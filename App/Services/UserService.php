<?php

namespace App\Services;

use Core\Dbal\Entity;
use RuntimeException;
use Core\Library\Logger;
use InvalidArgumentException;
use App\Database\Entities\UserEntity;
use App\Database\Repositories\UserRepository;
use App\Exceptions\CompanyNotExistsException;
use App\Exceptions\EmailAlreadyExistsException;
use App\Database\Repositories\CompanyRepository;
use Core\Dbal\Exceptions\EntityNotFoundException;

class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private CompanyRepository $companyRepository,
        private Logger $logger,
    ) {
    }

    public function createUser(array $userData): UserEntity
    {
        try {
            $company = $this->companyRepository->getCompanyById($userData['company_id']);
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

        return $this->userRepository->createUser($entity);
    }

    public function editUser(int $id, array $userData): UserEntity
    {
        if ($this->userRepository->emailExists($userData['email'], $id)) {
            throw new EmailAlreadyExistsException('The email already exists');
        }

        $entity = $this->userRepository->getUserById($id);
        $entity->setIsActive($userData['is_active']);
        $entity->setName($userData['name']);
        $entity->setEmail($userData['email']);
        $entity->setLevel($userData['level']);
        $entity->setUpdatedAt(date('Y-m-d H:i:s'));
        $this->userRepository->updateUser($entity);

        return $entity;
    }

    public function deleteUserById(int $id): void
    {
        $user = $this->userRepository->getUserById($id);
        $this->userRepository->deleteUser($user);
    }

    public function findAllUsers(): array
    {
        return $this->userRepository->findAllUsers();
    }

    public function getUserById(int $id): Entity
    {
        return $this->userRepository->getUserById($id);
    }

    private function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
