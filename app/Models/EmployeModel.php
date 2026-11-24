<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

/**
 * Accès aux données des employés (lecture seule : ils viennent du système RH).
 *
 * Attention : findAll() liste les coordonnées de tous les employés,
 * à n'appeler que depuis une action protégée par requireAdmin().
 */
class EmployeModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Cherche un employé par son adresse email (pour la connexion).
     *
     * @return array<string, mixed>|null Null si aucun employé ne correspond.
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id_employe, nom, prenom, telephone, email, mot_de_passe, role
             FROM employes
             WHERE email = :email'
        );
        $stmt->execute(['email' => $email]);
        $employe = $stmt->fetch();

        return $employe === false ? null : $employe;
    }

    /**
     * Cherche un employé par son identifiant (sans le mot de passe).
     *
     * @return array<string, mixed>|null
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id_employe, nom, prenom, telephone, email, role
             FROM employes
             WHERE id_employe = :id'
        );
        $stmt->execute(['id' => $id]);
        $employe = $stmt->fetch();

        return $employe === false ? null : $employe;
    }

    /**
     * Retourne tous les employés, triés par nom (sans le mot de passe).
     *
     * @return array<int, array<string, mixed>>
     */
    public function findAll(): array
    {
        return $this->pdo
            ->query('SELECT id_employe, nom, prenom, telephone, email, role FROM employes ORDER BY nom, prenom')
            ->fetchAll();
    }
}