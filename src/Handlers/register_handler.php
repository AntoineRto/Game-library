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

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
    $stmt->execute([':email' => $email]);
    if ($stmt->fetch()) {
        throw new Exception("Cet email est déjà utilisé.");
    }

    // Hacher le mot de passe
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Insérer dans la base de données
    $stmt = $pdo->prepare("INSERT INTO utilisateur (username, email, password) VALUES (:username, :email, :password)");
    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':password' => $password_hash
    ]);

    // Redirection après succès
    header("Location: /game-library/public/login.php");
    exit;

} catch (Exception $e) {
    // En cas d'erreur, afficher un message et ne pas rediriger vers la page d'erreur
    echo "Erreur : " . $e->getMessage();
}
