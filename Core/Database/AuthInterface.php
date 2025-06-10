<?php

namespace Core\Database;

interface AuthInterface
{
    public function auth(string $email): Entity;
}
