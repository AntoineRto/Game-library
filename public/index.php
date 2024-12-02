<?php

// Inclure les fichiers nécessaires
require_once '../src/Router.php';
require_once '../src/Controllers/HomeController.php';

use App\Router;

// Créer une instance du routeur
$router = new Router();

// Ajouter une route pour l'accueil
$router->add('/', 'HomeController', 'index');

// Obtenir l'URL demandée et ajuster
$path = str_replace('/game-library/public', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Appeler la route correspondante
$router->dispatch($path);
