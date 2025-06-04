<?php

namespace core\dbal;

abstract class Entity
{
    abstract public static function create(array $properties): Entity;
}
