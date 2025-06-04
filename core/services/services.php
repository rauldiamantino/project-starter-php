<?php

use core\library\Twig;
use core\dbal\Connection;
use core\library\Logger;
use core\library\Request;
use Twig\Extension\DebugExtension;
use Doctrine\DBAL\Connection as DBALConnection;

use function DI\create;

$logFilePath = dirname(__FILE__, 2) . '/temp/logs/application.log';

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
