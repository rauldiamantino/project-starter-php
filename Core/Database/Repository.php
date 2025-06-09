<?php 

namespace Core\Database;

use Core\Library\Logger;

abstract class Repository
{
    protected Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    // abstract protected function mapToStorageFormat(object $entity): mixed;
    // abstract protected function mapFromStorageFormat(mixed $rawData): Entity;
}


