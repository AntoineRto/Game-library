<?php
namespace App\Controllers;

class CollectionController {
    public function showCollection() {
        require_once '../config/db.php';
        

        if (!isset($_SESSION['user_id'])) {
            header("Location: /game-library/public/login");
            exit;
        }

        $user_id = $_SESSION['user_id'];

        $stmt = $pdo->prepare("
        SELECT collection.*, jeu.title, jeu.description, jeu.release_date,
               GROUP_CONCAT(DISTINCT img.url) AS images, status.name AS status_name
        FROM collection
        JOIN jeu ON collection.id_jeu = jeu.Id_jeu
        LEFT JOIN Asso_8 ON jeu.Id_jeu = Asso_8.Id_jeu
        LEFT JOIN img ON Asso_8.Id_img = img.Id_img
        JOIN status ON collection.id_status = status.Id_status
        WHERE collection.id_user = :id_user
        GROUP BY collection.id_collection
    ");
    $stmt->execute(['id_user' => $_SESSION['user_id']]);
    $collection = $stmt->fetchAll();
    

        include '../templates/collection.php';
    }

    public function addToCollection() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /game-library/public/login");
            exit;
        }
    
        require_once '../config/db.php';
    
        try {
            $id_user = $_SESSION['user_id'];
            $id_jeu = intval($_POST['id_jeu']);
    
            // Vérifier si le jeu est déjà dans la collection
            $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM collection WHERE id_user = :id_user AND id_jeu = :id_jeu");
            $stmtCheck->execute(['id_user' => $id_user, 'id_jeu' => $id_jeu]);
            $exists = $stmtCheck->fetchColumn();
    
            if ($exists > 0) {
                $_SESSION['error'] = "Ce jeu est déjà dans votre collection.";
                header("Location: /game-library/public/collection");
                exit;
            }
    
            // Récupérer l'ID du statut "à faire"
            $stmtStatus = $pdo->prepare("SELECT id_status FROM status WHERE name = 'à faire'");
            $stmtStatus->execute();
            $status = $stmtStatus->fetch();
    
            if (!$status) {
                throw new Exception("Le statut 'à faire' est introuvable dans la table status.");
            }
    
            $id_status = $status['id_status'];
    
            // Ajouter le jeu à la collection
            $stmtInsert = $pdo->prepare("
                INSERT INTO collection (id_user, id_jeu, id_status, added_at)
                VALUES (:id_user, :id_jeu, :id_status, NOW())
            ");
            $stmtInsert->execute(['id_user' => $id_user, 'id_jeu' => $id_jeu, 'id_status' => $id_status]);
    
            $_SESSION['success'] = "Jeu ajouté à votre collection avec succès.";
            header("Location: /game-library/public/collection");
            exit;
    
        } catch (PDOException $e) {
            $_SESSION['error'] = "Erreur lors de l'ajout : " . $e->getMessage();
            header("Location: /game-library/public/home");
            exit;
        }
    }
    
    public function deleteFromCollection() {
        require_once '../config/db.php';
        

        if (!isset($_SESSION['user_id'])) {
            header("Location: /game-library/public/login");
            exit;
        }

        $id_collection = intval($_POST['id_collection']);
        $stmt = $pdo->prepare("DELETE FROM collection WHERE id_collection = :id_collection");
        $stmt->execute(['id_collection' => $id_collection]);

        header("Location: /game-library/public/collection");
        exit;
    }

public function updateStatus() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /game-library/public/login");
        exit;
    }

    require_once '../config/db.php';

    try {
        $id_collection = intval($_POST['id_collection']);
        $id_status = intval($_POST['id_status']);

        $stmt = $pdo->prepare("
            UPDATE collection
            SET id_status = :id_status
            WHERE id_collection = :id_collection
        ");
        $stmt->execute(['id_status' => $id_status, 'id_collection' => $id_collection]);

        $_SESSION['success'] = "Statut mis à jour avec succès.";
        header("Location: /game-library/public/collection");
        exit;

    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de la mise à jour : " . $e->getMessage();
        header("Location: /game-library/public/collection");
        exit;
    }
}
}