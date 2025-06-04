<?php

use App\Controllers\HomeController;
use Core\Library\Router;
use App\Controllers\UserController;
use App\Controllers\LoginController;

$admin = require basePath() . '/app/routes/admin.php';

$router = $container->get(Router::class);
$router->add('GET', '/', [HomeController::class, 'index']);
$router->add('GET', '/login', [LoginController::class, 'index']);
$router->add('POST', '/login', [LoginController::class, 'store']);
$router->add('DELETE', '/logout', [LoginController::class, 'destroy']);
$router->add('GET', '/users', [UserController::class, 'index']);
$router->add('GET', '/user/{id:[0-9]+}', [UserController::class, 'show']);
$router->add('GET', '/user/create', [UserController::class, 'create']);
$router->add('POST', '/user/store', [UserController::class, 'store']);

$router->run();
