<?php
namespace App\Controllers;

class RegisterController {
    public function showRegister() {
        // Inclure le fichier de la vue pour le formulaire d'inscription
        require_once __DIR__ . '/../../templates/register.php';
    }
}
