<?php

namespace App\Exceptions;

use RuntimeException;

class CnpjAlreadyExistsException extends RuntimeException
{
    public function __construct(
        string $message = 'The cnpj provided is already in use.',
        int $code = 409,
    ) {
        parent::__construct($message, $code);
    }
}
