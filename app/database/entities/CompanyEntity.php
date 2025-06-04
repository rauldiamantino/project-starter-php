<?php

namespace app\database\entities;

use core\dbal\Entity;
use InvalidArgumentException;

class CompanyEntity extends Entity
{
  public function __construct(
    public readonly string $name,
    public readonly string $slug,
    public readonly string $cnpj,
    public readonly ?int $id = null,
    public readonly ?string $createdAt = null,
    public readonly ?string $updatedAt = null
  ) {}

  public static function create(array $properties): Entity
  {
    if (! is_array($properties) or empty($properties)) {
      throw new InvalidArgumentException('Invalid or empty properties array provided to create CompanyEntity.');
    }

    $id = $properties['id'] ?? null;
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
      name: $name,
      slug: $slug,
      cnpj: $cnpj,
      createdAt: $createdAt,
      updatedAt: $updatedAt,
    );
  }
}
