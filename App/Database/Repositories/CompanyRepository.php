<?php

namespace App\Database\Repositories;

use Core\Dbal\Repository;
use Core\Dbal\exceptions\EntityNotFoundException;

use PDOException;
use InvalidArgumentException;
use RuntimeException;
use Doctrine\DBAL\Exception as DBALException;

use App\Database\Entities\CompanyEntity;

class CompanyRepository extends Repository
{
    protected string $table = 'companies';

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

    public function getCompanyById(int $id): CompanyEntity
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();

            $selected = $queryBuilder->select(
                'id',
                'name',
                'slug',
                'cnpj',
                'created_at AS createdAt',
                'updated_at AS updatedAt',
            )
                ->from($this->table)
                ->where('id = :id')
                ->setParameter('id', $id)
                ->fetchAssociative();

            if ($selected === false || empty($selected)) {
                $this->logger->info("Company with ID '{$id}' not found in database.");

                throw new EntityNotFoundException("Company with ID '{$id}' not found.");
            }

            $companyEntity = $this->createEntityFromData($selected);

            return $companyEntity;
        } catch (DBALException $e) {
            $this->logger->error('DBAL Error in Company::getCompanyById: ' . $e->getMessage(), ['table' => $this->table]);

            throw new RuntimeException("Database error while fetching company with ID '{$id}'.", 0, $e);
        } catch (PDOException $e) {
            $this->logger->critical('PDO Error in Company::getCompanyById: ' . $e->getMessage(), ['table' => $this->table]);

            throw new RuntimeException("Database connection error while fetching company with ID '{$id}'.", 0, $e);
        }
    }

    public function create(CompanyEntity $entity): CompanyEntity
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();

            $result = $queryBuilder->insert($this->table)
                ->setValue('name', ':name')
                ->setValue('slug', ':slug')
                ->setValue('cnpj', ':cnpj')
                ->setParameter('name', $entity->name)
                ->setParameter('slug', $entity->slug)
                ->setParameter('cnpj', $entity->cnpj)
                ->executeStatement();

            if ($result === 0) {
                $this->logger->error('Failed to insert the company record into the database.', ['name' => $entity->name]);

                throw new RuntimeException('Failed to insert the company record into the database');
            }

            $insertedId = (int) $this->connection->lastInsertId();

            $entityData = [
                'id' => $insertedId,
                'name' => $entity->name,
                'slug' => $entity->slug,
                'cnpj' => $entity->cnpj,
            ];

            return CompanyEntity::create($entityData);
        } catch (DBALException $e) {
            $this->logger->error(
                'DBAL Error in Company::create: ' . $e->getMessage(),
                [
                    'exception' => $e->getTraceAsString(),
                    'table' => $this->table,
                    'name' => $entity->name,
                ],
            );

            throw new RuntimeException('Database error while creating company. Please try again', 0, $e);
        } catch (PDOException $e) {
            $this->logger->critical('PDO Error in Company::create: ' . $e->getMessage(), [
                'table' => $this->table, 
                'name' => $entity->name,
              ]);

            throw new RuntimeException('Database connection error while creating company', 0, $e);
        }
    }

    public function cnpjExists(string $cnpj): bool
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();

            $count = $queryBuilder->select('COUNT(id)')
                ->from($this->table)
                ->where('cnpj = :cnpj')
                ->setParameter('cnpj', $cnpj)
                ->fetchOne();

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

    public function nameExists(string $name): bool
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();

            $count = $queryBuilder->select('COUNT(id)')
                ->from($this->table)
                ->where('name = :name')
                ->setParameter('name', $name)
                ->fetchOne();

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

            return $count > 0;
        } catch (DBALException $e) {
            $this->logger->error('DBAL Error in Company::slugExists: ' . $e->getMessage(), ['slug' => $slug]);

            throw new RuntimeException('Database error while checking slug existence.', 0, $e);
        } catch (PDOException $e) {
            $this->logger->critical('PDO Error in Company::slugExists: ' . $e->getMessage(), ['slug' => $slug]);

            throw new RuntimeException('Database connection error while checking slug existence.', 0, $e);
        }
    }

    public function delete(CompanyEntity $entity): int
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();

            $deletedRows = $queryBuilder->delete($this->table)
                ->where('id = :id')
                ->setParameter('id', $entity->id)
                ->executeStatement();

            if ($deletedRows === 0) {
                $this->logger->warning("Attempted to delete company with ID '{$entity->id}' but no rows were affected.");
            } else {
                $this->logger->info("User with ID '{$entity->id}' deleted successfully. Affected rows: {$deletedRows}.");
            }

            return $deletedRows;
        } catch (DBALException $e) {
            $this->logger->error('DBAL Error deleting company with ID ' . $entity->id . ': ' . $e->getMessage(), [
                'table' => $this->table,
                'id' => $entity->id,
            ]);

            throw new RuntimeException('Database error during company deletion.', 0, $e);
        } catch (PDOException $e) {
            $this->logger->critical('PDO Error deleting company with ID ' . $entity->id . ': ' . $e->getMessage(), [
                'table' => $this->table,
                'id' => $entity->id,
            ]);

            throw new RuntimeException('Database error during company deletion.', 0, $e);
        }
    }
}
