<?php

namespace App\Exceptions;

use RuntimeException;

class CompanyNotExistsException extends RuntimeException
{
    public function __construct(
        string $message = 'The company does not exist.',
        int $code = 404,
    ) {
        parent::__construct($message, $code);
    }
}
