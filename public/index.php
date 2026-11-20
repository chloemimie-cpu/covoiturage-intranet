<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Buki\Router\Router;

$router = new Router();

$router->get('/', function () {
    return 'Bienvenue sur l\'application de covoiturage !';
});

$router->run();