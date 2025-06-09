<?php 

namespace App\Database\Repositories\Interfaces;

use App\Database\Entities\CompanyEntity;

interface CompanyRepositoryInterface
{
    public function createCompany(CompanyEntity $entity): CompanyEntity;
    public function deleteCompany(CompanyEntity $entity): void;
    public function updateCompany(CompanyEntity $entity): void;
    public function findAllCompanies(): array;
    public function getCompanyById(int $id): CompanyEntity;
    public function findCompanyById(int $id): ?CompanyEntity;
    public function cnpjExists(string $cnpj, ?int $id = null): bool;
    public function nameExists(string $name, ?int $id = null): bool;
    public function slugExists(string $slug): bool;
}