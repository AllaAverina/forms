<?php

namespace Forms\Helpers;

class Route
{
    public static function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    public static function back(): void
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
