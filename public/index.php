<?php

session_start(); // La session est démarrée une seule fois ici

// Inclure les fichiers nécessaires
require_once '../src/Router.php';
require_once '../src/Controllers/HomeController.php';
require_once '../src/Controllers/LoginController.php';
require_once '../src/Controllers/RegisterController.php';
require_once '../src/Controllers/LogoutController.php';
require_once '../src/Controllers/GameController.php';
require_once '../src/Controllers/ProfileController.php';
require_once '../src/Controllers/CollectionController.php';


use App\Router;

// Créer une instance du routeur
$router = new Router();

// Route pour l'accueil par défaut 
$router->add('/', 'HomeController', 'index');

//Route pour le profil (uniquement pour les utilisateurs connectés +) 
$router->add('/profile', 'ProfileController', 'showProfile');

//Route pour éditer le profil (uniquement pour les utilisateurs connectés +)
$router->add('/edit-profile', 'ProfileController', 'editProfile');

// Route pour login
$router->add('/login', 'LoginController', 'showLogin');

// Route pour register
$router->add('/register', 'RegisterController', 'showRegister');

//Route pour déconnecter
$router->add('/logout', 'LogOutController', 'logOut');

//Route pour infos jeux
$router->add('/game-details', 'GameController', 'showDetails');

//Route pour ajouter un jeu seulement disponible en admin
$router->add('/add-game', 'GameController', 'showAddGameForm');
$router->add('/add-game-submit', 'GameController', 'submitAddGame');

//Routes relatives à la collection
$router->add('/collection', 'CollectionController', 'showCollection');
$router->add('/update-status', 'CollectionController', 'updateGameStatus');
$router->add('/remove-game', 'CollectionController', 'removeGameFromCollection');



// Obtenir l'URL demandée et ajuster
$path = str_replace('/game-library/public', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Appeler la route correspondante
$router->dispatch($path);
