<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Database;
use Buki\Router\Router;

$router = new Router();

$router->get('/', function () {
    return 'Bienvenue sur l\'application de covoiturage !';
});

$router->get('/bonjour', function () {
    return 'Bonjour !';
});

$router->get('/test-db', function () {
    $pdo = Database::getConnection();
    $nb = $pdo->query('SELECT COUNT(*) FROM employes')->fetchColumn();

    return "Connexion OK : {$nb} employés dans la base.";
});

$router->run();