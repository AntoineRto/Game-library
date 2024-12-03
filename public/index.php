<?php

// Inclure les fichiers nécessaires
require_once '../src/Router.php';
require_once '../src/Controllers/HomeController.php';
require_once '../src/Controllers/LoginController.php';
require_once '../src/Controllers/RegisterController.php';

use App\Router;

// Créer une instance du routeur
$router = new Router();

// Route pour l'accueil = / default
$router->add('/', 'HomeController', 'index');

//Route pour login
$router->add('/login', 'LoginController', 'showLogin');

//Route pour register
$router->add('/register', 'RegisterController', 'showRegister');


// Obtenir l'URL demandée et ajuster
$path = str_replace('/game-library/public', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Appeler la route correspondante
$router->dispatch($path);
