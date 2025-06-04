<?php

namespace core\library;

use core\library\Twig;
use core\library\Request;
use core\library\Redirect;
use core\library\Response;

abstract class Controller
{
    protected Request $request;

    public function __construct(private Twig $twig)
    {
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    protected function render(string $template, array $data = [], int $statusCode = 200): Response
    {
        $html = $this->twig->env->render($template, $data);

        return new Response($html, $statusCode);
    }

    protected function redirect(string $url, string $type = '', string $message = '', int $statusCode = 302): Response
    {
        $redirect = new Redirect($url);

        if ($type && $message) {
            $redirect->withMessage($type, $message);
        }

        return $redirect;
    }
}
