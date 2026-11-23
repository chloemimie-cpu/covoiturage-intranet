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
        $content = $this->renderFile($view, $data);

        return $this->renderFile('layout', [
            'content' => $content,
            'title'   => $data['title'] ?? 'Covoiturage',
            'user'    => Session::user(),
            'flash'   => Session::pullFlash(),
        ]);
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

        extract($data);
        ob_start();
        require $file;

        return (string) ob_get_clean();
    }
}