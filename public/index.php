<?php

session_start(); // La session est démarrée une seule fois ici

// Inclure les fichiers nécessaires
require_once '../src/Router.php';
require_once '../src/Controllers/HomeController.php';
require_once '../src/Controllers/LoginController.php';
require_once '../src/Controllers/RegisterController.php';
require_once '../src/Controllers/LogoutController.php';


use App\Router;

// Créer une instance du routeur
$router = new Router();

// Route pour l'accueil par défaut (redirection selon le rôle)
$router->add('/', 'HomeController', 'index');

// Route pour user (niveau 2)
$router->add('/user_home', 'HomeController', 'showUserHome');

// Route pour admin (niveau 3)
$router->add('/admin_home', 'HomeController', 'showAdminHome');

// Route pour login
$router->add('/login', 'LoginController', 'showLogin');

// Route pour register
$router->add('/register', 'RegisterController', 'showRegister');

//Route pour déconnecter
$router->add('/logout', 'LogOutController', 'logOut');


// Obtenir l'URL demandée et ajuster
$path = str_replace('/game-library/public', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Appeler la route correspondante
$router->dispatch($path);
