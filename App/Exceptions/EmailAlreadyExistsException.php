<?php

namespace App\Exceptions;

use RuntimeException;

class EmailAlreadyExistsException extends RuntimeException
{
    public function __construct(
        string $message = 'The email provided is already in use.',
        int $code = 409,
    ) {
        parent::__construct($message, $code);
    }
}
