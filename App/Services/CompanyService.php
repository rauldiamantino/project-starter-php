<?php

namespace App\Services;

use Throwable;
use PDOException;
use RuntimeException;
use Core\Library\Logger;
use Core\Utils\SlugGenerator;
use InvalidArgumentException;
use App\Database\Entities\CompanyEntity;
use App\Exceptions\CompanyNotExistsException;
use App\Exceptions\CnpjAlreadyExistsException;
use App\Exceptions\NameAlreadyExistsException;
use App\Database\Repositories\ArticleRepository;
use App\Database\Repositories\CategoryRepository;
use App\Exceptions\CompanyHasDependentsException;
use App\Database\Repositories\Interfaces\ArticleContentRepositoryInterface;
use App\Database\Repositories\Interfaces\CompanyRepositoryInterface;
use App\Database\Repositories\Interfaces\UserRepositoryInterface;

class CompanyService
{
    public function __construct(
        private Logger $logger,
        private CompanyRepositoryInterface $companyRepositoryInterface,
        private CategoryRepository $categoryRepository,
        private UserRepositoryInterface $userRepositoryInterface,
        private ArticleRepository $articleRepository,
        private ArticleContentRepositoryInterface $articleContentRepositoryInterface,
    ) {
    }

    public function createCompany(array $companyData): CompanyEntity
    {
        if ($this->companyRepositoryInterface->nameExists($companyData['name'])) {
            throw new NameAlreadyExistsException('The name already exists');
        }

        if ($this->companyRepositoryInterface->cnpjExists(onlyNumbers($companyData['cnpj']))) {
            throw new CnpjAlreadyExistsException('The cnpj already exists');
        }

        $data = [
            'name' => $companyData['name'],
            'cnpj' => onlyNumbers($companyData['cnpj']),
            'slug' => $this->generateUniqueSlug($companyData['name']),
        ];

        try {
            $entity = CompanyEntity::create($data);
        } catch (InvalidArgumentException $e) {
            $this->logger->error('Failed to create CompanyEntity due to invalid data: ' . $e->getMessage(), ['data' => $data]);
            throw new RuntimeException('Internal error: invalid data provided for company entity creation.', 0, $e);
        }

        return $this->companyRepositoryInterface->createCompany($entity);
    }

    public function editCompany(int $id, array $companyData): CompanyEntity
    {
        if ($this->companyRepositoryInterface->nameExists($companyData['name'], $id)) {
            throw new NameAlreadyExistsException('The name already exists');
        }

        if ($this->companyRepositoryInterface->cnpjExists(onlyNumbers($companyData['cnpj']), $id)) {
            throw new CnpjAlreadyExistsException('The cnpj already exists');
        }

        $entity = $this->companyRepositoryInterface->getCompanyById($id);
        $entity->setName($companyData['name']);
        $entity->setCnpj(onlyNumbers($companyData['cnpj']));
        $entity->setIsActive($companyData['is_active']);
        $entity->setUpdatedAt(date('Y-m-d H:i:s'));
        $this->companyRepositoryInterface->updateCompany($entity);

        return $entity;
    }

    public function deleteCompanyById(int $id): void
    {
        $company = $this->companyRepositoryInterface->getCompanyById($id);

        if (!$company) {
            throw new CompanyNotExistsException("Company with ID {$id} does not exist.");
        }

        if ($this->companyHasAnyDependents($id)) {
            throw new CompanyHasDependentsException("Cannot delete company {$company->getName()} because it has related data.");
        }

        try {
            $this->companyRepositoryInterface->deleteCompany($company);
        } catch (PDOException $e) {
            $this->logger->error('Persistence error when deleting company: ' . $e->getMessage());
            throw new RuntimeException('Error deleting company from database.', 0, $e);
        } catch (Throwable $e) {
            $this->logger->error('Unexpected error in CompanyService::deleteCompany: ' . $e->getMessage());
            throw new RuntimeException('Internal error when deleting the company.', 0, $e);
        }
    }

    public function findAllCompanies(): array
    {
        return $this->companyRepositoryInterface->findAllCompanies();
    }

    public function getCompanyById(int $id): CompanyEntity
    {
        return $this->companyRepositoryInterface->getCompanyById($id);
    }


    private function generateUniqueSlug(string $base): string
    {
        $slug = SlugGenerator::generate($base);
        $finalSlug = $slug;
        $counter = 1;

        while ($this->companyRepositoryInterface->slugExists($finalSlug)) {
            $finalSlug = $slug . '-' . $counter++;

            if ($counter > 100) {
                throw new RuntimeException("Could not generate a unique slug for company: {$base}. Too many attempts.");
            }
        }

        return $finalSlug;
    }

    private function companyHasAnyDependents(int $companyId): bool
    {
        $repositoriesToCheck = [
            $this->userRepositoryInterface,
            $this->categoryRepository,
            $this->articleRepository,
            $this->articleContentRepositoryInterface,
        ];

        foreach ($repositoriesToCheck as $repository) {
            if ($repository->existsByCompanyId($companyId)) {
                return true;
            }
        }

        return false;
    }
}
