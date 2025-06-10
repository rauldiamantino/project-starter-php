<?php

namespace Core\Database;

abstract class Entity
{
    protected ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    abstract public static function create(array $properties): Entity;
}
