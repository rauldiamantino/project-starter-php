<?php

namespace app\database\entities;

use core\dbal\Entity;
use InvalidArgumentException;

class UserEntity extends Entity
{
  public function __construct(
    public readonly string $name,
    public readonly string $email,
    public readonly int $companyId,
    public readonly string $password,
    public readonly int $level,
    public readonly ?int $id = null,
    public readonly ?string $createdAt = null,
    public readonly ?string $updatedAt = null
  ) {}

  public static function create(array $properties): Entity
  {
    if (! is_array($properties) || empty($properties)) {
      throw new InvalidArgumentException('Invalid properties provided to create UserEntity.');
    }

    return new self(
      id: $properties['id'] ?? null,
      name: trim($properties['name'] ?? ''),
      email: strtolower(trim($properties['email'] ?? '')),
      companyId: (int) ($properties['company_id'] ?? 0),
      password: $properties['password'] ?? '',
      level: (int) ($properties['level'] ?? 0),
      createdAt: $properties['created_at'] ?? null,
      updatedAt: $properties['updated_at'] ?? null
    );
  }
}
