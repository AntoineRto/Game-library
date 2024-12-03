<?php
namespace App\Controllers;

class LoginController {
    public function showLogin() {
        // Inclure le template de la page Login
        require_once __DIR__ . '/../../templates/login.php';
    }
}
