<?php

namespace Core\Library;

use Exception;

use Core\Library\Sanitize;

class Request
{
    public function __construct(
        public readonly array $get,
        public readonly array $post,
        public readonly array $server,
        public readonly array $cookie,
    ) {
    }

    public static function create()
    {
        return new static($_GET, $_POST, $_SERVER, $_FILES, $_COOKIE);
    }

    public function getRequest(string $request): Sanitize
    {
        $request = strtolower($request);

        if (!in_array($request, ['post', 'get'])) {
            throw new Exception('Request ' . $request . ' not accepted');
        }

        $sanitize = new Sanitize();
        $sanitize->execute($this->$request);

        return $sanitize;
    }
}
