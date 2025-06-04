<?php

namespace app\exceptions;

use Throwable;
use RuntimeException;

class EmailAlreadyExistsException extends RuntimeException
{
  public function __construct(string $message = 'The email provided is already in use.', int $code = 409, Throwable $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}
