<?php

use Core\Library\Twig;
use Core\Dbal\Connection;
use Core\Library\Logger;
use Core\Library\Request;

use Twig\Extension\DebugExtension;
use Doctrine\DBAL\Connection as DBALConnection;

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
];
