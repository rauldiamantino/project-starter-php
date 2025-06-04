<?php

namespace Core\Library;

use Twig\Environment;
use Twig\TwigFunction;
use Twig\Loader\FilesystemLoader;

class Twig
{
    public readonly Environment $env;

    public function __construct()
    {
        $loader = new FilesystemLoader(dirname(__FILE__, 3) . '/App/Views');

        $this->env = new Environment($loader, ['debug' => false]);
    }

    public function add_functions()
    {
        $functions = require dirname(__FILE__, 2) . '/Functions/twig.php';

        foreach ($functions as $index => $function) {
            $function = new TwigFunction($index, $function);
            $this->env->addFunction($function);
        }
    }
}
