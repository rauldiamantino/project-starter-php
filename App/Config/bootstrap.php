<?php

use Core\Library\Container;

$services = require basePath() . '/App/Services/Services.php';
$container = new Container();
$container = $container->build(['services']);

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__, 3));
$dotenv->load();