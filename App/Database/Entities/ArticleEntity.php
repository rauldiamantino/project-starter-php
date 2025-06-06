<?php

namespace App\Database\Entities;

use Core\Dbal\Entity;
use InvalidArgumentException;

class ArticleEntity extends Entity
{
    public function __construct(
        public readonly int $isActive,
        public readonly string $title,
        public readonly string $slug,
        public readonly int $userId,
        public readonly int $companyId,
        public readonly int $categoryId,
        public readonly int $viewsCount,
        public readonly int $ordering,
        public readonly ?int $id = null,
        public readonly ?string $createdAt = null,
        public readonly ?string $updatedAt = null,
    ) {

    }

    public static function create(array $properties): Entity
    {
        if (!is_array($properties) || empty($properties)) {
          throw new InvalidArgumentException('Invalid or empty properties array provided to create ArticleEntity.');
        }

        $id = $properties['id'] ?? null;
        $isActive = (int) ($properties['is_active'] ?? 0);
        $title = trim($properties['title'] ?? '');
        $slug = trim($properties['slug'] ?? '');
        $userId = (int) ($properties['user_id'] ?? 0);
        $companyId = (int) ($properties['company_id'] ?? 0);
        $categoryId = (int) ($properties['category_id'] ?? 0);
        $viewsCount = (int) ($properties['views_id'] ?? 0);
        $ordering = (int) ($properties['ordering'] ?? 0);
        $createdAt = $properties['created_at'] ? null;
        $updatedAt = $properties['updated_at'] ? null;

        if (empty($title)) {
            throw new InvalidArgumentException('Article title cannot be empty.');
        }

        if (empty($slug)) {
            throw new InvalidArgumentException('Article slug cannot be empty.');
        }

        if (empty($userId)) {
            throw new InvalidArgumentException('Article userId cannot be empty.');
        }

        if (empty($companyId)) {
            throw new InvalidArgumentException('Article companyId cannot be empty.');
        }

        if (empty($categoryId)) {
            throw new InvalidArgumentException('Article categoryId cannot be empty.');
        }
    }
}
