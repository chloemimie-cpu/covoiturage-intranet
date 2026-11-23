<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

/**
 * Fournit une connexion PDO unique à la base de données.
 */
final class Database
{
    private static ?PDO $connection = null;

    /**
     * Retourne la connexion PDO (créée à la première demande).
     *
     * @throws RuntimeException Si la connexion échoue.
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                Config::get('DB_HOST'),
                Config::get('DB_PORT'),
                Config::get('DB_NAME')
            );

            try {
                self::$connection = new PDO($dsn, Config::get('DB_USER'), Config::get('DB_PASS'), [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                // On journalise le détail technique, mais on ne l'affiche jamais à l'utilisateur
                error_log('Connexion BDD impossible : ' . $e->getMessage());
                throw new RuntimeException('Connexion à la base de données impossible.');
            }
        }

        return self::$connection;
    }
}