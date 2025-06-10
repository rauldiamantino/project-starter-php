<?php

namespace App\Services;

use RuntimeException;
use InvalidArgumentException;
use App\Database\Entities\UserEntity;
use App\Exceptions\CompanyNotExistsException;
use App\Exceptions\EmailAlreadyExistsException;
use App\Database\Repositories\Interfaces\CompanyRepositoryInterface;
use App\Database\Repositories\Interfaces\UserRepositoryInterface;
use Core\Dbal\Entity;
use Core\Library\Logger;
use Core\Dbal\Exceptions\EntityNotFoundException;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepositoryInterface,
        private CompanyRepositoryInterface $companyRepositoryInterface,
        private Logger $logger,
    ) {
    }

    public function createUser(array $userData): UserEntity
    {
        try {
            $company = $this->companyRepositoryInterface->getCompanyById($userData['company_id']);
        } catch (EntityNotFoundException $e) {
            throw new CompanyNotExistsException('This company does not exists');
        }

        $data = [
          'name' => $userData['name'],
          'is_active' => 1,
          'email' => $userData['email'],
          'company_id' => $company->getId(),
          'password' => $this->hashPassword($userData['password']),
          'level' => $userData['level'],
        ];

        try {
            $entity = UserEntity::create($data);
        } catch (InvalidArgumentException $e) {
            $this->logger->error('Failed to create UserEntity due to invalid data: ' . $e->getMessage(), ['data' => $data]);
            throw new RuntimeException('Internal error: invalid data provided for user entity creation.', 0, $e);
        }

        return $this->userRepositoryInterface->createUser($entity);
    }

    public function editUser(int $id, array $userData): UserEntity
    {
        if ($this->userRepositoryInterface->emailExists($userData['email'], $id)) {
            throw new EmailAlreadyExistsException('The email already exists');
        }

        $entity = $this->userRepositoryInterface->getUserById($id);
        $entity->setIsActive($userData['is_active']);
        $entity->setName($userData['name']);
        $entity->setEmail($userData['email']);
        $entity->setLevel($userData['level']);
        $entity->setUpdatedAt(date('Y-m-d H:i:s'));
        $this->userRepositoryInterface->updateUser($entity);

        return $entity;
    }

    public function deleteUserById(int $id): void
    {
        $user = $this->userRepositoryInterface->getUserById($id);
        $this->userRepositoryInterface->deleteUser($user);
    }

    public function findAllUsers(): array
    {
        return $this->userRepositoryInterface->findAllUsers();
    }

    public function getUserById(int $id): Entity
    {
        return $this->userRepositoryInterface->getUserById($id);
    }

    private function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
