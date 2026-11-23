<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

/**
 * Lecture de la configuration de l'application depuis le fichier .env.
 *
 * Classe générique : elle pourra être réutilisée telle quelle
 * par les futurs sites de l'intranet.
 */
final class Config
{
    /** @var array<string, string>|null */
    private static ?array $values = null;

    /**
     * Retourne la valeur d'une clé du fichier .env.
     *
     * @param string $key Nom de la clé (ex. "DB_HOST").
     *
     * @throws RuntimeException Si le fichier .env est illisible ou la clé absente.
     */
    public static function get(string $key): string
    {
        if (self::$values === null) {
            $path = dirname(__DIR__, 2) . '/.env';
            $parsed = is_readable($path)
                ? parse_ini_file($path, false, INI_SCANNER_RAW)
                : false;

            if ($parsed === false) {
                throw new RuntimeException('Fichier de configuration .env introuvable ou illisible.');
            }

            self::$values = array_map('strval', $parsed);
        }

        if (!array_key_exists($key, self::$values)) {
            throw new RuntimeException("Clé de configuration manquante : {$key}");
        }

        return self::$values[$key];
    }
}