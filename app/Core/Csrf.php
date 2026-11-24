<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Protection contre les requêtes forgées (CSRF).
 */
final class Csrf
{
    /**
     * Retourne le jeton de la session (créé s'il n'existe pas encore).
     */
    public static function token(): string
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    /**
     * Vérifie le jeton reçu avec celui de la session.
     */
    public static function isValid(mixed $token): bool
    {
        return is_string($token)
            && isset($_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], $token);
    }
}