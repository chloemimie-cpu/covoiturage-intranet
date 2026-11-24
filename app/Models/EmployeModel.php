<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

/**
 * Accès aux données des employés (lecture seule : ils viennent du système RH).
 */
class EmployeModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Cherche un employé par son adresse email.
     *
     * @return array<string, mixed>|null Null si aucun employé ne correspond.
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id_employe, nom, prenom, email, mot_de_passe, role
             FROM employes
             WHERE email = :email'
        );
        $stmt->execute(['email' => $email]);
        $employe = $stmt->fetch();

        return $employe === false ? null : $employe;
    }

    /**
     * Cherche un employé par son identifiant.
     *
     * @return array<string, mixed>|null
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id_employe, nom, prenom, telephone, email
             FROM employes
             WHERE id_employe = :id'
        );
        $stmt->execute(['id' => $id]);
        $employe = $stmt->fetch();

        return $employe === false ? null : $employe;
    }
}