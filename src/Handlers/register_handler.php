<?php
require_once '../../config/db.php';

try {
    // Récupérer les données du formulaire
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Vérification des mots de passe
    if ($password !== $confirm_password) {
        throw new Exception("Les mots de passe ne correspondent pas.");
    }

    // Vérifier si le nom d'utilisateur existe déjà
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE username = :username");
    $stmt->execute([':username' => $username]);
    if ($stmt->fetch()) {
        throw new Exception("Le nom d'utilisateur est déjà utilisé.");
    }

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
    $stmt->execute([':email' => $email]);
    if ($stmt->fetch()) {
        throw new Exception("L'email est déjà utilisé.");
    }

    // Hacher le mot de passe
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Insérer dans la base de données
    $stmt = $pdo->prepare("INSERT INTO utilisateur (username, email, password, registration_date, roles) VALUES (:username, :email, :password, CURDATE(), :roles)");
    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':password' => $password_hash,
        ':roles' => 2
    ]);

    // Redirection après succès
    header("Location: /game-library/public/login");
    exit;

} catch (Exception $e) {
    // Rediriger vers le formulaire avec un message d'erreur
    $error_message = urlencode($e->getMessage());
    header("Location: /game-library/public/register?error=$error_message");
    exit;
}

