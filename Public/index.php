<?php

require_once '../vendor/autoload.php';

use App\Exceptions\CompanyNotExistsException;
use Core\Dbal\Exceptions\EntityNotFoundException;

session_start();
require_once '../App/Config/bootstrap.php';

try {
    require_once '../App/Routes/web.php';
} catch (EntityNotFoundException | CompanyNotExistsException $e) {
    http_response_code(404);
    header('Location: /404');
    exit;
} catch (\Throwable $e) {
    http_response_code(500);
    error_log($e->getMessage());
    header('Location: /500');
    exit;
}

