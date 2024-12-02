<?php
namespace App\Controllers;

class HomeController {
    public function index() {
        $title = "Accueil";
        include '../templates/home.php'; // Inclure la page d'accueil
    }
}
