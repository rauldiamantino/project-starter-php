<?php

namespace App\Database\Entities;

use Core\Dbal\Entity;
use InvalidArgumentException;

class ArticleContentEntity extends Entity
{
    public function __construct(
        private int $isActive,
        private int $articleId,
        private int $type,
        private string $title,
        private int $hideTitle,
        private string $content,
        private int $ordering,
        private ?string $createdAt = null,
        private ?string $updatedAt = null,
    ) {
    }

    public static function create(array $properties): Entity
    {
        if (empty($properties)) {
            throw new InvalidArgumentException('Invalid or empty properties array provided to create ArticleContentEntity.');
        }

        $id = (int) $properties['id'] ?? null;
        $isActive = (int) ($properties['is_active'] ?? 0);
        $articleId = (int) ($properties['article_id'] ?? 0);
        $type = (int) ($properties['type'] ?? 0);
        $title = trim((string) ($properties['title'] ?? ''));
        $hideTitle = (int) ($properties['hide_title'] ?? 0);
        $content = trim((string) ($properties['content'] ?? ''));
        $ordering = (int) ($properties['ordering'] ?? 0);
        $createdAt = $properties['created_at'] ?? null;
        $updatedAt = $properties['updated_at'] ?? null;

        if ($articleId <= 0) {
            throw new InvalidArgumentException('ArticleContent article ID cannot be empty or zero.');
        }

        if ($type <= 0) {
            throw new InvalidArgumentException('ArticleContent type cannot be empty or zero.');
        }

        if ($title === '') {
            throw new InvalidArgumentException('ArticleContent title cannot be empty.');
        }

        if ($content === '') {
            throw new InvalidArgumentException('ArticleContent content cannot be empty.');
        }

        $entity = new static(
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
    public function getArticleId(): int
    {
        return $this->articleId;
    }
    public function getType(): int
    {
        return $this->type;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getHideTitle(): int
    {
        return $this->hideTitle;
    }
    public function getContent(): string
    {
        return $this->content;
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
    public function setArticleId(int $articleId): void
    {
        $this->articleId = $articleId;
    }
    public function setType(int $type): void
    {
        $this->type = $type;
    }
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
    public function setHideTitle(int $hideTitle): void
    {
        $this->hideTitle = $hideTitle;
    }
    public function setContent(string $content): void
    {
        $this->content = $content;
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
