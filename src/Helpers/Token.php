<?php

namespace Forms\Helpers;

class Token
{
    public static function get(): string
    {
        $token = $_COOKIE['token'] ?? md5(random_bytes(16));
        setcookie('token', $token, ['expires' => strtotime('+1 hour'), 'httponly' => true, 'samesite' => 'Strict']);
        return $token;
    }

    public static function checkToken(): bool
    {
        return (isset($_POST['token']) && $_POST['token'] === self::get('token'));
    }
}
