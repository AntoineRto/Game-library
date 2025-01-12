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
        //Inclure le fichier addgame.php
        include '../templates/addGame.php';
    }
    
    //Fonction pour ajouter un jeu
    public function submitAddGame() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] < 3) {
            header("Location: /game-library/public/");
            exit;
        }
    
        require_once '../config/db.php';
    
        try {
            //Récupérer les données
            $title = htmlspecialchars($_POST['title']);
            $release_date = $_POST['release_date'];
            $description = htmlspecialchars($_POST['description']);
            $tags = $_POST['tags']; // IDs des tags
            $platform = $_POST['platform']; // IDs des plateformes
    
            //Gérer l'image
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imageTmpName = $_FILES['image']['tmp_name'];
                $imageName = basename($_FILES['image']['name']);
                $imagePath = __DIR__ . "/../../public/uploads/" . $imageName;

    
                //Déplace le fichier téléchargé
                if (!move_uploaded_file($imageTmpName, $imagePath)) {
                    throw new Exception("Erreur lors de l'upload de l'image.");
                }
    
                //Enregistrer l'image dans la table img
                $stmtImg = $pdo->prepare("INSERT INTO img (url) VALUES (:url)");
                $stmtImg->execute(['url' => $imagePath]);
                $imageId = $pdo->lastInsertId();
            } else {
                throw new Exception("Aucune image n'a été téléchargée ou une erreur est survenue.");
            }
    
            //Ajouter le jeu
            $stmt = $pdo->prepare("INSERT INTO jeu (title, release_date, description) VALUES (:title, :release_date, :description)");
            $stmt->execute([
                'title' => $title,
                'release_date' => $release_date,
                'description' => $description,
            ]);
    
            $gameId = $pdo->lastInsertId();
    
            // Associer le jeu à son image
            $stmtAssocImg = $pdo->prepare("INSERT INTO Asso_8 (Id_jeu, Id_img) VALUES (:Id_jeu, :Id_img)");
            $stmtAssocImg->execute(['Id_jeu' => $gameId, 'Id_img' => $imageId]);
    
            // Ajouter les tags associés
            foreach ($tags as $tag_id) {
                $stmtTag = $pdo->prepare("INSERT INTO Asso_5 (Id_jeu, Id_tags) VALUES (:Id_jeu, :Id_tags)");
                $stmtTag->execute(['Id_jeu' => $gameId, 'Id_tags' => $tag_id]);
            }
    
            // Ajouter les plateformes associées
            foreach ($platform as $platform_id) {
                $stmtPlatform = $pdo->prepare("INSERT INTO Asso_2 (Id_jeu, Id_platform) VALUES (:Id_jeu, :Id_platform)");
                $stmtPlatform->execute(['Id_jeu' => $gameId, 'Id_platform' => $platform_id]);
            }
    
            // Redirection après succès
            header("Location: /game-library/public/");
            exit;
    
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
            exit;
        }
    }
    
    //Editer un jeu
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
    
    //Submit l'edition du jeu
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
    
            header("Location: /game-library/public/");
            exit;
        } catch (Exception $e) {
            echo "Erreur lors de la mise à jour du jeu : " . $e->getMessage();
            exit;
        }
    }
    
    //Suppression d'un jeu avec ses reviews
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
    
            // Supprimer les relations dans la table des reviews (asso_7)
            $pdo->prepare("DELETE FROM asso_7 WHERE Id_jeu = :id_jeu")->execute(['id_jeu' => $id_jeu]);
    
            // Supprimer les reviews elles-mêmes
            $pdo->prepare("
                DELETE reviews FROM reviews
                INNER JOIN asso_7 ON reviews.id_reviews = asso_7.id_reviews
                WHERE asso_7.id_jeu = :id_jeu
            ")->execute(['id_jeu' => $id_jeu]);
    
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


