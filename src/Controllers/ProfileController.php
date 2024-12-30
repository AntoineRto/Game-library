<?php
namespace App\Controllers;

use PDO;

class ProfileController {
    public function showProfile() {
        
        // Vérifiez si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header("Location: /game-library/public/login");
            exit;
        }

        // Inclure la configuration de la base de données
        require_once '../config/db.php';

        // Récupérer les informations de l'utilisateur connecté
        $user_id = $_SESSION['user_id'];
        $stmt = $pdo->prepare("SELECT username, email, registration_date, roles FROM utilisateur WHERE Id_user = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        $user = $stmt->fetch();

        if (!$user) {
            echo "Utilisateur introuvable.";
            exit;
        }

        // Inclure le template de la page profil
        include '../templates/profile.php';
    }

    public function editProfile() {
        
        if (!isset($_SESSION['user_id'])) {
                header("Location: /game-library/public/login");
                exit;
            }
    
            require_once '../config/db.php';
            $user_id = $_SESSION['user_id'];
            $error_message = '';
            $success_message = '';
    
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = htmlspecialchars($_POST['email']);
                $password = !empty($_POST['password']) ? $_POST['password'] : null;
    
                // Validation de l'email
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error_message = "L'adresse email n'est pas valide.";
                }
    
                // Validation du mot de passe (si fourni)
                if ($password && strlen($password) < 6) {
                    $error_message = "Le mot de passe doit contenir au moins 6 caractères.";
                }
    
                if (empty($error_message)) {
                    // Mise à jour de l'email
                    $stmt = $pdo->prepare("UPDATE utilisateur SET email = :email WHERE Id_user = :user_id");
                    $stmt->execute(['email' => $email, 'user_id' => $user_id]);
    
                    // Mise à jour du mot de passe (si fourni)
                    if ($password) {
                        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                        $stmt = $pdo->prepare("UPDATE utilisateur SET password = :password WHERE Id_user = :user_id");
                        $stmt->execute(['password' => $hashed_password, 'user_id' => $user_id]);
                    }
    
                    $success_message = "Profil mis à jour avec succès.";
                }
            }
    
            $stmt = $pdo->prepare("SELECT email FROM utilisateur WHERE Id_user = :user_id");
            $stmt->execute(['user_id' => $user_id]);
            $user = $stmt->fetch();
    
            if (!$user) {
                echo "Utilisateur introuvable.";
                exit;
            }
    
            include '../templates/editProfile.php';
        }
    }












