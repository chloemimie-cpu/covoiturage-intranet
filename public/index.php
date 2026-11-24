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
$router->get('/trajets/nouveau', 'TrajetController@create');
$router->post('/trajets', 'TrajetController@store');
$router->get('/trajets/:id/modifier', 'TrajetController@edit');
$router->post('/trajets/:id/modifier', 'TrajetController@update');
$router->post('/trajets/:id/supprimer', 'TrajetController@delete');
$router->get('/admin', 'AdminController@index');
$router->get('/admin/utilisateurs', 'AdminController@users');
$router->get('/admin/agences', 'AdminController@agences');
$router->post('/admin/agences', 'AdminController@storeAgence');
$router->get('/admin/agences/:id/modifier', 'AdminController@editAgence');
$router->post('/admin/agences/:id/modifier', 'AdminController@updateAgence');
$router->post('/admin/agences/:id/supprimer', 'AdminController@deleteAgence');
$router->get('/admin/trajets', 'AdminController@trajets');
$router->post('/admin/trajets/:id/supprimer', 'AdminController@deleteTrajet');

$router->run();