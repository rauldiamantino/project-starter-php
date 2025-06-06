<?php

namespace App\Services;

use Throwable;
use RuntimeException;

use Core\Library\Logger;
use Core\Utils\SlugGenerator;
use InvalidArgumentException;
use App\Database\Entities\CompanyEntity;

use App\Exceptions\CnpjAlreadyExistsException;
use App\Exceptions\NameAlreadyExistsException;
use App\Database\Repositories\CompanyRepository;
use App\Exceptions\CompanyNotExistsException;

class CompanyService
{
    public function __construct(
        private CompanyRepository $companyRepository,
        private Logger $logger
    ) {}

    public function createCompany(array $companyData): CompanyEntity
    {
        $name = $companyData['name'];
        $cnpj = onlyNumbers($companyData['cnpj']);

        if ($this->companyRepository->nameExists($name)) {
            throw new NameAlreadyExistsException('The name already exists');
        }

        if ($this->companyRepository->cnpjExists($cnpj)) {
            throw new CnpjAlreadyExistsException('The cnpj already exists');
        }

        $baseSlug = SlugGenerator::generate($name);
        $finalSlug = $baseSlug;

        $counter = 1;
        while ($this->companyRepository->slugExists($finalSlug)) {
            $finalSlug = $baseSlug . '-' . $counter++;

            if ($counter > 100) {
                throw new RuntimeException("Could not generate a unique slug for company: {$name}. Too many attempts.");
            }
        }

        $data = [
            'name' => $name,
            'cnpj' => $cnpj,
            'slug' => $finalSlug,
        ];

        try {
            $entity = CompanyEntity::create($data);
        } catch (InvalidArgumentException $e) {
            $this->logger->error('Failed to create CompanyEntity due to invalid data: ' . $e->getMessage(), ['data' => $data]);
            throw new RuntimeException('Internal error: invalid data provided for company entity creation.', 0, $e);
        }

        return $this->companyRepository->create($entity);
    }

    public function deleteCompany(int $id): void // Retorna void porque o sucesso é implicado e a falha é por exceção
    {
        $company = $this->companyRepository->getCompanyById($id);

        if (!$company) {
            throw new CompanyNotExistsException("Company with ID {$id} does not exists.");
        }

        // if ($company->hasDependents()) {
        //     throw new CompanyHasDependentsException("Cannot delete company {$company->getName()} because it has related data.");
        // }

        try {
            $this->companyRepository->delete($company);
        } catch (\PDOException $e) {
            $this->logger->error('Persistence error when deleting company: ' . $e->getMessage());
            throw new \Exception('Error deleting company from database.', 0, $e);
        } catch (Throwable $e) {
            $this->logger->error('Unexpected error in CompanyService::deleteCompany: ' . $e->getMessage());
            throw new \Exception('Internal error when deleting the company.', 0, $e);
        }
    }
}
