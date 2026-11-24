<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

/**
 * Accès aux données des agences (villes).
 */
class AgenceModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Retourne toutes les agences, triées par nom.
     *
     * @return array<int, array<string, mixed>>
     */
    public function findAll(): array
    {
        return $this->pdo
            ->query('SELECT id_agence, nom FROM agences ORDER BY nom')
            ->fetchAll();
    }
}