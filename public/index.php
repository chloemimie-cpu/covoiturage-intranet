<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Session;
use Buki\Router\Router;

Session::start();

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
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->post('/logout', 'AuthController@logout');

$router->run();