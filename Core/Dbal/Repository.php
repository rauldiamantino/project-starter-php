<?php

namespace Core\Dbal;

use Core\Dbal\Entity;
use Core\Dbal\exceptions\EntityNotFoundException;
use Core\Library\Logger;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;

use PDOException;
use RuntimeException;

abstract class Repository
{
    protected string $table;

    public function __construct(
        protected Connection $connection,
        protected Logger $logger
    ) {}

    abstract protected function createEntityFromData(array $data): Entity;

    abstract protected function mapEntityToData(object $entity): array;

    public function create(Entity $entity): Entity
    {
        if (property_exists($entity, 'id') && $entity->id !== null) {
            throw new RuntimeException('Cannot create an entity that already has an ID. Use update() instead.');
        }

        try {
            $dataToInsert = $this->mapEntityToData($entity);

            $result = $this->connection->insert($this->table, $dataToInsert);

            if ($result === 0) {
                $this->logger->error('Failed to insert record into database, 0 rows affected.', ['table' => $this->table, 'entity_data' => $dataToInsert]);

                throw new RuntimeException('Failed to insert the record into the database.');
            }

            $insertedId = (int) $this->connection->lastInsertId();

            $entityDataForReturn = array_merge($dataToInsert, ['id' => $insertedId]);

            return $this->createEntityFromData($entityDataForReturn);
        } catch (DBALException $e) {
            $this->logger->error('DBAL Error in ' . static::class . '::create: ' . $e->getMessage(), ['table' => $this->table]);

            throw new RuntimeException("Database error while creating entity in table '{$this->table}'.", 0, $e);
        } catch (PDOException $e) {
            $this->logger->critical('PDO Error in ' . static::class . '::create: ' . $e->getMessage(), ['table' => $this->table]);

            throw new RuntimeException("Database connection error while creating entity in table '{$this->table}'.", 0, $e);
        }
    }

    public function update(Entity $entity): void
    {
        if (!property_exists($entity, 'id') || $entity->id === null) {
            throw new RuntimeException("Cannot update an entity without an ID. Use create() for new entities.");
        }

        try {
            $dataToUpdate = $this->mapEntityToData($entity);

            $this->connection->update($this->table, $dataToUpdate, ['id' => $entity->id]);
        } catch (DBALException $e) {
            $this->logger->error('DBAL Error in ' . static::class . '::update: ' . $e->getMessage(), ['table' => $this->table]);

            throw new RuntimeException("Database error while updating entity in table '{$this->table}'.", 0, $e);
        } catch (PDOException $e) {
            $this->logger->critical('PDO Error in ' . static::class . '::update: ' . $e->getMessage(), ['table' => $this->table]);

            throw new RuntimeException("Database connection error while updating entity in table '{$this->table}'.", 0, $e);
        }
    }

    public function delete(Entity $entity): void
    {
        if (!property_exists($entity, 'id') || $entity->id === null) {
            $this->logger->warning('Attempted to delete an entity without an ID.', ['table' => $this->table]);

            throw new RuntimeException('Cannot delete an entity without an ID.');
        }

        try {
            $result = $this->connection->delete($this->table, ['id' => $entity->id]);

            if ($result === 0) {
                $this->logger->info("No record found with ID '{$entity->id}' for deletion'.", ['table' => $this->table]);
            }
        } catch (DBALException $e) {
            $this->logger->error('DBAL Error in ' . static::class . '::delete: ' . $e->getMessage(), ['table' => $this->table]);

            throw new RuntimeException("Database error while deleting entity with ID '{$entity->id}' from table '{$this->table}'.", 0, $e);
        } catch (PDOException $e) {
            $this->logger->critical('PDO Error in ' . static::class . '::delete: ' . $e->getMessage(), ['table' => $this->table]);

            throw new RuntimeException("Database connection error while deleting entity with ID '{$entity->id}' from table '{$this->table}'.", 0, $e);
        }
    }

    public function findById(int $id): Entity
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();

            $selected = $queryBuilder->select('*')
                ->from($this->table)
                ->where('id = :id')
                ->setParameter('id', $id)
                ->fetchAssociative();

            if ($selected === false || empty($selected)) {
                $this->logger->info("Entity with ID '{$id}' not found in table '{$this->table}'.");
                throw new EntityNotFoundException("Entity with ID '{$id}' not found.");
            }

            return $this->createEntityFromData($selected);
        } catch (DBALException $e) {
            $this->logger->error('DBAL Error in Repository::findById: ' . $e->getMessage(), ['table' => $this->table]);

            throw new RuntimeException("Database error while fetching entity with ID '{$id}'.", 0, $e);
        } catch (PDOException $e) {
            $this->logger->error('PDO Error in Repository::findById: ' . $e->getMessage(), ['table' => $this->table]);

            throw new RuntimeException("Database connecition error while fetching entity with ID '{$id}'.", 0, $e);
        }
    }

    public function findAll(array $orderBy = []): array
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();

            $results = $queryBuilder->select('*')
                ->from($this->table);

            foreach ($orderBy as $column => $direction) {
                $direction = (strtoupper($direction) === 'DESC') ? 'DESC' : 'ASC';
                $queryBuilder->addOrderBy($column, $direction);
            }

            $results = $queryBuilder->fetchAllAssociative();

            $entities = [];
            foreach ($results as $data) {
                $entities[] = $this->createEntityFromData($data);
            }

            return $entities;
        } catch (DBALException $e) {
            $this->logger->error('DBAL Error in Repository::findAll: ' . $e->getMessage(), ['table' => $this->table]);

            throw new RuntimeException('Database error while fetching all records.', 0, $e);
        } catch (PDOException $e) {
            $this->logger->critical('PDO Error in Repository::findAll: ' . $e->getMessage(), ['table' => $this->table]);

            throw new RuntimeException('Database connection error while fetching all records.', 0, $e);
        }
    }

    public function existsByCompanyId(int $companyId): bool
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();

            $count = $queryBuilder->select('COUNT(id)')
                ->from($this->table)
                ->where('company_id = :company_id')
                ->setParameter('company_id', $companyId)
                ->fetchOne();

            return (int) $count > 0;
        } catch (DBALException $e) {
            $this->logger->error(
                'DBAL Error in Repository::companyHasDependents: ' . $e->getMessage(),
                [
                    'table' => $this->table,
                    'companyId' => $companyId,
                ],
            );

            throw new RuntimeException('Database error while checking companyId', 0, $e);
        } catch (PDOException $e) {
            $this->logger->critical(
                'PDO Error in Repository::companyHasDependents: ' . $e->getMessage(),
                [
                    'table' => $this->table,
                    'companyId' => $companyId,
                ]
            );

            throw new RuntimeException('Database connection error when checking companyId', 0, $e);
        }
    }
}
