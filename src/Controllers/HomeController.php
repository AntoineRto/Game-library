<?php
namespace App\Controllers;

class HomeController {
    public function index() {
        $title = "Accueil";
        require_once __DIR__ . '/../../templates/home.php'; // page d'accueil
    }
}
