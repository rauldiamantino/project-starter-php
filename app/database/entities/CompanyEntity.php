<?php

namespace app\database\entities;

use core\dbal\Entity;
use core\dbal\exceptions\EntityNotFound;

class CompanyEntity extends Entity
{
  public function __construct
  (
    public readonly string $name,
    public readonly string $slug,
    public readonly string $cnpj,
    public readonly ?int $id = null,
    public readonly ?string $createdAt = null,
    public readonly ?string $updatedAt = null
  ) {}

  public static function create(array|bool $properties): Entity
  {
    if (! is_array($properties)) {
      return new EntityNotFound();
    }

    return new self(
      name: trim($properties['name'] ?? ''),
      slug: strtolower(trim($properties['slug'] ?? '')),
      cnpj: trim($properties['cnpj'] ?? ''),
      id: $properties['id'] ?? null,
      createdAt: $properties['created_at'] ?? null,
      updatedAt: $properties['updated_at'] ?? null,
    );
  }
}
