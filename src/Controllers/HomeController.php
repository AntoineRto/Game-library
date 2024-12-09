<?php
namespace App\Controllers;

class HomeController {
    public function index() {
        

        $role = $_SESSION['role'] ?? 1; // Guest par défaut

        if ($role === 1) {
            include '../templates/guest_home.php'; // Affiche directement la page des invités
        } elseif ($role === 2) {
            header('Location: /game-library/public/user_home'); // Redirection utilisateur
            exit;
        } elseif ($role === 3) {
            header('Location: /game-library/public/admin_home'); // Redirection admin
            exit;
        
        }
    }

//Méthode pour page guest / default page
    public function showGuestHome() {
        include '../templates/guest_home.php';
    }
//Méthode pour page user
    public function showUserHome() { 
        include '../templates/user_home.php';
    }

//Méthode pour page admin
    public function showAdminHome() { 
        include '../templates/admin_home.php';
    }
}
