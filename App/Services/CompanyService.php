<?php

namespace App\Services;

use Core\Dbal\Entity;
use Core\Library\Logger;
use Core\Utils\SlugGenerator;

use App\Database\Entities\CompanyEntity;
use App\Exceptions\CnpjAlreadyExistsException;
use App\Exceptions\NameAlreadyExistsException;
use App\Database\Repositories\CompanyRepository;

use InvalidArgumentException;
use RuntimeException;

class CompanyService
{
    public function __construct(
        private CompanyRepository $companyRepository,
        private Logger $logger
    ) {

    }

    public function createCompany(array $companyData): Entity
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

        $insertedId = $this->companyRepository->create($entity);

        if ($insertedId <= 0) {
            throw new RuntimeException('Internal error when persisting company in database');
        }

        return $entity;
    }
}
