<?php
namespace App\Controllers;

class GameController {
    public function showDetails() {
        // Inclure le fichier gameDetails.php
        include '../templates/gameDetails.php';
    }
}
