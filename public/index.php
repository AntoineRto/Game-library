<?php

session_start(); // La session est démarrée une seule fois ici

// Inclure les fichiers nécessaires
require_once '../src/Router.php';
require_once '../src/Controllers/HomeController.php';
require_once '../src/Controllers/SearchController.php';
require_once '../src/Controllers/LoginController.php';
require_once '../src/Controllers/RegisterController.php';
require_once '../src/Controllers/LogoutController.php';
require_once '../src/Controllers/GameController.php';
require_once '../src/Controllers/ProfileController.php';
require_once '../src/Controllers/CollectionController.php';
require_once '../src/Controllers/ReviewController.php';



use App\Router;

// Créer une instance du routeur
$router = new Router();

// Route pour l'accueil par défaut 
$router->add('/', 'HomeController', 'index');

//Route pour recherche
$router->add('/search', 'SearchController', 'performSearch');

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

//Routes relatives aux jeux seulement disponible en admin
$router->add('/add-game', 'GameController', 'showAddGameForm');
$router->add('/add-game-submit', 'GameController', 'submitAddGame');
$router->add('/edit-game', 'GameController', 'showEditGameForm');
$router->add('/edit-game-submit', 'GameController', 'submitEditGame');
$router->add('/delete-game', 'GameController', 'deleteGame');


//Routes relatives à la collection
$router->add('/collection', 'CollectionController', 'showCollection');
$router->add('/add-to-collection', 'CollectionController', 'addToCollection');
$router->add('/update-status', 'CollectionController', 'updateStatus');
$router->add('/delete-from-collection', 'CollectionController', 'deleteFromCollection');

//Route pour review
$router->add('/add-review', 'ReviewController', 'addReview');


// Obtenir l'URL demandée et ajuster
$path = str_replace('/game-library/public', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Appeler la route correspondante
$router->dispatch($path);
