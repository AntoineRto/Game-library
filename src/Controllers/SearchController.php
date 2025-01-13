<?php

namespace App\Controllers;

use PDO;

class SearchController {
    public function performSearch() {
        require_once '../config/db.php'; // Inclure la connexion à la base de données

        $query = htmlspecialchars($_GET['query'] ?? '');

        // Vérifier si le terme de recherche est vide
        if (empty($query)) {
            $_SESSION['error'] = "Veuillez entrer un terme de recherche.";
            header("Location: /game-library/public/");
            exit;
        }

        try {
            // Rechercher dans les jeux (par titre ou description)
            $stmt = $pdo->prepare("
                SELECT jeu.Id_jeu, jeu.title, jeu.description, img.url
                FROM jeu
                LEFT JOIN Asso_8 ON jeu.Id_jeu = Asso_8.Id_jeu
                LEFT JOIN img ON Asso_8.Id_img = img.Id_img
                WHERE jeu.title LIKE :query OR jeu.description LIKE :query
            ");
            $stmt->execute(['query' => '%' . $query . '%']);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Inclure la vue pour afficher les résultats
            include '../templates/searchResults.php';

        } catch (PDOException $e) {
            echo "Erreur lors de la recherche : " . $e->getMessage();
            exit;
        }
    }
}
