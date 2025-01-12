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
        
    
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header("Location: /game-library/public/login");
            exit;
        }
    
        require_once '../config/db.php';
    
        try {
            $id_user = $_SESSION['user_id'];
            $id_jeu = intval($_POST['id_jeu']);
    
            // Vérifier si le jeu est déjà dans la collection
            $stmt = $pdo->prepare("SELECT * FROM collection WHERE id_user = :id_user AND id_jeu = :id_jeu");
            $stmt->execute(['id_user' => $id_user, 'id_jeu' => $id_jeu]);
            $existing = $stmt->fetch();
    
            if ($existing) {
                // Si le jeu est déjà dans la collection, retournez avec un message
                $_SESSION['message'] = "Ce jeu est déjà dans votre collection.";
                header("Location: /game-library/public/home.php");
                exit;
            }
    
            // Récupérer l'ID du statut "à faire" depuis la table status
            $stmt = $pdo->prepare("SELECT Id_status FROM status WHERE name = 'à faire'");
            $stmt->execute();
            $status = $stmt->fetch();
    
            if (!$status) {
                throw new Exception("Le statut 'à faire' est introuvable dans la table status.");
            }
    
            $id_status = $status['Id_status'];
    
            // Ajouter le jeu à la collection avec le statut "à faire"
            $stmt = $pdo->prepare("
                INSERT INTO collection (id_user, id_jeu, added_at, id_status)
                VALUES (:id_user, :id_jeu, NOW(), :id_status)
            ");
            $stmt->execute(['id_user' => $id_user, 'id_jeu' => $id_jeu, 'id_status' => $id_status]);
    
            $_SESSION['message'] = "Le jeu a été ajouté à votre collection avec succès.";
            header("Location: /game-library/public/");
            exit;
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout du jeu à la collection : " . $e->getMessage();
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
}
