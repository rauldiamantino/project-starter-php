<?php

namespace core\library;

use core\library\Response;

class Redirect extends Response
{
    public function __construct(string $uri)
    {
        parent::__construct('', 302, ['location' => $uri]);
    }

    public function send()
    {
        return header('Location: ' . $this->headers['location'], true, $this->statusCode);
    }

    public function withMessage(string $index, string $message)
    {
        Session::flash($index, $message);

        return $this;
    }
}
