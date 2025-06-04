<?php

namespace Core\Dbal;

interface AuthInterface
{
    public function auth(string $email): Entity;
}
