<?php

namespace App\Database\Repositories;

use Core\Dbal\Entity;
use Core\Dbal\Repository;
use Core\Dbal\AuthInterface;
use Core\Dbal\Exceptions\EntityNotFoundException;

use PDOException;
use InvalidArgumentException;
use RuntimeException;
use Doctrine\DBAL\Exception as DBALException;

use App\Database\Entities\UserEntity;

class UserRepository extends Repository implements AuthInterface
{
    protected string $table = 'users';

    protected function createEntityFromData(array $data): UserEntity
    {
        try {
            $userEntity = UserEntity::create($data);
        } catch (InvalidArgumentException $e) {
            $this->logger->error('Invalid data from database for UserEntity: ' . $e->getMessage(), ['data' => $data]);

            throw new RuntimeException('Internal failure: Corrupted data received from database for UserEntity.', 0, $e);
        }

        return $userEntity;
    }

    public function getUserById(int $id): UserEntity
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();

            $selected = $queryBuilder->select(
                'id',
                'name',
                'email',
                'password',
                'company_id AS companyId',
                'level',
                'created_at AS createdAt',
                'updated_at AS updatedAt',
            )
                ->from($this->table)
                ->where('id = :id')
                ->setParameter('id', $id)
                ->fetchAssociative();

            if ($selected === false || empty($selected)) {
                $this->logger->info("User with ID '{$id}' not found in database.");

                throw new EntityNotFoundException("User with ID '{$id}' not found.");
            }

            $userEntity = $this->createEntityFromData($selected);

            if ($userEntity instanceof EntityNotFoundException) {
                throw new RuntimeException("Failed to create UserEntity from fetched data for ID '{$id}'.");
            }

            return $userEntity;
        } catch (DBALException $e) {
            $this->logger->error('DBAL Error in User::getUserById: ' . $e->getMessage(), ['table' => $this->table]);

            throw new RuntimeException("Database error while fetching user with ID '{$id}'.", 0, $e);
        } catch (PDOException $e) {
            $this->logger->critical('PDO Error in User::getUserById: ' . $e->getMessage(), ['table' => $this->table]);

            throw new RuntimeException("Database connection error while fetching user with ID '{$id}'.", 0, $e);
        }
    }

    public function create(UserEntity $entity): UserEntity
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();

            $result = $queryBuilder->insert($this->table)
                ->setValue('name', ':name')
                ->setValue('email', ':email')
                ->setValue('password', ':password')
                ->setValue('company_id', ':company_id')
                ->setValue('level', ':level')
                ->setParameter('name', $entity->name)
                ->setParameter('email', $entity->email)
                ->setParameter('password', $entity->password)
                ->setParameter('company_id', $entity->companyId)
                ->setParameter('level', $entity->level)
                ->executeStatement();

            if ($result === 0) {
                $this->logger->error('Failed to insert the user record into the database, 0 rows affected.', ['email' => $entity->email]);

                throw new RuntimeException('Failed to insert the user record into the database');
            }

            $insertedId = (int) $this->connection->lastInsertId();

            $entityData = [
                'id' => $insertedId,
                'name' => $entity->name,
                'email' => $entity->email,
                'password' => $entity->password,
                'companyId' => $entity->companyId,
                'level' => $entity->level,
            ];

            return UserEntity::create($entityData);
        } catch (DBALException $e) {
            $this->logger->error('DBAL Error in User::create: ' . $e->getMessage(), [
                'table' => $this->table,
                'email' => $entity->email,
            ]);

            throw new RuntimeException('Database error while creating user. Please try again', 0, $e);
        } catch (PDOException $e) {
            $this->logger->critical('PDO Error in User::create: ' . $e->getMessage(), [
                'table' => $this->table,
                'email' => $entity->email,
            ]);

            throw new RuntimeException('Database connection error while creating user', 0, $e);
        }
    }

    public function emailExists(string $email): bool
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();

            $count = $queryBuilder->select('COUNT(id)')
                ->from($this->table)
                ->where('email = :email')
                ->setParameter('email', $email)
                ->fetchOne();

            return (int) $count > 0;
        } catch (DBALException $e) {
            $this->logger->error('DBAL Error in User::emailExists: ' . $e->getMessage(), [
                'table' => $this->table,
                'email' => $email,
              ],
            );

            throw new RuntimeException('Database error while checking email', 0, $e);
        } catch (PDOException $e) {
            $this->logger->critical('PDO Error in User::emailExists: ' . $e->getMessage(), [
                'table' => $this->table,
                'email' => $email,
            ]);

            throw new RuntimeException('Database connection error when checking email', 0, $e);
        }
    }

    public function delete(UserEntity $entity): int
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();

            $deletedRows = $queryBuilder->delete($this->table)
                ->where('id = :id')
                ->setParameter('id', $entity->id)
                ->executeStatement();

            if ($deletedRows === 0) {
                $this->logger->warning("Attempted to delete user with ID '{$entity->id}' but no rows were affected. User might not exist or already deleted.");
            } else {
                $this->logger->info("User with ID '{$entity->id}' deleted successfully. Affected rows: {$deletedRows}.");
            }

            return $deletedRows;
        } catch (DBALException $e) {
            $this->logger->error('DBAL Error deleting user with ID ' . $entity->id . ': ' . $e->getMessage(), [
                'table' => $this->table,
                'id' => $entity->id,
            ]);

            throw new RuntimeException('Database error during user deletion.', 0, $e);
        } catch (PDOException $e) {
            $this->logger->critical('PDO Error deleting user with ID ' . $entity->id . ': ' . $e->getMessage(), [
                'table' => $this->table,
                'id' => $entity->id,
            ]);

            throw new RuntimeException('Database connection error during user deletion.', 0, $e);
        }
    }

    public function auth(string $email): UserEntity
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();

            $selected = $queryBuilder->select(
                'id',
                'name',
                'email',
                'password',
                'company_id AS companyId',
                'level'
            )
                ->from('users')
                ->where('email = :email')
                ->setParameter('email', $email)
                ->fetchAssociative();

            if ($selected === false || empty($selected)) {
                $this->logger->info("Authentication attempt for email '{$email}' failed: User not found.");

                throw new EntityNotFoundException("User with email '{$email}' not found.");
            }

            return $this->createEntityFromData($selected);
        } catch (DBALException $e) {
            $this->logger->error('DBAL Error in UserRepository::auth: ' . $e->getMessage(), [
                'table' => $this->table,
                'email' => $email,
            ]);

            throw new RuntimeException("Database error during authentication for email '{$email}'.", 0, $e);
        } catch (PDOException $e) {
            $this->logger->critical('PDO Error in UserRepository::auth: ' . $e->getMessage(), [
                'table' => $this->table,
                'email' => $email,
            ]);

            throw new RuntimeException("Database connection error during authentication for email '{$email}'.", 0, $e);
        }
    }
}
