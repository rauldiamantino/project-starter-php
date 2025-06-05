<?php

namespace App\Exceptions;

use Throwable;
use RuntimeException;

class CompanyNotExistsException extends RuntimeException
{
    public function __construct(
        string $message = 'The company does not exist.',
        int $code = 404,
        Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
