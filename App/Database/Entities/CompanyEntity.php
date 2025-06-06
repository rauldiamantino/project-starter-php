<?php

namespace App\Database\Entities;

use Core\Dbal\Entity;
use InvalidArgumentException;

class CompanyEntity extends Entity
{
    public function __construct(
        public readonly int $isActive,
        public readonly string $cnpj,
        public readonly string $slug,
        public readonly string $name,
        public readonly ?int $id = null,
        public readonly ?string $createdAt = null,
        public readonly ?string $updatedAt = null
    ) {
    }

    public static function create(array $properties): Entity
    {
        if (!is_array($properties) || empty($properties)) {
            throw new InvalidArgumentException('Invalid or empty properties array provided to create CompanyEntity.');
        }

        $id = (int) $properties['id'] ?? null;
        $isActive = (int) ($properties['is_active'] ?? 0);
        $name = trim($properties['name'] ?? '');
        $slug = strtolower(trim($properties['slug'] ?? ''));
        $cnpj = trim($properties['cnpj'] ?? '');
        $createdAt = $properties['created_at'] ?? null;
        $updatedAt = $properties['updated_at'] ?? null;

        if (empty($name)) {
            throw new InvalidArgumentException('Company name cannot be empty.');
        }

        if (empty($slug)) {
            throw new InvalidArgumentException('Company slug cannot be empty.');
        }

        if (empty($cnpj)) {
            throw new InvalidArgumentException('Company CNPJ cannot be empty.');
        }

        return new static(
            id: $id,
            isActive: $isActive,
            name: $name,
            slug: $slug,
            cnpj: $cnpj,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }
}
