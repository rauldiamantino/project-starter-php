<?php

function publicPath()
{
    return $_SERVER['DOCUMENT_ROOT'];
}

function basePath()
{
    return dirname(__FILE__, 3);
}

function removeFile(string $file)
{
    @unlink(publicPath() . $file);
}

function pr(mixed $value, bool $dump = true)
{
    echo '<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>';
    echo '<pre class="w-max w-screen h-screen bg-black text-white">';

    if ($dump == false) {
        var_dump($value);
    } else {
        print_r($value);
    }

    echo '</pre>';
}

function onlyNumbers(string $value): string {
    return preg_replace('/\D/', '', $value);
}
