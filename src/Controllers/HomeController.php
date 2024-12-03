<?php
namespace App\Controllers;

class HomeController {
    public function index() {
        $title = "Accueil";
        include '../templates/home.php'; // page d'accueil
    }
}
