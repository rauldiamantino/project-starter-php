<?php

namespace App\Database\Entities;

use Core\Database\Entity;
use InvalidArgumentException;

class ArticleEntity extends Entity
{
    public function __construct(
        private int $isActive,
        private string $title,
        private string $slug,
        private int $userId,
        private int $companyId,
        private int $categoryId,
        private int $viewsCount,
        private int $ordering,
        private ?string $createdAt = null,
        private ?string $updatedAt = null,
    ) {
    }

    public static function create(array $properties): Entity
    {
        if (empty($properties)) {
            throw new InvalidArgumentException('Invalid or empty properties array provided to create ArticleEntity.');
        }

        $id = isset($properties['id']) ? (int) $properties['id'] : null;
        $isActive = (int) ($properties['is_active'] ?? 0);
        $title = trim((string) ($properties['title'] ?? ''));
        $slug = trim((string) ($properties['slug'] ?? ''));
        $userId = (int) ($properties['user_id'] ?? 0);
        $companyId = (int) ($properties['company_id'] ?? 0);
        $categoryId = (int) ($properties['category_id'] ?? 0);
        $viewsCount = (int) ($properties['views_count'] ?? 0);
        $ordering = (int) ($properties['ordering'] ?? 0);
        $createdAt = $properties['created_at'] ?? null;
        $updatedAt = $properties['updated_at'] ?? null;

        if ($title === '') {
            throw new InvalidArgumentException('Article title cannot be empty.');
        }

        if ($slug === '') {
            throw new InvalidArgumentException('Article slug cannot be empty.');
        }

        if ($userId <= 0) {
            throw new InvalidArgumentException('Article user ID cannot be empty or zero.');
        }

        if ($companyId <= 0) {
            throw new InvalidArgumentException('Article company ID cannot be empty or zero.');
        }

        if ($categoryId <= 0) {
            throw new InvalidArgumentException('Article category ID cannot be empty or zero.');
        }

        $entity = new static(
            isActive: $isActive,
            title: $title,
            slug: $slug,
            userId: $userId,
            companyId: $companyId,
            categoryId: $categoryId,
            viewsCount: $viewsCount,
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
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getSlug(): string
    {
        return $this->slug;
    }
    public function getUserId(): int
    {
        return $this->userId;
    }
    public function getCompanyId(): int
    {
        return $this->companyId;
    }
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }
    public function getViewsCount(): int
    {
        return $this->viewsCount;
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
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }
    public function setCompanyId(int $companyId): void
    {
        $this->companyId = $companyId;
    }
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }
    public function setViewsCount(int $viewsCount): void
    {
        $this->viewsCount = $viewsCount;
    }
    public function setOrdering(int $ordering): void
    {
        $this->ordering = $ordering;
    }
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
