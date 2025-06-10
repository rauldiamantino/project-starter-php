<?php

namespace App\Database\Repositories\Implementations\Doctrine;

use Core\Dbal\Repository;
use PDOException;
use InvalidArgumentException;
use RuntimeException;
use Doctrine\DBAL\Exception as DBALException;
use App\Database\Entities\CompanyEntity;
use App\Database\Repositories\Interfaces\CompanyRepositoryInterface;

class CompanyRepositoryDoctrine extends Repository implements CompanyRepositoryInterface
{
    protected string $table = 'companies';

    public function createCompany(CompanyEntity $entity): CompanyEntity
    {
        return $this->create($entity);
    }

    public function deleteCompany(CompanyEntity $entity): void
    {
        $this->delete($entity);
    }

    public function updateCompany(CompanyEntity $entity): void
    {
        $this->update($entity);
    }

    public function findAllCompanies(): array
    {
        return $this->findAll();
    }

    public function getCompanyById(int $id): CompanyEntity
    {
        return $this->getById($id);
    }

    public function findCompanyById(int $id): ?CompanyEntity
    {
        return $this->findById($id);
    }

    public function cnpjExists(string $cnpj, ?int $id = null): bool
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();

            $queryBuilder->select('COUNT(id)')
                ->from($this->table)
                ->where('cnpj = :cnpj')
                ->setParameter('cnpj', $cnpj);

            if ($id !== null) {
                $queryBuilder->andWhere('id != :id')
                        ->setParameter('id', (int) $id);
            }

            $count = $queryBuilder->fetchOne();

            return (int) $count > 0;
        } catch (DBALException $e) {
            $this->logger->error('DBAL Error in Company::cnpjExists: ' . $e->getMessage(), [
                'table' => $this->table,
                'cnpj' => $cnpj,
            ]);

            throw new RuntimeException('Database error while checking cnpj', 0, $e);
        } catch (PDOException $e) {
            $this->logger->error('PDO Error in Company::cnpjExists: ' . $e->getMessage(), [
                'table' => $this->table,
                'cnpj' => $cnpj,
            ]);

            throw new RuntimeException('Database connection error when checking cnpj', 0, $e);
        }
    }

    public function nameExists(string $name, ?int $id = null): bool
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();

            $queryBuilder->select('COUNT(id)')
                ->from($this->table)
                ->where('name = :name')
                ->setParameter('name', $name);

            if ($id !== null) {
                $queryBuilder->andWhere('id != :id')
                        ->setParameter('id', (int) $id);
            }

            $count = $queryBuilder->fetchOne();

            return (int) $count > 0;
        } catch (DBALException $e) {
            $this->logger->error('DBAL Error in Company::nameExists: ' . $e->getMessage(), [
                'table' => $this->table,
                'name' => $name,
            ]);

            throw new RuntimeException('Database error while checking name', 0, $e);
        } catch (PDOException $e) {
            $this->logger->error('PDO Error in Company::nameExists: ' . $e->getMessage(), [
                'table' => $this->table,
                'name' => $name,
            ]);

            throw new RuntimeException('Database connection error when checking name', 0, $e);
        }
    }

    public function slugExists(string $slug): bool
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();

            $count = $queryBuilder->select('COUNT(id)')
                ->from($this->table)
                ->where('slug = :slug')
                ->setParameter('slug', $slug)
                ->fetchOne();

            return (int) $count > 0;
        } catch (DBALException $e) {
            $this->logger->error('DBAL Error in Company::slugExists: ' . $e->getMessage(), ['slug' => $slug]);
            throw new RuntimeException('Database error while checking slug existence.', 0, $e);
        } catch (PDOException $e) {
            $this->logger->critical('PDO Error in Company::slugExists: ' . $e->getMessage(), ['slug' => $slug]);
            throw new RuntimeException('Database connection error while checking slug existence.', 0, $e);
        }
    }

    protected function createEntityFromData(array $data): CompanyEntity
    {
        try {
            $companyEntity = CompanyEntity::create($data);
        } catch (InvalidArgumentException $e) {
            $this->logger->error('Invalid data from database for CompanyEntity: ' . $e->getMessage(), ['data' => $data]);
            throw new RuntimeException('Internal failure: Corrupted data received from database for CompanyEntity.', 0, $e);
        }

        return $companyEntity;
    }

    protected function mapEntityToData(object $entity): array
    {
        /** @var CompanyEntity $entity */
        return [
            'is_active' => $entity->getIsActive(),
            'name' => $entity->getName(),
            'cnpj' => $entity->getCnpj(),
            'slug' => $entity->getSlug(),
        ];
    }
}
