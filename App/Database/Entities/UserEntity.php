<?php

namespace App\Database\Entities;

use Core\Database\Entity;
use InvalidArgumentException;

class UserEntity extends Entity
{
    public function __construct(
        private int $isActive,
        private string $name,
        private string $email,
        private int $companyId,
        private string $password,
        private int $level,
        private ?string $createdAt = null,
        private ?string $updatedAt = null
    ) {
    }

    public static function create(array $properties): Entity
    {
        if (empty($properties)) {
            throw new InvalidArgumentException('Invalid or empty properties array provided to create UserEntity.');
        }

        $id = isset($properties['id']) ? (int) $properties['id'] : null;
        $isActive = (int) ($properties['is_active'] ?? 0);
        $name = trim((string) ($properties['name'] ?? ''));
        $email = strtolower(trim((string) ($properties['email'] ?? '')));
        $companyId = (int) ($properties['company_id'] ?? 0);
        $password = (string) ($properties['password'] ?? '');
        $level = (int) ($properties['level'] ?? 0);
        $createdAt = $properties['created_at'] ?? null;
        $updatedAt = $properties['updated_at'] ?? null;

        if ($name === '') {
            throw new InvalidArgumentException('User name cannot be empty.');
        }

        if ($email === '') {
            throw new InvalidArgumentException('User email cannot be empty.');
        }

        if ($companyId <= 0) {
            throw new InvalidArgumentException('User company ID cannot be empty or zero.');
        }

        if ($password === '') {
            throw new InvalidArgumentException('User password cannot be empty.');
        }

        if ($level <= 0) {
            throw new InvalidArgumentException('User level must be a positive integer.');
        }

        $entity = new static(
            isActive: $isActive,
            name: $name,
            email: $email,
            companyId: $companyId,
            password: $password,
            level: $level,
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );

        if ($id !== null) {
            $entity->id = $id;
        }

        return $entity;
    }

    ## Getters
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getIsActive(): int
    {
        return $this->isActive;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getCompanyId(): int
    {
        return $this->companyId;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function getLevel(): int
    {
        return $this->level;
    }
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    ## Setters
    public function setIsActive(int $isActive): void
    {
        $this->isActive = $isActive;
    }
    public function setName(string $name): void
    {
        $this->name = trim($name);
    }
    public function setEmail(string $email): void
    {
        $this->email = strtolower(trim($email));
    }
    public function setCompanyId(int $companyId): void
    {
        $this->companyId = $companyId;
    }
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }
    public function setUpdatedAt(?string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
