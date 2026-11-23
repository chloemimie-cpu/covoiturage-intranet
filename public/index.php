<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Buki\Router\Router;

$router = new Router([
    'paths' => [
        'controllers' => dirname(__DIR__) . '/app/Controllers',
    ],
    'namespaces' => [
        'controllers' => 'App\\Controllers\\',
    ],
    'debug' => true, // à passer à false pour le rendu final
]);

$router->get('/', 'HomeController@index');

$router->run();