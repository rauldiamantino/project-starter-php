<?php

use Core\Library\Router;

use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\CompanyController;

$admin = require basePath() . '/App/Routes/admin.php';

$router = $container->get(Router::class);

$router->add('GET', '/', [HomeController::class, 'index']);

$router->add('GET', '/users', [UserController::class, 'index']);
$router->add('GET', '/users/{id:[0-9]+}', [UserController::class, 'show']);
$router->add('GET', '/users/create', [UserController::class, 'create']);
$router->add('POST', '/users/store', [UserController::class, 'store']);

$router->add('GET', '/companies', [CompanyController::class, 'index']);
$router->add('GET', '/companies/{id:[0-9]+}', [CompanyController::class, 'show']);
$router->add('GET', '/companies/create', [CompanyController::class, 'create']);
$router->add('POST', '/companies/store', [CompanyController::class, 'store']);

$router->run();
