<?php

use Core\Library\Session;

$app_functions = dirname(__FILE__, 3) . '/app/functions/twig.php';

if (!file_exists($app_functions)) {
    throw new Exception('Please create functions file inside functions folder');
}

$functions = [
  'flash' => function (string $index, string $cssClass = 'error') {
      $flash = Session::get('__flash');

      if (isset($flash[$index])) {
          return '<span class="' . $cssClass . '">' . $flash[$index] . '</span>';
      }
  },
  'session_has' => function (string $index) {
      return Session::has($index);
  },

  'session_get' => function (string $index) {
      return Session::get($index);
  },
  'session_get_flash' => function (string $index) {
      return Session::get_flash($index);
  },
  'session_has_flash' => function (string $index) {
      return Session::has_flash($index);
  },
  'auth' => function () {
      return Session::get('auth');
  }
];

$include_app_functions = require($app_functions);

if (!is_array($include_app_functions)) {
    throw new Exception('twig file must return an array');
}

return [...$functions, ...$include_app_functions];
