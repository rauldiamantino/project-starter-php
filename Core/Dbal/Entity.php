<?php

namespace Core\Dbal;

abstract class Entity
{
    public readonly ?int $id;
    abstract public static function create(array $properties): Entity;
}
