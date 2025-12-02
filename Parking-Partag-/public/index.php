<?php

require __DIR__ . '/../vendor/autoload.php';

use Interface\Http\Router;
use Interface\Http\Controller\Web\AuthController;
use Interface\Http\Controller\Api\AuthApiController;

// -----------------------------------------------
// Initialisation du routeur
// -----------------------------------------------
$router = new Router();

// -----------------------------------------------
// Routes WEB (pages HTML)
// -----------------------------------------------
$router->get('/login', [AuthController::class, 'showLoginForm']);
$router->get('/register', [AuthController::class, 'showRegisterForm']);

// -----------------------------------------------
// Routes API (actions POST)
// -----------------------------------------------
// $router->post('/login', [AuthApiController::class, 'login']);
// $router->post('/register', [AuthApiController::class, 'register']);

// -----------------------------------------------
// Détection automatique du base path
// -----------------------------------------------
$scriptName = dirname($_SERVER['SCRIPT_NAME']);         // ex: /php/Parking-Partage/public
$uri        = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($scriptName !== '/' && $scriptName !== '\\' && $scriptName !== '') {
    // stripos = recherche insensible à la casse
    if (stripos($uri, $scriptName) === 0) {
        $uri = substr($uri, strlen($scriptName));
    }
}

// Si chaîne vide → "/"
if ($uri === '' || $uri === false) {
    $uri = '/';
}

// -----------------------------------------------
// Dispatch (exécution de la bonne route)
// -----------------------------------------------
$router->dispatch($uri, $_SERVER['REQUEST_METHOD']);