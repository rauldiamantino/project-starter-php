<?php

namespace core\library;

class Session
{
    public static function set(string $index, mixed $value)
    {
        $_SESSION[$index] = $value;
    }

    public static function has(string $index)
    {
        return isset($_SESSION[$index]);
    }

    public static function get(string $index)
    {
        if (self::has($index)) {
            return $_SESSION[$index];
        }
    }

    public static function remove_session(string $index)
    {
        if (self::has($index)) {
            unset($_SESSION[$index]);
        }
    }

    public static function remove_all_sessions()
    {
        session_destroy();
    }

    public static function has_flash(string $index): bool
    {
        return isset($_SESSION['__flash'][$index]);
    }

    public static function get_flash(string $index, mixed $default = null): mixed
    {
        if (isset($_SESSION['__flash'][$index])) {
            $message = $_SESSION['__flash'][$index];

            // Remove after reading
            unset($_SESSION['__flash'][$index]);

            return $message;
        }

        return $default;
    }

    public static function get_all_flashes(): array
    {
        $messages = $_SESSION['__flash'] ?? [];

        // Remove all after reading
        if (isset($_SESSION['__flash'])) {
            unset($_SESSION['__flash']);
        }

        return $messages;
    }

    public static function flashes(array $messages)
    {
        foreach ($messages as $index => $message) {
            $_SESSION['__flash'][$index] = $message;
        }
    }

    public static function flash(string $index, mixed $value)
    {
        $_SESSION['__flash'][$index] = $value;
    }

    public static function flash_remove()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && self::has('__flash')) {
            unset($_SESSION['__flash']);
        }
    }

    public static function debug()
    {
        var_dump($_SESSION);
        exit;
    }
}
