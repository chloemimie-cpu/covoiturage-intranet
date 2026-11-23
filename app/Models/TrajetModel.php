<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

/**
 * Accès aux données des trajets.
 */
class TrajetModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Retourne les trajets à venir qui ont encore des places,
     * triés par date de départ croissante.
     *
     * @return array<int, array<string, mixed>>
     */
    public function findAvailable(): array
    {
        $sql = 'SELECT t.id_trajet, t.date_depart, t.date_arrivee, t.places_disponibles,
                       d.nom AS agence_depart, a.nom AS agence_arrivee
                FROM trajets t
                JOIN agences d ON d.id_agence = t.id_agence_depart
                JOIN agences a ON a.id_agence = t.id_agence_arrivee
                WHERE t.places_disponibles > 0
                  AND t.date_depart > NOW()
                ORDER BY t.date_depart ASC';

        return $this->pdo->query($sql)->fetchAll();
    }
}