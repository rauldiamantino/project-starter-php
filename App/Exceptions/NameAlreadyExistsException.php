<?php

namespace App\Exceptions;

use Throwable;
use RuntimeException;

class NameAlreadyExistsException extends RuntimeException
{
    public function __construct(
        string $message = 'The email provided is already in use.',
        int $code = 409,
        Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
