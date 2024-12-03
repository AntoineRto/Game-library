<?php 

require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // récupérer les données du formulaire
    $username = htmlspecialchars($_POST['username']); // Nettoyer les entrées pour éviter les failles XSS
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // sert à confirmer le mdp
    if ($password !== $confirm_password) {
        die("Les mots de passe ne correspondent pas.");
    }

    // vérifier mail existe
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
    $stmt->execute([':email' => $email]);
    if ($stmt->fetch()) {
        die("Cet email est déjà utilisé.");
    }

    // crypter le mot de passe
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // insérer l'utilisateur dans la base
    $stmt = $pdo->prepare("INSERT INTO utilisateur (username, email, password) VALUES (:username, :email, :password)");
    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':password' => $password_hash
    ]);

    echo "Inscription réussie ! Vous pouvez maintenant vous connecter.";
}

//
header('location: /game-library/public/login.php');
exit;

?>