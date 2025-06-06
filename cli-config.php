<?php

require_once basePath() . '/vendor/autoload.php';
require basePath() . '/App/Config/bootstrap.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\DBAL\Connection as DBALConnection;

$conn = $container->get(DBALConnection::class);

$config = new PhpFile('migrations.php');

return DependencyFactory::fromConnection($config, new ExistingConnection($conn));