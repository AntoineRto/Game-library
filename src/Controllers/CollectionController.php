<?php
namespace App\Controllers;

use PDO;

class CollectionController {
    public function showCollection() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /game-library/public/login");
            exit;
        }

        require_once '../config/db.php';
        $user_id = $_SESSION['user_id'];

        // Récupérer les jeux de la collection de l'utilisateur
        $stmt = $pdo->prepare("
            SELECT jeu.id_jeu, jeu.title, jeu.description, jeu.release_date, collection.status, collection.personal_note, collection.time_played, collection.priority
            FROM collection
            JOIN jeu ON collection.id_jeu = jeu.id_jeu
            WHERE collection.id_user = :user_id
            ORDER BY collection.status, collection.priority DESC, collection.added_at ASC
        ");
        $stmt->execute(['user_id' => $user_id]);
        $games = $stmt->fetchAll();

        include '../templates/collection.php';
    }

    public function updateGameStatus() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /game-library/public/login");
            exit;
        }

        require_once '../config/db.php';
        $user_id = $_SESSION['user_id'];
        $jeu_id = intval($_POST['id_jeu']);
        $status = htmlspecialchars($_POST['status']);

        // Mettre à jour le statut du jeu dans la collection
        $stmt = $pdo->prepare("
            UPDATE collection
            SET status = :status
            WHERE id_user = :user_id AND id_jeu = :id_jeu
        ");
        $stmt->execute([
            'status' => $status,
            'user_id' => $user_id,
            'id_jeu' => $jeu_id
        ]);

        header("Location: /game-library/public/collection");
        exit;
    }

    public function removeGameFromCollection() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /game-library/public/login");
            exit;
        }

        require_once '../config/db.php';
        $user_id = $_SESSION['user_id'];
        $jeu_id = intval($_POST['id_jeu']);

        // Supprimer un jeu de la collection
        $stmt = $pdo->prepare("
            DELETE FROM collection
            WHERE id_user = :user_id AND id_jeu = :id_jeu
        ");
        $stmt->execute([
            'user_id' => $user_id,
            'id_jeu' => $jeu_id
        ]);

        header("Location: /game-library/public/collection");
        exit;
    }
}
