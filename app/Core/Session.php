<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Gestion de la session : utilisateur connecté et messages flash.
 */
final class Session
{
    /**
     * Démarre la session de façon sécurisée (cookie inaccessible depuis JavaScript).
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
            session_start();
        }
    }

    /**
     * Enregistre l'employé connecté (on ne garde jamais le mot de passe).
     *
     * @param array<string, mixed> $employe Ligne de la table employes.
     */
    public static function login(array $employe): void
    {
        session_regenerate_id(true); // protège contre le vol de session
        $_SESSION['user'] = [
            'id'     => (int) $employe['id_employe'],
            'prenom' => (string) $employe['prenom'],
            'nom'    => (string) $employe['nom'],
            'role'   => (string) $employe['role'],
        ];
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public static function logout(): void
    {
        $_SESSION = [];
        session_regenerate_id(true);
    }

    /**
     * Retourne l'utilisateur connecté, ou null si personne n'est connecté.
     *
     * @return array<string, mixed>|null
     */
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Prépare un message à afficher une seule fois à la prochaine page.
     */
    public static function flash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    /**
     * Lit le message flash et l'efface.
     *
     * @return array<string, string>|null
     */
    public static function pullFlash(): ?array
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        return $flash;
    }
}