<?php

namespace Core\Utils;

class SlugGenerator
{
    public static function generate(string $text, string $separator = '-'): string
    {
        $slug = strtolower($text);
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $slug);
        $slug = preg_replace('/[^a-z0-9' . preg_quote($separator) . ']/', $separator, $slug);
        $slug = preg_replace('/[' . preg_quote($separator) . ']+/', $separator, $slug);
        $slug = trim($slug, $separator);

        return $slug;
    }
}
