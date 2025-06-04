<?php

namespace App\Services;

use RuntimeException;
use App\Database\Repositories\UserRepository;
use App\Database\Entities\UserEntity;
use Core\Dbal\Exceptions\EntityNotFound;
use App\Exceptions\EmailAlreadyExistsException;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(array $userData): Entity
    {
        if ($this->userRepository->emailExists($userData['email'])) {
            throw new EmailAlreadyExistsException('The email already exists');
        }

        $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);

        $data = [
          'name' => $userData['name'],
          'email' => $userData['email'],
          'company_id' => $userData['company_id'],
          'password' => $hashedPassword,
          'level' => $userData['level'],
        ];

        $entity = UserEntity::create($data);

        if ($entity instanceof EntityNotFound) {
            throw new RuntimeException('Internal error: could not create user entity');
        }

        $insertedId = $this->userRepository->create($entity);

        if ($insertedId <= 0) {
            throw new RuntimeException('Internal error when persisting user in database');
        }

        return $entity;
    }
}
