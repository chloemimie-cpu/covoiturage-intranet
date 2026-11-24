<?php

declare(strict_types=1);

namespace App\Validators;

use DateTimeImmutable;

/**
 * Contrôles de cohérence d'un trajet saisi dans un formulaire.
 */
final class TrajetValidator
{
    private const MAX_PLACES = 9;

    /**
     * Valide les données d'un trajet.
     *
     * @param array<string, mixed> $input      Données brutes du formulaire.
     * @param array<int, int>      $agenceIds  Identifiants d'agences existants.
     * @param DateTimeImmutable|null $now      Date du jour (modifiable pour les tests).
     *
     * @return array{errors: array<string, string>, data: array<string, mixed>}
     *         Les erreurs (champ => message) et les données nettoyées.
     */
    public function validate(array $input, array $agenceIds, ?DateTimeImmutable $now = null): array
    {
        $errors = [];
        $now ??= new DateTimeImmutable();

        // Agences
        $depart  = filter_var($input['id_agence_depart'] ?? null, FILTER_VALIDATE_INT);
        $arrivee = filter_var($input['id_agence_arrivee'] ?? null, FILTER_VALIDATE_INT);

        if ($depart === false || !in_array($depart, $agenceIds, true)) {
            $errors['id_agence_depart'] = 'Choisissez une agence de départ valide.';
        }
        if ($arrivee === false || !in_array($arrivee, $agenceIds, true)) {
            $errors['id_agence_arrivee'] = 'Choisissez une agence d\'arrivée valide.';
        }
        if ($errors === [] && $depart === $arrivee) {
            $errors['id_agence_arrivee'] = 'L\'agence d\'arrivée doit être différente de l\'agence de départ.';
        }

        // Dates
        $dateDepart  = $this->parseDate($input['date_depart'] ?? null);
        $dateArrivee = $this->parseDate($input['date_arrivee'] ?? null);

        if ($dateDepart === null) {
            $errors['date_depart'] = 'Saisissez une date et une heure de départ valides.';
        } elseif ($dateDepart <= $now) {
            $errors['date_depart'] = 'Le départ doit être dans le futur.';
        }

        if ($dateArrivee === null) {
            $errors['date_arrivee'] = 'Saisissez une date et une heure d\'arrivée valides.';
        } elseif ($dateDepart !== null && $dateArrivee <= $dateDepart) {
            $errors['date_arrivee'] = 'L\'arrivée doit être postérieure au départ.';
        }

        // Places
        $total = filter_var($input['places_total'] ?? null, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => self::MAX_PLACES],
        ]);
        $dispo = filter_var($input['places_disponibles'] ?? null, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0, 'max_range' => self::MAX_PLACES],
        ]);

        if ($total === false) {
            $errors['places_total'] = 'Le nombre total de places doit être compris entre 1 et ' . self::MAX_PLACES . '.';
        }
        if ($dispo === false) {
            $errors['places_disponibles'] = 'Le nombre de places disponibles doit être compris entre 0 et ' . self::MAX_PLACES . '.';
        } elseif ($total !== false && $dispo > $total) {
            $errors['places_disponibles'] = 'Il ne peut pas y avoir plus de places disponibles que de places au total.';
        }

        $data = [];
        if ($errors === [] && $dateDepart !== null && $dateArrivee !== null) {
            $data = [
                'id_agence_depart'   => $depart,
                'id_agence_arrivee'  => $arrivee,
                'date_depart'        => $dateDepart->format('Y-m-d H:i:s'),
                'date_arrivee'       => $dateArrivee->format('Y-m-d H:i:s'),
                'places_total'       => $total,
                'places_disponibles' => $dispo,
            ];
        }

        return ['errors' => $errors, 'data' => $data];
    }

    /**
     * Convertit une saisie "2026-12-02T08:00" en date, ou null si elle est invalide.
     */
    private function parseDate(mixed $value): ?DateTimeImmutable
    {
        if (!is_string($value)) {
            return null;
        }

        $date   = DateTimeImmutable::createFromFormat('Y-m-d\TH:i', $value);
        $issues = DateTimeImmutable::getLastErrors();

        if ($date === false || ($issues !== false && ($issues['warning_count'] > 0 || $issues['error_count'] > 0))) {
            return null;
        }

        return $date;
    }
}