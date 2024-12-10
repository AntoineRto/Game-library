<?php
namespace App\Controllers;

class LogoutController {
    public function logout() {
        // Démarrer la session (si nécessaire)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Détruire la session
        session_unset();
        session_destroy();

        // Rediriger vers la page d'accueil des invités
        header("Location: /game-library/public/");
        exit;
    }
}
