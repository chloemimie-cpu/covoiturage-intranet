<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\TrajetModel;

/**
 * Contrôleur de la page d'accueil.
 */
class HomeController extends Controller
{
    /**
     * Affiche la liste des trajets disponibles.
     */
    public function index(): string
    {
        $trajets = (new TrajetModel())->findAvailable();

        return $this->render('home/index', ['trajets' => $trajets]);
    }
}