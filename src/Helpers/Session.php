<?php

namespace Forms\Helpers;

class Session
{
    public static function start(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start(['cookie_httponly' => true, 'cookie_samesite' => 'Strict']);
        }
    }

    public static function addInputValues(array $values): void
    {
        $_SESSION['values'] = $values;
    }

    public static function popInputValues(): ?array
    {
        $values = $_SESSION['values'] ?? null;
        unset($_SESSION['values']);
        
        return $values;
    }

    public static function addErrors(array $errors): void
    {
        $_SESSION['errors'] = $errors;
    }

    public static function popErrors(): ?array
    {
        $errors = $_SESSION['errors'] ?? null;
        unset($_SESSION['errors']);
        
        return $errors;
    }

    public static function addMessage(string $key, string $message): void
    {
        $_SESSION['messages'][$key] = $message;
    }

    public static function popMessage(string $key): ?string
    {
        $message = $_SESSION['messages'][$key] ?? null;
        unset($_SESSION['messages'][$key]);

        return $message;
    }

    public static function addLogin(string $login): void
    {
        $_SESSION['login'] = $login;
    }

    public static function getLogin(): ?string
    {
        return $_SESSION['login'] ?? null;
    }

    public static function destroy(): void
    {
        session_unset();
        session_destroy();
    }
}
