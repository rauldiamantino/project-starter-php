<?php

namespace App\Database\Entities;

use Core\Dbal\Entity;
use InvalidArgumentException;

class CategoryEntity extends Entity
{
    public function __construct(
        private int $isActive,
        private int $parentId,
        private string $name,
        private string $slug,
        private string $description,
        private int $companyId,
        private int $ordering,
        private ?string $createdAt = null,
        private ?string $updatedAt = null,
    ) {
    }

    public static function create(array $properties): Entity
    {
        if (empty($properties)) {
            throw new InvalidArgumentException('Invalid or empty properties array provided to create CategoryEntity.');
        }

        $id = isset($properties['id']) ? (int) $properties['id'] : null;
        $isActive = (int) ($properties['is_active'] ?? 0);
        $name = trim((string) ($properties['name'] ?? ''));
        $slug = strtolower(trim((string) ($properties['slug'] ?? '')));
        $description = trim((string) ($properties['description'] ?? ''));
        $companyId = (int) ($properties['company_id'] ?? 0);
        $parentId = (int) ($properties['parent_id'] ?? 0);
        $ordering = (int) ($properties['ordering'] ?? 0);
        $createdAt = $properties['created_at'] ?? null;
        $updatedAt = $properties['updated_at'] ?? null;

        if ($name === '') {
            throw new InvalidArgumentException('Category name cannot be empty.');
        }

        if ($slug === '') {
            throw new InvalidArgumentException('Category slug cannot be empty.');
        }

        if ($companyId <= 0) {
            throw new InvalidArgumentException('Category company ID cannot be empty or zero.');
        }

        $entity = new static(
            isActive: $isActive,
            parentId: $parentId,
            name: $name,
            slug: $slug,
            description: $description,
            companyId: $companyId,
            ordering: $ordering,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );

        if ($id !== null) {
            $entity->id = $id;
        }

        return $entity;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getIsActive(): int
    {
        return $this->isActive;
    }
    public function getParentId(): int
    {
        return $this->parentId;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getSlug(): string
    {
        return $this->slug;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getCompanyId(): int
    {
        return $this->companyId;
    }
    public function getOrdering(): int
    {
        return $this->ordering;
    }
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    // Setters
    public function setIsActive(int $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    public function setName(string $name): void
    {
        $this->name = trim($name);
    }

    public function setSlug(string $slug): void
    {
        $this->slug = strtolower(trim($slug));
    }

    public function setDescription(string $description): void
    {
        $this->description = trim($description);
    }

    public function setCompanyId(int $companyId): void
    {
        $this->companyId = $companyId;
    }

    public function setOrdering(int $ordering): void
    {
        $this->ordering = $ordering;
    }

    public function setUpdatedAt(?string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
