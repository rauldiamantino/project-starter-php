<?php

namespace Core\Library;

use Core\Library\Twig;
use Core\Library\Request;
use Core\Library\Redirect;
use Core\Library\Response;

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
