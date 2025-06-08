<?php

namespace App\Database\Repositories;

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

    public function createUser(UserEntity $entity): UserEntity
    {
        return $this->create($entity);
    }

    public function deleteUser(UserEntity $entity): void
    {
        $this->delete($entity);
    }

    public function updateUser(UserEntity $entity): void
    {
        $this->update($entity);
    }

    public function findAllUsers(): array
    {
        return $this->findAll();
    }

    public function getUserById(int $id): UserEntity
    {
        return $this->getById($id);
    }

    public function findUserById(int $id): ?UserEntity
    {
        return $this->findById($id);
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
            $this->logger->error(
                'DBAL Error in User::emailExists: ' . $e->getMessage(),
                [
                    'table' => $this->table,
                    'email' => $email,
                ],
            );

            throw new RuntimeException('Database error while checking email', 0, $e);
        } catch (PDOException $e) {
            $this->logger->critical(
                'PDO Error in User::emailExists: ' . $e->getMessage(),
                [
                    'table' => $this->table,
                    'email' => $email,
                ]
            );

            throw new RuntimeException('Database connection error when checking email', 0, $e);
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
            $this->logger->error(
                'DBAL Error in UserRepository::auth: ' . $e->getMessage(),
                [
                    'table' => $this->table,
                    'email' => $email,
                ]
            );

            throw new RuntimeException("Database error during authentication for email '{$email}'.", 0, $e);
        } catch (PDOException $e) {
            $this->logger->critical(
                'PDO Error in UserRepository::auth: ' . $e->getMessage(),
                [
                    'table' => $this->table,
                    'email' => $email,
                ]
            );

            throw new RuntimeException("Database connection error during authentication for email '{$email}'.", 0, $e);
        }
    }

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

    protected function mapEntityToData(object $entity): array
    {
        /** @var UserEntity $entity */
        return [
            'is_active' => $entity->getIsActive(),
            'name' => $entity->getName(),
            'email' => $entity->getEmail(),
            'password' => $entity->getPassword(),
            'company_id' => $entity->getCompanyId(),
            'level' => $entity->getLevel(),
        ];
    }
}
