<?php

namespace core\library;

class Response
{
    public function __construct(
        protected mixed $body,
        protected int $statusCode = 200,
        protected array $headers = [],
    ) {
    }

    public function send()
    {
        http_response_code($this->statusCode);

        if ($this->headers) {
            foreach ($this->headers as $index => $value) {
                header($index . ':' . $value);
            }
        }

        if (in_array('application/json', $this->headers)) {
            echo json_encode($this->body);
        } else {
            echo $this->body;
        }
    }
}
