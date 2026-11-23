<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\EmployeModel;

/**
 * Connexion et déconnexion des employés.
 */
class AuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion.
     */
    public function showLogin(): string
    {
        if (Session::user() !== null) {
            $this->redirect('/');
        }

        return $this->render('auth/login', ['title' => 'Connexion']);
    }

    /**
     * Traite le formulaire de connexion.
     */
    public function login(): string
    {
        $email    = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        // 1. Validation des entrées
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false || $password === '') {
            return $this->render('auth/login', [
                'title' => 'Connexion',
                'error' => 'Saisissez une adresse email valide et un mot de passe.',
                'email' => $email,
            ]);
        }

        // 2. Vérification des identifiants (même message d'erreur dans les deux cas)
        $employe = (new EmployeModel())->findByEmail($email);

        if ($employe === null || !password_verify($password, (string) $employe['mot_de_passe'])) {
            return $this->render('auth/login', [
                'title' => 'Connexion',
                'error' => 'Identifiants incorrects.',
                'email' => $email,
            ]);
        }

        // 3. Connexion réussie
        Session::login($employe);
        Session::flash('success', 'Bienvenue ' . $employe['prenom'] . ' !');
        $this->redirect('/');
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public function logout(): void
    {
        Session::logout();
        Session::flash('success', 'Vous êtes déconnecté.');
        $this->redirect('/');
    }
}