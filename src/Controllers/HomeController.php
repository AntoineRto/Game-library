<?php
namespace App\Controllers;

class HomeController {
    public function index() {
        $role = $_SESSION['role'] ?? 1; // 1 par défaut pour les invités
        include '../templates/home.php';
    }
}
