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

    /**
     * Cherche une agence par son identifiant.
     *
     * @return array<string, mixed>|null Null si elle n'existe pas.
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id_agence, nom FROM agences WHERE id_agence = :id');
        $stmt->execute(['id' => $id]);
        $agence = $stmt->fetch();

        return $agence === false ? null : $agence;
    }

    /**
     * Crée une agence.
     *
     * @return int Identifiant de l'agence créée.
     */
    public function create(string $nom): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO agences (nom) VALUES (:nom)');
        $stmt->execute(['nom' => $nom]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Renomme une agence.
     *
     * @return bool True si une ligne a été modifiée.
     */
    public function update(int $id, string $nom): bool
    {
        $stmt = $this->pdo->prepare('UPDATE agences SET nom = :nom WHERE id_agence = :id');
        $stmt->execute(['nom' => $nom, 'id' => $id]);

        return $stmt->rowCount() > 0;
    }

        /**
     * Supprime une agence.
     *
     * @return bool True si une agence a été supprimée.
     *
     * @throws \PDOException Si l'agence est utilisée par des trajets (erreur MySQL 1451).
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM agences WHERE id_agence = :id');
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }
}