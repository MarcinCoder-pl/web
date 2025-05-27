<?php
namespace Tools_Manager;

class AuthToken
{
    private const TOKEN_LIFETIME = 900; // 15 minut (w sekundach)

    // Generuje token CSRF dla danej akcji
    public static function generate(string $action): string
    {
        self::ensureSessionStarted();

        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_tokens'][$action] = [
            'token' => $token,
            'created_at' => time()
        ];

        return $token;
    }

    // Waliduje token CSRF (z uwzględnieniem czasu ważności)
    public static function validate(string $action, string $token): bool
    {
        self::ensureSessionStarted();

        if (empty($_SESSION['csrf_tokens'][$action])) {
            return false;
        }

        $stored = $_SESSION['csrf_tokens'][$action];
        $isValid = hash_equals($stored['token'], $token);

        $isNotExpired = (time() - $stored['created_at']) <= self::TOKEN_LIFETIME;

        // Usuwamy token niezależnie od tego, czy wygasł, czy nie
        unset($_SESSION['csrf_tokens'][$action]);

        return $isValid && $isNotExpired;
    }

    // Czyści token CSRF dla danej akcji
    public static function clear(string $action): void
    {
        self::ensureSessionStarted();
        unset($_SESSION['csrf_tokens'][$action]);
    }

    // Sprawdza istnienie i ważność tokenu (opcjonalnie do diagnostyki)
    public static function isTokenValid(string $action): bool
    {
        self::ensureSessionStarted();

        if (empty($_SESSION['csrf_tokens'][$action])) {
            return false;
        }

        return (time() - $_SESSION['csrf_tokens'][$action]['created_at']) <= self::TOKEN_LIFETIME;
    }

    // Pomocnicza metoda do uruchamiania sesji
    private static function ensureSessionStarted(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
