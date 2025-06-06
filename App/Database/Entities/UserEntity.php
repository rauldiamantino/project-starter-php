<?php

namespace App\Database\Entities;

use Core\Dbal\Entity;
use InvalidArgumentException;

class UserEntity extends Entity
{
    public function __construct(
        public readonly int $isActive,
        public readonly string $name,
        public readonly string $email,
        public readonly int $companyId,
        public readonly string $password,
        public readonly int $level,
        public readonly ?int $id = null,
        public readonly ?string $createdAt = null,
        public readonly ?string $updatedAt = null
    ) {
    }

    public static function create(array $properties): Entity
    {
        if (!is_array($properties) || empty($properties)) {
            throw new InvalidArgumentException('Invalid properties provided to create UserEntity.');
        }

        $id = (int) $properties['id'] ?? null;
        $isActive = (int) ($properties['isActive'] ?? 0);
        $name = trim($properties['name'] ?? '');
        $email = strtolower(trim($properties['email'] ?? ''));
        $companyId = (int) ($properties['company_id'] ?? 0);
        $password = $properties['password'] ?? '';
        $level = (int) ($properties['level'] ?? 0);
        $createdAt = $properties['created_at'] ?? null;
        $updatedAt = $properties['updated_at'] ?? null;

        if (empty($name)) {
            throw new InvalidArgumentException('User name cannot be empty.');
        }

        if (empty($email)) {
            throw new InvalidArgumentException('User email cannot be empty.');
        }

        if (empty($companyId)) {
            throw new InvalidArgumentException('User companyId cannot be empty.');
        }

        if (empty($password)) {
            throw new InvalidArgumentException('User password cannot be empty.');
        }

        if ($level <= 0) {
            throw new InvalidArgumentException('User level must be a positive integer.');
        }

        return new self(
            id: $id,
            isActive: $isActive,
            name: $name,
            email: $email,
            companyId: $companyId,
            password: $password,
            level: $level,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }
}
