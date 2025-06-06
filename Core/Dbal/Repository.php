<?php

namespace Core\Dbal;

use Core\Dbal\Entity;
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
    ) {
    }

    abstract protected function createEntityFromData(array $data): Entity;

    public function getAll(): array
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();

            $results = $queryBuilder->select('*')
                ->from($this->table)
                ->fetchAllAssociative();

            $entities = [];
            foreach ($results as $data) {
                $entity = $this->createEntityFromData($data);
                $entities[] = $entity;
            }

            return $entities;

        } catch (DBALException $e) {
            $this->logger->error('DBAL Error in Repository::getAll: ' . $e->getMessage(), ['table' => $this->table]);

            throw new RuntimeException('Database error while fetching all records.', 0, $e);
        } catch (PDOException $e) {
            $this->logger->critical('PDO Error in Repository::getAll: ' . $e->getMessage(), ['table' => $this->table]);

            throw new RuntimeException('Database connection error while fetching all records.', 0, $e);
        }
    }
}
