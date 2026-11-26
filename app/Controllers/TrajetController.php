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
 * Création, modification et suppression de trajets par les employés.
 */
class TrajetController extends Controller
{
    /**
     * Affiche le formulaire de création.
     */
    public function create(): string
    {
        $user = $this->requireLogin();

        return $this->renderForm($user, []);
    }

    /**
     * Enregistre un nouveau trajet.
     */
    public function store(): string
    {
        $this->verifyCsrf();
        $user   = $this->requireLogin();
        $result = $this->validateInput();

        if ($result['errors'] !== []) {
            return $this->renderForm($user, $_POST, $result['errors']);
        }

        try {
            (new TrajetModel())->create($result['data'], (int) $user['id']);
        } catch (PDOException $e) {
            error_log('Création de trajet impossible : ' . $e->getMessage());

            return $this->renderForm($user, $_POST, [
                'general' => 'Une erreur est survenue : le trajet n\'a pas été enregistré.',
            ]);
        }

        Session::flash('success', 'Votre trajet a bien été créé.');
        $this->redirect('/');
    }

    /**
     * Affiche le formulaire de modification d'un trajet.
     */
    public function edit(int $id): string
    {
        $user   = $this->requireLogin();
        $trajet = $this->findAuthorizedTrajet($id, $user, false);

        $old = $trajet;
        $old['date_depart']  = str_replace(' ', 'T', substr((string) $trajet['date_depart'], 0, 16));
        $old['date_arrivee'] = str_replace(' ', 'T', substr((string) $trajet['date_arrivee'], 0, 16));

        return $this->renderForm($user, $old, [], $id);
    }

    /**
     * Enregistre les modifications d'un trajet.
     */
    public function update(int $id): string
    {
        $this->verifyCsrf();
        $user   = $this->requireLogin();
        $trajet = $this->findAuthorizedTrajet($id, $user, false);

        $result = $this->validateInput();

        if ($result['errors'] !== []) {
            return $this->renderForm($user, $_POST, $result['errors'], $id);
        }

        try {
            (new TrajetModel())->update($id, (int) $trajet['id_employe'], $result['data']);
        } catch (PDOException $e) {
            error_log('Modification de trajet impossible : ' . $e->getMessage());

            return $this->renderForm($user, $_POST, [
                'general' => 'Une erreur est survenue : le trajet n\'a pas été modifié.',
            ], $id);
        }

        Session::flash('success', 'Votre trajet a bien été modifié.');
        $this->redirect('/');
    }

    /**
     * Supprime un trajet (son auteur ou un administrateur).
     */
    public function delete(int $id): void
    {
        $this->verifyCsrf();
        $user   = $this->requireLogin();
        $trajet = $this->findAuthorizedTrajet($id, $user, true);

        try {
            (new TrajetModel())->delete($id, (int) $trajet['id_employe']);
        } catch (PDOException $e) {
            error_log('Suppression de trajet impossible : ' . $e->getMessage());
            Session::flash('error', 'Une erreur est survenue : le trajet n\'a pas été supprimé.');
            $this->redirect('/');
        }

        Session::flash('success', 'Le trajet a bien été supprimé.');
        $this->redirect('/');
    }

    /**
     * Retourne le trajet demandé si l'utilisateur a le droit d'agir dessus,
     * sinon redirige avec un message.
     *
     * @param array<string, mixed> $user
     * @param bool                 $allowAdmin Un administrateur est-il autorisé ?
     *
     * @return array<string, mixed>
     */
    private function findAuthorizedTrajet(int $id, array $user, bool $allowAdmin): array
    {
        $trajet = (new TrajetModel())->findById($id);

        if ($trajet === null) {
            Session::flash('error', 'Ce trajet n\'existe pas.');
            $this->redirect('/');
        }

        $isAuthor = (int) $trajet['id_employe'] === (int) $user['id'];
        $isAdmin  = $allowAdmin && $user['role'] === 'admin';

        if (!$isAuthor && !$isAdmin) {
            Session::flash('error', 'Vous n\'avez pas le droit d\'agir sur ce trajet.');
            $this->redirect('/');
        }

        return $trajet;
    }

    /**
     * Valide les données du formulaire.
     *
     * @return array{errors: array<string, string>, data: array<string, mixed>}
     */
    private function validateInput(): array
    {
        $agences = (new AgenceModel())->findAll();
        $ids     = array_map(static fn (array $a): int => (int) $a['id_agence'], $agences);

        return (new TrajetValidator())->validate($_POST, $ids);
    }

    /**
     * Prépare et affiche le formulaire (création si $trajetId est null).
     *
     * @param array<string, mixed>  $user
     * @param array<string, mixed>  $old    Valeurs à afficher dans les champs.
     * @param array<string, string> $errors Messages d'erreur par champ.
     */
    private function renderForm(array $user, array $old, array $errors = [], ?int $trajetId = null): string
    {
        $employe = (new EmployeModel())->findById((int) $user['id']);

        if ($employe === null) {
            Session::logout();
            Session::flash('error', 'Votre compte est introuvable.');
            $this->redirect('/login');
        }

        $isEdit = $trajetId !== null;

        return $this->render('trajets/form', [
            'title'       => $isEdit ? 'Modifier un trajet' : 'Proposer un trajet',
            'heading'     => $isEdit ? 'Modifier mon trajet' : 'Proposer un trajet',
            'action'      => $isEdit ? "/trajets/{$trajetId}/modifier" : '/trajets',
            'submitLabel' => $isEdit ? 'Enregistrer les modifications' : 'Enregistrer le trajet',
            'employe'     => $employe,
            'agences'     => (new AgenceModel())->findAll(),
            'old'         => $old,
            'errors'      => $errors,
        ]);
    }
}