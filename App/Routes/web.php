<?php

use Core\Library\Router;

use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\CompanyController;

$admin = require basePath() . '/App/Routes/admin.php';

$router = $container->get(Router::class);

$router->add('GET', '/', [HomeController::class, 'index']);

$router->add('GET', '/users', [UserController::class, 'index']);
$router->add('GET', '/user/{id:[0-9]+}', [UserController::class, 'show']);
$router->add('GET', '/user/create', [UserController::class, 'create']);
$router->add('POST', '/user/store', [UserController::class, 'store']);

$router->add('GET', '/companies', [CompanyController::class, 'index']);
$router->add('GET', '/company/{id:[0-9]+}', [CompanyController::class, 'show']);
$router->add('GET', '/company/create', [CompanyController::class, 'create']);
$router->add('POST', '/company/store', [CompanyController::class, 'store']);

$router->run();
