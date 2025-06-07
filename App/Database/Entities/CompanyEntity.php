<?php

namespace App\Database\Entities;

use Core\Dbal\Entity;
use InvalidArgumentException;

class CompanyEntity extends Entity
{
    public function __construct(
        private int $isActive,
        private string $cnpj,
        private string $slug,
        private string $name,
        private ?string $createdAt = null,
        private ?string $updatedAt = null
    ) {}

    public static function create(array $properties): Entity
    {
        if (empty($properties)) {
            throw new InvalidArgumentException('Invalid or empty properties array provided to create CompanyEntity.');
        }

        $id = isset($properties['id']) ? (int) $properties['id'] : null;
        $isActive = (int) ($properties['is_active'] ?? 0);
        $name = trim((string) ($properties['name'] ?? ''));
        $slug = strtolower(trim((string) ($properties['slug'] ?? '')));
        $cnpj = trim((string) ($properties['cnpj'] ?? ''));
        $createdAt = $properties['created_at'] ?? null;
        $updatedAt = $properties['updated_at'] ?? null;

        if ($name === '') {
            throw new InvalidArgumentException('Company name cannot be empty.');
        }

        if ($slug === '') {
            throw new InvalidArgumentException('Company slug cannot be empty.');
        }

        if ($cnpj === '') {
            throw new InvalidArgumentException('Company CNPJ cannot be empty.');
        }

        $entity = new static(
            isActive: $isActive,
            cnpj: $cnpj,
            slug: $slug,
            name: $name,
            createdAt: $createdAt,
            updatedAt: $updatedAt
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
    public function getName(): string
    {
        return $this->name;
    }
    public function getSlug(): string
    {
        return $this->slug;
    }
    public function getCnpj(): string
    {
        return $this->cnpj;
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
    public function setName(string $name): void
    {
        $this->name = trim($name);
    }
    public function setCnpj(string $cnpj): void
    {
        $this->cnpj = trim($cnpj);
    }
    public function setSlug(string $slug): void
    {
        $this->slug = strtolower(trim($slug));
    }
    public function setUpdatedAt(?string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
