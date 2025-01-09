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
    
            // Gestion des images
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../public/assets/img/';
                $fileName = basename($_FILES['image']['name']);
                $targetFilePath = $uploadDir . $fileName;
    
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                    // Ajouter l'image dans la table img
                    $stmt = $pdo->prepare("INSERT INTO img (url) VALUES (:url)");
                    $stmt->execute(['url' => $fileName]);
    
                    $img_id = $pdo->lastInsertId();
    
                    // Associer l'image au jeu via Asso_8
                    $stmt = $pdo->prepare("INSERT INTO Asso_8 (id_jeu, id_img) VALUES (:id_jeu, :id_img)");
                    $stmt->execute([
                        'id_jeu' => $game_id,
                        'id_img' => $img_id,
                    ]);
                } else {
                    throw new Exception("Erreur lors du téléversement de l'image.");
                }
            }
    
            header("Location: /game-library/public/?success=game_added");
            exit;
        } catch (Exception $e) {
            echo "Erreur lors de l'ajout du jeu : " . $e->getMessage();
            exit;
        }
    }

    public function showEditGameForm() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] < 3) {
            header("Location: /game-library/public/home.php");
            exit;
        }
    
        require_once '../config/db.php';
    
        $id_jeu = intval($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT * FROM jeu WHERE Id_jeu = :id");
        $stmt->execute(['id' => $id_jeu]);
        $jeu = $stmt->fetch();
    
        if (!$jeu) {
            header("Location: /game-library/public/home.php?error=not_found");
            exit;
        }
    
        include '../templates/editGame.php';
    }
    
    public function submitEditGame() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] < 3) {
            header("Location: /game-library/public/");
            exit;
        }
    
        require_once '../config/db.php';
    
        try {
            $id_jeu = intval($_POST['id']);
            $title = htmlspecialchars($_POST['title']);
            $release_date = $_POST['release_date'];
            $description = htmlspecialchars($_POST['description']);
            $tags = $_POST['tags'] ?? [];
            $platforms = $_POST['platform'] ?? [];
    
            // Mettre à jour le jeu
            $stmt = $pdo->prepare("UPDATE jeu SET title = :title, release_date = :release_date, description = :description WHERE Id_jeu = :id");
            $stmt->execute([
                'title' => $title,
                'release_date' => $release_date,
                'description' => $description,
                'id' => $id_jeu,
            ]);
    
            // Mettre à jour les associations de tags
            $pdo->prepare("DELETE FROM Asso_5 WHERE id_jeu = :id")->execute(['id' => $id_jeu]);
            foreach ($tags as $tag_id) {
                $pdo->prepare("INSERT INTO Asso_5 (id_jeu, id_tags) VALUES (:id_jeu, :id_tags)")->execute(['id_jeu' => $id_jeu, 'id_tags' => $tag_id]);
            }
    
            // Mettre à jour les associations de plateformes
            $pdo->prepare("DELETE FROM Asso_2 WHERE id_jeu = :id")->execute(['id' => $id_jeu]);
            foreach ($platforms as $platform_id) {
                $pdo->prepare("INSERT INTO Asso_2 (id_jeu, id_platform) VALUES (:id_jeu, :id_platform)")->execute(['id_jeu' => $id_jeu, 'id_platform' => $platform_id]);
            }
    
            header("Location: /game-library/public/home.php?success=game_updated");
            exit;
        } catch (Exception $e) {
            echo "Erreur lors de la mise à jour du jeu : " . $e->getMessage();
            exit;
        }
    }
    
    public function deleteGame() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] < 3) {
            header("Location: /game-library/public/home.php");
            exit;
        }
    
        require_once '../config/db.php';
    
        try {
            $id_jeu = intval($_POST['id']);
    
            // Supprimer les relations dans les tables associatives
            $pdo->prepare("DELETE FROM asso_8 WHERE Id_jeu = :id_jeu")->execute(['id_jeu' => $id_jeu]);
            $pdo->prepare("DELETE FROM asso_5 WHERE Id_jeu = :id_jeu")->execute(['id_jeu' => $id_jeu]);
            $pdo->prepare("DELETE FROM asso_2 WHERE Id_jeu = :id_jeu")->execute(['id_jeu' => $id_jeu]);
    
            // Supprimer le jeu de la table principale
            $stmt = $pdo->prepare("DELETE FROM jeu WHERE Id_jeu = :id_jeu");
            $stmt->execute(['id_jeu' => $id_jeu]);
    
            header("Location: /game-library/public/");
            exit;
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression du jeu : " . $e->getMessage();
            exit;
        }
    }
    
    

} 


