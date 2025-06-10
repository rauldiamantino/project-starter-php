<?php

use Core\Library\Twig;
use Core\Library\Logger;
use Core\Dbal\Connection;
use Core\Library\Request;
use Twig\Extension\DebugExtension;
use Doctrine\DBAL\Connection as DBALConnection;
use App\Database\Repositories\Interfaces\UserRepositoryInterface;
use App\Database\Repositories\Interfaces\CompanyRepositoryInterface;
use App\Database\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Database\Repositories\Interfaces\ArticleRepositoryInterface;
use App\Database\Repositories\Interfaces\ArticleContentRepositoryInterface;
use App\Database\Repositories\Implementations\Doctrine\UserRepositoryDoctrine;
use App\Database\Repositories\Implementations\Doctrine\CompanyRepositoryDoctrine;
use App\Database\Repositories\Implementations\Doctrine\CategoryRepositoryDoctrine;
use App\Database\Repositories\Implementations\Doctrine\ArticleRepositoryDoctrine;
use App\Database\Repositories\Implementations\Doctrine\ArticleContentRepositoryDoctrine;

use function DI\get;
use function DI\create;

$logFilePath = dirname(__FILE__, 3) . '/Temp/Logs/application.log';

return [
    Request::class => Request::create(),
    DBALConnection::class => Connection::create(),
    Logger::class => create(Logger::class)->constructor($logFilePath),
    Twig::class => function () {
        $twig = new Twig();
        $twig->add_functions();
        $twig->env->addExtension(new DebugExtension());
        return $twig;
    },
    UserRepositoryInterface::class => create(UserRepositoryDoctrine::class)
        ->constructor(get(DBALConnection::class), get(Logger::class)),
    CompanyRepositoryInterface::class => create(CompanyRepositoryDoctrine::class)
        ->constructor(get(DBALConnection::class), get(Logger::class)),
    CategoryRepositoryInterface::class => create(CategoryRepositoryDoctrine::class)
        ->constructor(get(DBALConnection::class), get(Logger::class)),
    ArticleRepositoryInterface::class => create(ArticleRepositoryDoctrine::class)
        ->constructor(get(DBALConnection::class), get(Logger::class)),
    ArticleContentRepositoryInterface::class => create(ArticleContentRepositoryDoctrine::class)
        ->constructor(get(DBALConnection::class), get(Logger::class)),
];
