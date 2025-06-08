<?php

use Core\Library\Router;
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\CompanyController;
use App\Controllers\NotFoundController;
use App\Controllers\ServerErrorController;

$admin = require basePath() . '/App/Routes/admin.php';

// Base
$router = $container->get(Router::class);
$router->add('GET', '/', [HomeController::class, 'index']);

// Error
$router->add('GET', '/404', [NotFoundController::class, 'index']);
$router->add('GET', '/500', [ServerErrorController::class, 'index']);

// User
$router->add('GET', '/users', [UserController::class, 'index']);
$router->add('GET', '/users/{id:[0-9]+}', [UserController::class, 'edit']);
$router->add('GET', '/users/create', [UserController::class, 'create']);
$router->add('POST', '/users', [UserController::class, 'store']);
$router->add('DELETE', '/users/{id:[0-9]+}', [UserController::class, 'delete']);
$router->add('PUT', '/users/{id:[0-9]+}', [UserController::class, 'update']);

// Company
$router->add('GET', '/companies', [CompanyController::class, 'index']);
$router->add('GET', '/companies/{id:[0-9]+}', [CompanyController::class, 'edit']);
$router->add('GET', '/companies/create', [CompanyController::class, 'create']);
$router->add('POST', '/companies', [CompanyController::class, 'store']);
$router->add('DELETE', '/companies/{id:[0-9]+}', [CompanyController::class, 'delete']);
$router->add('PUT', '/companies/{id:[0-9]+}', [CompanyController::class, 'update']);

$router->run();
