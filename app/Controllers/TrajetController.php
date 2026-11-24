<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\AgenceModel;
use App\Models\EmployeModel;
use App\Models\TrajetModel;
use App\Validators\TrajetValidator;
use PDOException;

/**
 * Création de trajets par les employés.
 */
class TrajetController extends Controller
{
    /**
     * Affiche le formulaire de création d'un trajet.
     */
    public function create(): string
    {
        $user = $this->requireLogin();

        return $this->renderForm((int) $user['id'], []);
    }

    /**
     * Traite le formulaire de création d'un trajet.
     */
    public function store(): string
    {
        $user    = $this->requireLogin();
        $agences = (new AgenceModel())->findAll();
        $ids     = array_map(static fn (array $a): int => (int) $a['id_agence'], $agences);

        $result = (new TrajetValidator())->validate($_POST, $ids);

        if ($result['errors'] !== []) {
            return $this->renderForm((int) $user['id'], $_POST, $result['errors']);
        }

        try {
            (new TrajetModel())->create($result['data'], (int) $user['id']);
        } catch (PDOException $e) {
            // Détail technique dans le journal, message simple pour l'utilisateur
            error_log('Création de trajet impossible : ' . $e->getMessage());

            return $this->renderForm(
                (int) $user['id'],
                $_POST,
                ['general' => 'Une erreur est survenue : le trajet n\'a pas été enregistré.']
            );
        }

        Session::flash('success', 'Votre trajet a bien été créé.');
        $this->redirect('/');
    }

    /**
     * Prépare et affiche le formulaire.
     *
     * @param array<string, mixed>  $old    Valeurs déjà saisies.
     * @param array<string, string> $errors Messages d'erreur par champ.
     */
    private function renderForm(int $idEmploye, array $old, array $errors = []): string
    {
        $employe = (new EmployeModel())->findById($idEmploye);

        if ($employe === null) {
            Session::logout();
            Session::flash('error', 'Votre compte est introuvable.');
            $this->redirect('/login');
        }

        return $this->render('trajets/form', [
            'title'   => 'Proposer un trajet',
            'employe' => $employe,
            'agences' => (new AgenceModel())->findAll(),
            'old'     => $old,
            'errors'  => $errors,
        ]);
    }
}