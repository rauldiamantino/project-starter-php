<?php

namespace App\Exceptions;

use RuntimeException;

class CompanyHasDependentsException extends RuntimeException
{
    public function __construct(
        string $message = 'It is not possible to delete the company because it has related data.',
        int $code = 409,
    ) {
        parent::__construct($message, $code);
    }
}
