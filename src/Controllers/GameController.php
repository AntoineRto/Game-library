<?php
namespace App\Controllers;

class GameController {
    public function showDetails() {
        // Inclure le fichier gameDetails.php
        include '../templates/gameDetails.php';
    }
    
    public function showAddGameForm() {;
        if (!isset($_SESSION['role']) || $_SESSION['role'] < 3) { // Rôle 3 pour admin
            header("Location: /game-library/public/home.php");
            exit;
        }
    
        include '../templates/addGame.php';
    }
    
    public function submitAddGame() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] < 3) {
            header("Location: /game-library/public/home.php");
            exit;
        }
    
        require_once '../config/db.php';
    
        try {
            $title = htmlspecialchars($_POST['title']);
            $release_date = $_POST['release_date'];
            $description = htmlspecialchars($_POST['description']);
            $genre = $_POST['genre']; // Array of genre IDs
            $platform = $_POST['platform']; // Array of platform IDs
            $tags = $_POST['tags']; // Array of tag IDs
    
            // Ajouter le jeu
            $stmt = $pdo->prepare("INSERT INTO jeu (title, release_date, description) VALUES (:title, :release_date, :description)");
            $stmt->execute([
                'title' => $title,
                'release_date' => $release_date,
                'description' => $description,
            ]);
    
            $game_id = $pdo->lastInsertId();
    
            // Ajouter les genres associés
            foreach ($genre as $genre_id) {
                $stmt = $pdo->prepare("INSERT INTO Asso_3 (id_jeu, id_genre) VALUES (:id_jeu, :id_genre)");
                $stmt->execute(['id_jeu' => $game_id, 'id_genre' => $genre_id]);
            }
    
            // Ajouter les plateformes associées
            foreach ($platform as $platform_id) {
                $stmt = $pdo->prepare("INSERT INTO Asso_2 (id_jeu, id_platform) VALUES (:id_jeu, :id_platform)");
                $stmt->execute(['id_jeu' => $game_id, 'id_platform' => $platform_id]);
            }
    
            // Ajouter les tags associés
            foreach ($tags as $tag_id) {
                $stmt = $pdo->prepare("INSERT INTO Asso_5 (id_jeu, id_tags) VALUES (:id_jeu, :id_tags)");
                $stmt->execute(['id_jeu' => $game_id, 'id_tags' => $tag_id]);
            }
    
            header("Location: /game-library/public/");
            exit;
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout du jeu : " . $e->getMessage();
            exit;
        }
    }
    

}


