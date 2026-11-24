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
     * triés par date de départ croissante, avec les coordonnées de l'auteur.
     *
     * @return array<int, array<string, mixed>>
     */
    public function findAvailable(): array
    {
        $sql = 'SELECT t.id_trajet, t.id_employe, t.date_depart, t.date_arrivee,
                       t.places_total, t.places_disponibles,
                       d.nom AS agence_depart, a.nom AS agence_arrivee,
                       e.nom, e.prenom, e.telephone, e.email
                FROM trajets t
                JOIN agences d ON d.id_agence = t.id_agence_depart
                JOIN agences a ON a.id_agence = t.id_agence_arrivee
                JOIN employes e ON e.id_employe = t.id_employe
                WHERE t.places_disponibles > 0
                  AND t.date_depart > NOW()
                ORDER BY t.date_depart ASC';

        return $this->pdo->query($sql)->fetchAll();
    }

    /**
     * Cherche un trajet par son identifiant.
     *
     * @return array<string, mixed>|null
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id_trajet, date_depart, date_arrivee, places_total, places_disponibles,
                    id_employe, id_agence_depart, id_agence_arrivee
             FROM trajets
             WHERE id_trajet = :id'
        );
        $stmt->execute(['id' => $id]);
        $trajet = $stmt->fetch();

        return $trajet === false ? null : $trajet;
    }

    /**
     * Enregistre un nouveau trajet.
     *
     * @param array<string, mixed> $data      Données déjà validées.
     * @param int                  $idEmploye Employé qui propose le trajet.
     *
     * @return int Identifiant du trajet créé.
     */
    public function create(array $data, int $idEmploye): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO trajets
                (date_depart, date_arrivee, places_total, places_disponibles,
                 id_employe, id_agence_depart, id_agence_arrivee)
             VALUES
                (:date_depart, :date_arrivee, :places_total, :places_disponibles,
                 :id_employe, :id_agence_depart, :id_agence_arrivee)'
        );
        $stmt->execute([
            'date_depart'        => $data['date_depart'],
            'date_arrivee'       => $data['date_arrivee'],
            'places_total'       => $data['places_total'],
            'places_disponibles' => $data['places_disponibles'],
            'id_employe'         => $idEmploye,
            'id_agence_depart'   => $data['id_agence_depart'],
            'id_agence_arrivee'  => $data['id_agence_arrivee'],
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Modifie un trajet, uniquement s'il appartient à l'employé.
     *
     * @param array<string, mixed> $data Données déjà validées.
     *
     * @return bool True si le trajet existe et appartient à l'employé.
     */
    public function update(int $id, int $idEmploye, array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE trajets
             SET date_depart = :date_depart,
                 date_arrivee = :date_arrivee,
                 places_total = :places_total,
                 places_disponibles = :places_disponibles,
                 id_agence_depart = :id_agence_depart,
                 id_agence_arrivee = :id_agence_arrivee
             WHERE id_trajet = :id AND id_employe = :id_employe'
        );
        $stmt->execute([
            'date_depart'        => $data['date_depart'],
            'date_arrivee'       => $data['date_arrivee'],
            'places_total'       => $data['places_total'],
            'places_disponibles' => $data['places_disponibles'],
            'id_agence_depart'   => $data['id_agence_depart'],
            'id_agence_arrivee'  => $data['id_agence_arrivee'],
            'id'                 => $id,
            'id_employe'         => $idEmploye,
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Supprime un trajet, uniquement s'il appartient à l'employé.
     *
     * @return bool True si un trajet a été supprimé.
     */
    public function delete(int $id, int $idEmploye): bool
    {
        $stmt = $this->pdo->prepare(
            'DELETE FROM trajets WHERE id_trajet = :id AND id_employe = :id_employe'
        );
        $stmt->execute(['id' => $id, 'id_employe' => $idEmploye]);

        return $stmt->rowCount() > 0;
    }
        /**
     * Retourne tous les trajets avec leurs agences et leur auteur (vue administrateur).
     *
     * @return array<int, array<string, mixed>>
     */
    public function findAllWithDetails(): array
    {
        $sql = 'SELECT t.id_trajet, t.date_depart, t.date_arrivee,
                       t.places_total, t.places_disponibles,
                       d.nom AS agence_depart, a.nom AS agence_arrivee,
                       e.nom, e.prenom, e.telephone, e.email
                FROM trajets t
                JOIN agences d ON d.id_agence = t.id_agence_depart
                JOIN agences a ON a.id_agence = t.id_agence_arrivee
                JOIN employes e ON e.id_employe = t.id_employe
                ORDER BY t.date_depart ASC';

        return $this->pdo->query($sql)->fetchAll();
    }
}