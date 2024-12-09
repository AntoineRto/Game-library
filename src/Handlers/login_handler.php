<?php
require_once '../../config/db.php';
session_start(); // Démarrer la session pour gérer l'utilisateur connecté

try {
    // Récupérer les données du formulaire
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // Vérifier si l'email existe dans la base de données
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if (!$user) {
        // Email non trouvé
        throw new Exception("L'adresse email est incorrecte.");
    }

    // Vérifier le mot de passe
    if (!password_verify($password, $user['password'])) {
        // Mot de passe incorrect
        throw new Exception("Le mot de passe est incorrect.");
    }

    // Stocker les informations de l'utilisateur dans la session
    $_SESSION['user_id'] = $user['Id_user'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['roles']; // MODIFICATION : Ajout du rôle

    // Rediriger vers la page d'accueil après connexion réussie
    header("Location: /game-library/public/");
    exit;

} catch (Exception $e) {
    // Rediriger vers la page de connexion avec un message d'erreur
    $error_message = urlencode($e->getMessage());
    header("Location: /game-library/public/login?error=$error_message");
    exit;
}
