<?php

namespace App\Database\Entities;

use Core\Dbal\Entity;
use InvalidArgumentException;

class CategoryEntity extends Entity
{
    public function __construct(
        public readonly int $isActive,
        public readonly int $parentId,
        public readonly string $name,
        public readonly string $description,
        public readonly int $companyId,
        public readonly int $ordering,
        public readonly ?int $id = null,
        public readonly ?string $createdAt = null,
        public readonly ?string $updatedAt = null,
    ) {
    }

    public static function create(array $properties): Entity
    {
        if (!is_array($properties) || empty($properties)) {
            throw new InvalidArgumentException('Invalid or empty properties array provided to create CategoryEntity.');
        }

        $id = $properties['id'] ?? null;
        $isActive = (int) ($properties['is_active'] ?? 0);
        $name = trim($properties['name'] ?? '');
        $description = trim($properties['description'] ?? '');
        $companyId = (int) ($properties['company_id'] ?? 0);
        $parentId = (int) ($properties['parent_id'] ?? 0);
        $ordering = (int) ($properties['ordering'] ?? 0);
        $createdAt = $properties['created_at'] ?? null;
        $updatedAt = $properties['updated_at'] ?? null;

        if (empty($name)) {
            throw new InvalidArgumentException('Category name cannot be empty.');
        }

        if (empty($slug)) {
            throw new InvalidArgumentException('Category name cannot be empty.');
        }

        if (empty($companyId)) {
            throw new InvalidArgumentException('Company ID cannot be empty.');
        }

        return new static(
            id: $id,
            isActive: $isActive,
            name: $name,
            description: $description,
            companyId: $companyId,
            parentId: $parentId,
            ordering: $ordering,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }
}
