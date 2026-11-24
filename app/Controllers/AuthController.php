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
    private const MESSAGE_IDENTIFIANTS = 'Identifiants incorrects.';
    private const MESSAGE_SAISIE       = 'Saisissez une adresse email valide et un mot de passe.';

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
        $this->verifyCsrf();

        $email    = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        // 1. Validation des entrées
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false || $password === '') {
            return $this->loginError(self::MESSAGE_SAISIE, $email);
        }

        // 2. Vérification des identifiants (même message dans les deux cas)
        $employe = (new EmployeModel())->findByEmail($email);

        if ($employe === null || !password_verify($password, (string) $employe['mot_de_passe'])) {
            return $this->loginError(self::MESSAGE_IDENTIFIANTS, $email);
        }

        // 3. Connexion réussie
        Session::login($employe);
        Session::flash('success', 'Bienvenue ' . $employe['prenom'] . ' !');
        $this->redirect('/');
    }

    /**
     * Déconnecte l'utilisateur (requête POST avec jeton CSRF).
     */
    public function logout(): void
    {
        $this->verifyCsrf();

        Session::logout();
        Session::flash('success', 'Vous êtes déconnecté.');
        $this->redirect('/');
    }

    /**
     * Réaffiche le formulaire avec un message d'erreur.
     */
    private function loginError(string $message, string $email): string
    {
        return $this->render('auth/login', [
            'title' => 'Connexion',
            'error' => $message,
            'email' => $email,
        ]);
    }
}