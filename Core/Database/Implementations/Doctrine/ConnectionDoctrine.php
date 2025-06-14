<?php

namespace Core\Database\Implementations\Doctrine;

use Doctrine\DBAL\DriverManager;

class ConnectionDoctrine
{
    public static function create()
    {
        return DriverManager::getConnection([
          'dbname' => $_ENV['DB_NAME'],
          'user' => $_ENV['DB_USERNAME'],
          'password' => $_ENV['DB_PASSWORD'],
          'host' => $_ENV['DB_HOST'],
          'driver' => $_ENV['DB_DRIVER'],
        ]);
    }
}
