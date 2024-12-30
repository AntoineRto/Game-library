<?php
// Vérifiez si les informations utilisateur sont disponibles
if (!isset($user)) {
    echo "Erreur : Les informations utilisateur ne sont pas disponibles.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="../public/assets/css/profile.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h1 class="text-light">Mon Profil</h1>
        <div class="card">
            <div class="card-body">
                <p><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($user['username']) ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Date d'inscription :</strong> <?= htmlspecialchars($user['registration_date']) ?></p>
                <p><strong>Rôle :</strong> <?= $user['roles'] == 3 ? 'Administrateur' : ($user['roles'] == 2 ? 'Utilisateur' : 'Invité') ?></p>
            </div>
        </div>
        <a href="/game-library/public/edit-profile" class="btn btn-primary mt-3">Modifier mes informations</a>
        <a href="/game-library/public/home.php" class="btn btn-secondary mt-3">Retour</a>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
