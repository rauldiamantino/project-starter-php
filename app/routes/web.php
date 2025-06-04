<?php

use app\controllers\HomeController;
use core\library\Router;
use app\controllers\UserController;
use app\controllers\LoginController;

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
