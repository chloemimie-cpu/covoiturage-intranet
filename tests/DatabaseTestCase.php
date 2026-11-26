<?php

declare(strict_types=1);

namespace Tests;

use App\Core\Config;
use App\Core\Database;
use PDO;
use PHPUnit\Framework\TestCase;

/**
 * Classe mère des tests qui utilisent la base de données.
 * Elle vide la base de test avant chaque test et fournit des outils de préparation.
 */
abstract class DatabaseTestCase extends TestCase
{
    protected PDO $pdo;

    protected function setUp(): void
    {
        // Garde-fou : ne JAMAIS vider une autre base que celle des tests
        if (Config::get('DB_NAME') !== 'covoiturage_test') {
            self::fail('Sécurité : les tests ne doivent tourner que sur la base covoiturage_test.');
        }

        $this->pdo = Database::getConnection();

        // Ordre important : les trajets d'abord (ils dépendent des deux autres tables)
        $this->pdo->exec('DELETE FROM trajets');
        $this->pdo->exec('DELETE FROM employes');
        $this->pdo->exec('DELETE FROM agences');
    }

    /**
     * Insère une agence et retourne son identifiant.
     */
    protected function insertAgence(string $nom): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO agences (nom) VALUES (:nom)');
        $stmt->execute(['nom' => $nom]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Insère un employé et retourne son identifiant.
     */
    protected function insertEmploye(string $email = 'test@email.fr'): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO employes (nom, prenom, telephone, email, mot_de_passe, role)
             VALUES (\'Test\', \'Alice\', \'0600000000\', :email, \'hash\', \'employe\')'
        );
        $stmt->execute(['email' => $email]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Données valides d'un trajet entre deux agences.
     *
     * @return array<string, mixed>
     */
    protected function tripData(int $depart, int $arrivee): array
    {
        return [
            'date_depart'        => '2026-12-02 08:00:00',
            'date_arrivee'       => '2026-12-02 12:00:00',
            'places_total'       => 4,
            'places_disponibles' => 3,
            'id_agence_depart'   => $depart,
            'id_agence_arrivee'  => $arrivee,
        ];
    }
}