<?php

namespace App\Services;

use Core\Dbal\Entity;
use Core\Dbal\Exceptions\EntityNotFoundException;

use App\Database\Entities\UserEntity;
use App\Database\Repositories\UserRepository;
use App\Exceptions\CompanyNotExistsException;
use App\Exceptions\EmailAlreadyExistsException;
use App\Database\Repositories\CompanyRepository;
use Core\Library\Logger;
use RuntimeException;
use InvalidArgumentException;

class UserService
{
    public function __construct(
      private UserRepository $userRepository, 
      private CompanyRepository $companyRepository,
      private Logger $logger,
    ) {
    }

    public function createUser(array $userData): Entity
    {
        if ($this->userRepository->emailExists($userData['email'])) {
            throw new EmailAlreadyExistsException('The email already exists');
        }

        try {
            $company = $this->companyRepository->getCompanyById($userData['company_id']);
        } catch (EntityNotFoundException $e) {
            throw new CompanyNotExistsException("Company with ID {$userData['company_id']} does not exist.", 0, $e);
        }

        $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);

        $data = [
          'name' => $userData['name'],
          'email' => $userData['email'],
          'company_id' => $company->id,
          'password' => $hashedPassword,
          'level' => $userData['level'],
        ];

        try {
            $entity = UserEntity::create($data);
        } catch (InvalidArgumentException $e) {
            $this->logger->error('Failed to create UserEntity due to invalid data: ' . $e->getMessage(), ['data' => $data]);
            
            throw new RuntimeException('Internal error: invalid data provided for user entity creation.', 0, $e);
        }

        $insertedId = $this->userRepository->create($entity);

        if ($insertedId <= 0) {
            throw new RuntimeException('Internal error when persisting user in database');
        }

        return $entity;
    }
}
