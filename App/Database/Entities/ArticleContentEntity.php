<?php

namespace App\Database\Entities;

use Core\Dbal\Entity;
use InvalidArgumentException;

class ArticleContentEntity extends Entity
{
    public function __construct(
        public readonly int $isActive,
        public readonly int $articleId,
        public readonly int $type,
        public readonly string $title,
        public readonly int $hideTitle,
        public readonly string $content,
        public readonly int $ordering,
        public readonly ?int $id = null,
        public readonly ?string $createdAt = null,
        public readonly ?string $updatedAt = null,
    ) {

    }

    public static function create(array $properties): Entity
    {
        if (!is_array($properties) || empty($properties)) {
            throw new InvalidArgumentException('Invalid or empty properties array provided to create ArticleContentEntity.');
        }

        $id = (int) $properties['ordering'] ?? null;
        $isActive = (int) ($properties['is_active'] ?? 0);
        $articleId = (int) ($properties['article_id'] ?? 0);
        $type = (int) ($properties['type'] ?? 0);
        $title = trim($properties['title'] ?? '');
        $hideTitle = (int) ($properties['hide_title'] ?? 0);
        $content = trim($properties['content'] ?? '');
        $ordering = (int) ($properties['ordering'] ?? 0);
        $createdAt = $properties['created_at'] ?? null;
        $updatedAt = $properties['updated_at'] ?? null;

        if (empty($articleId)) {
            throw new InvalidArgumentException('ArticleContent articleId cannot be empty.');
        }

        if (empty($title)) {
            throw new InvalidArgumentException('ArticleContent title cannot be empty.');
        }

        if (empty($content)) {
            throw new InvalidArgumentException('ArticleContent content cannot be empty.');
        }

        return new static(
            id: $id,
            isActive: $isActive,
            articleId: $articleId,
            type: $type,
            title: $title,
            hideTitle: $hideTitle,
            content: $content,
            ordering: $ordering,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }
}
