<?php

namespace App\Controllers;

use PDO;

class ReviewController {
    public function addReview() {
        session_start();
        require_once '../config/db.php';

        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: /game-library/public/login');
            exit;
        }

        // Récupérer les données POST
        $id_user = $_SESSION['user_id'];
        $id_jeu = intval($_POST['id_jeu']);
        $comment = htmlspecialchars($_POST['comment']);
        $note = intval($_POST['note']);

        // Vérifier que les données sont valides
        if (empty($comment) || $note < 1 || $note > 5) {
            header('Location: /game-library/public/game-details?id=' . $id_jeu . '&error=Invalid input');
            exit;
        }

        // Insérer le commentaire dans la base de données
      
            // Ajouter un nouvel avis dans la table reviews
            $stmt = $pdo->prepare("INSERT INTO reviews (comment, note, created_at) VALUES (:comment, :note, NOW())");
            $stmt->execute(['comment' => $comment, 'note' => $note]);

            // Récupérer l'ID du nouvel avis inséré
            $id_reviews = $pdo->lastInsertId();

            // Ajouter l'association dans Asso_7
            $stmtAssoc = $pdo->prepare("INSERT INTO Asso_7 (id_jeu, id_reviews, id_user) VALUES (:id_jeu, :id_reviews, :id_user)");
            $stmtAssoc->execute(['id_jeu' => $id_jeu, 'id_reviews' => $id_reviews, 'id_user' => $id_user]);

            // Rediriger vers la page des détails du jeu
            header('Location: /game-library/public/game-details?id=' . $id_jeu . '&success=Review added');
        } 
    }

