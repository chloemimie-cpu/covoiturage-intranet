<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\AgenceModel;
use App\Models\EmployeModel;
use App\Models\TrajetModel;
use App\Validators\AgenceValidator;
use PDOException;

/**
 * Tableau de bord de l'administrateur.
 */
class AdminController extends Controller
{
    /**
     * Page d'accueil du tableau de bord.
     */
    public function index(): string
    {
        $this->requireAdmin();

        return $this->render('admin/index', ['title' => 'Tableau de bord']);
    }

    /**
     * Liste des utilisateurs.
     */
    public function users(): string
    {
        $this->requireAdmin();

        return $this->render('admin/users', [
            'title'    => 'Utilisateurs',
            'employes' => (new EmployeModel())->findAll(),
        ]);
    }

    /**
     * Liste des agences et formulaire d'ajout.
     */
    public function agences(): string
    {
        $this->requireAdmin();

        return $this->renderAgences();
    }

    /**
     * Crée une agence.
     */
    public function storeAgence(): string
    {
        $this->verifyCsrf();
        $this->requireAdmin();

        $nom   = trim((string) ($_POST['nom'] ?? ''));
        $error = (new AgenceValidator())->validate($nom);

        if ($error === null) {
            try {
                (new AgenceModel())->create($nom);
            } catch (PDOException $e) {
                return $this->renderAgences($this->agenceErrorMessage($e), $nom);
            }

            Session::flash('success', 'L\'agence a bien été créée.');
            $this->redirect('/admin/agences');
        }

        return $this->renderAgences($error, $nom);
    }

    /**
     * Affiche le formulaire de modification d'une agence.
     */
    public function editAgence(int $id): string
    {
        $this->requireAdmin();
        $agence = $this->findAgenceOrRedirect($id);

        return $this->render('admin/agence_edit', [
            'title'  => 'Modifier une agence',
            'agence' => $agence,
            'nom'    => (string) $agence['nom'],
            'error'  => null,
        ]);
    }

    /**
     * Enregistre la modification d'une agence.
     */
    public function updateAgence(int $id): string
    {
        $this->verifyCsrf();
        $this->requireAdmin();
        $agence = $this->findAgenceOrRedirect($id);

        $nom   = trim((string) ($_POST['nom'] ?? ''));
        $error = (new AgenceValidator())->validate($nom);

        if ($error === null) {
            try {
                (new AgenceModel())->update($id, $nom);
            } catch (PDOException $e) {
                $error = $this->agenceErrorMessage($e);
            }
        }

        if ($error !== null) {
            return $this->render('admin/agence_edit', [
                'title'  => 'Modifier une agence',
                'agence' => $agence,
                'nom'    => $nom,
                'error'  => $error,
            ]);
        }

        Session::flash('success', 'L\'agence a bien été modifiée.');
        $this->redirect('/admin/agences');
    }

    /**
     * Supprime une agence (impossible si des trajets l'utilisent).
     */
    public function deleteAgence(int $id): void
    {
        $this->verifyCsrf();
        $this->requireAdmin();
        $this->findAgenceOrRedirect($id);

        try {
            (new AgenceModel())->delete($id);
            Session::flash('success', 'L\'agence a bien été supprimée.');
        } catch (PDOException $e) {
            Session::flash('error', $this->agenceErrorMessage($e));
        }

        $this->redirect('/admin/agences');
    }

    /**
     * Liste de tous les trajets.
     */
    public function trajets(): string
    {
        $this->requireAdmin();

        return $this->render('admin/trajets', [
            'title'   => 'Trajets',
            'trajets' => (new TrajetModel())->findAllWithDetails(),
        ]);
    }

    /**
     * Supprime un trajet.
     */
    public function deleteTrajet(int $id): void
    {
        $this->verifyCsrf();
        $this->requireAdmin();

        $model = new TrajetModel();

        if ($model->findById($id) === null) {
            Session::flash('error', 'Ce trajet n\'existe pas.');
            $this->redirect('/admin/trajets');
        }

        try {
            (new TrajetModel())->deleteByAdmin($id);
            Session::flash('success', 'Le trajet a bien été supprimé.');
        } catch (PDOException $e) {
            error_log('Suppression de trajet impossible : ' . $e->getMessage());
            Session::flash('error', 'Une erreur est survenue : le trajet n\'a pas été supprimé.');
        }

        $this->redirect('/admin/trajets');
    }

    /**
     * Affiche la liste des agences avec son formulaire d'ajout.
     */
    private function renderAgences(?string $error = null, string $nom = ''): string
    {
        return $this->render('admin/agences', [
            'title'   => 'Agences',
            'agences' => (new AgenceModel())->findAll(),
            'error'   => $error,
            'nom'     => $nom,
        ]);
    }

    /**
     * Retourne l'agence demandée, ou redirige si elle n'existe pas.
     *
     * @return array<string, mixed>
     */
    private function findAgenceOrRedirect(int $id): array
    {
        $agence = (new AgenceModel())->findById($id);

        if ($agence === null) {
            Session::flash('error', 'Cette agence n\'existe pas.');
            $this->redirect('/admin/agences');
        }

        return $agence;
    }

    /**
     * Traduit une erreur SQL en message compréhensible.
     * 1062 = valeur déjà existante, 1451 = ligne encore utilisée ailleurs.
     */
    private function agenceErrorMessage(PDOException $e): string
    {
        $code = (int) ($e->errorInfo[1] ?? 0);

        if ($code === 1062) {
            return 'Une agence porte déjà ce nom.';
        }
        if ($code === 1451) {
            return 'Cette agence est utilisée par des trajets : elle ne peut pas être supprimée.';
        }

        error_log('Erreur agence : ' . $e->getMessage());

        return 'Une erreur est survenue : l\'opération n\'a pas pu être effectuée.';
    }
}