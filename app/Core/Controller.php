<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

/**
 * Classe mère des contrôleurs : fournit l'affichage des vues.
 */
abstract class Controller
{
    /**
     * Affiche une vue et retourne son HTML.
     *
     * @param string               $view Chemin de la vue sans extension (ex. "home/index").
     * @param array<string, mixed> $data Variables disponibles dans la vue.
     *
     * @throws RuntimeException Si la vue n'existe pas.
     */
    protected function render(string $view, array $data = []): string
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