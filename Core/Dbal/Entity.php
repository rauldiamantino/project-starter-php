<?php

namespace Core\Dbal;

abstract class Entity
{
    abstract public static function create(array $properties): Entity;
}
