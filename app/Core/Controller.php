<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

/**
 * Classe mère des contrôleurs : affichage des vues et redirections.
 */
abstract class Controller
{
    /**
     * Affiche une vue à l'intérieur du gabarit commun (header + footer).
     *
     * @param string               $view Chemin de la vue sans extension (ex. "home/index").
     * @param array<string, mixed> $data Variables disponibles dans la vue.
     */
    protected function render(string $view, array $data = []): string
    {
        $user = Session::user();

        $content = $this->renderFile($view, $data + ['user' => $user]);

        return $this->renderFile('layout', [
            'content' => $content,
            'title'   => $data['title'] ?? 'Covoiturage',
            'user'    => $user,
            'flash'   => Session::pullFlash(),
        ]);
    }

    /**
     * Vérifie le jeton CSRF d'un formulaire, sinon refuse la requête.
     */
    protected function verifyCsrf(): void
    {
        if (!Csrf::isValid($_POST['csrf_token'] ?? null)) {
            Session::flash('error', 'Requête invalide ou session expirée. Veuillez réessayer.');
            $this->redirect('/');
        }
    }

    /**
     * Redirige vers une autre adresse et arrête le script.
     */
    protected function redirect(string $url): never
    {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Exige un utilisateur connecté, sinon redirige vers la connexion.
     *
     * @return array<string, mixed> L'utilisateur connecté.
     */
    protected function requireLogin(): array
    {
        $user = Session::user();

        if ($user === null) {
            Session::flash('error', 'Vous devez vous connecter pour accéder à cette page.');
            $this->redirect('/login');
        }

        return $user;
    }
    /**
     * Exige un administrateur connecté, sinon redirige avec un message.
     *
     * @return array<string, mixed> L'administrateur connecté.
     */
    protected function requireAdmin(): array
    {
        $user = $this->requireLogin();

        if ($user['role'] !== 'admin') {
            Session::flash('error', 'Accès réservé aux administrateurs.');
            $this->redirect('/');
        }

        return $user;
    }
    /**
     * Charge un fichier de vue et retourne son HTML.
     *
     * @param array<string, mixed> $data
     *
     * @throws RuntimeException Si la vue n'existe pas.
     */
    private function renderFile(string $view, array $data): string
    {
        $file = dirname(__DIR__) . '/Views/' . $view . '.php';

        if (!is_file($file)) {
            throw new RuntimeException("Vue introuvable : {$view}");
        }

        extract($data, EXTR_SKIP);
        ob_start();
        require $file;

        return (string) ob_get_clean();
    }
}