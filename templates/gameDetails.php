<?php
// Inclure la configuration de la base de données
require_once '../config/db.php';
session_start();

// Vérifier si un ID de jeu est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: /game-library/public/home.php");
    exit;
}

// Récupérer les informations du jeu via l'ID
$id_jeu = intval($_GET['id']);
$stmt = $pdo->prepare("
    SELECT jeu.*, 
           GROUP_CONCAT(DISTINCT genre.name SEPARATOR ', ') AS genres, 
           GROUP_CONCAT(DISTINCT platform.name SEPARATOR ', ') AS platforms,
           GROUP_CONCAT(DISTINCT tags.name SEPARATOR ', ') AS tags
    FROM jeu
    LEFT JOIN Asso_3 ON jeu.Id_jeu = Asso_3.Id_jeu
    LEFT JOIN genre ON Asso_3.Id_genre = genre.Id_genre
    LEFT JOIN Asso_2 ON jeu.Id_jeu = Asso_2.Id_jeu
    LEFT JOIN platform ON Asso_2.Id_platform = platform.Id_platform
    LEFT JOIN Asso_5 ON jeu.Id_jeu = Asso_5.Id_jeu
    LEFT JOIN tags ON Asso_5.Id_tags = tags.Id_tags
    WHERE jeu.Id_jeu = :id
    GROUP BY jeu.Id_jeu
");
$stmt->execute(['id' => $id_jeu]);
$jeu = $stmt->fetch();

if (!$jeu) {
    echo "Jeu non trouvé.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du jeu</title>
    <link rel="stylesheet" href="../public/assets/css/gameDetails.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h1 class="text-light"><?= htmlspecialchars($jeu['title']) ?></h1>
        <p class="text-light"><strong>Date de sortie :</strong> <?= htmlspecialchars($jeu['release_date']) ?></p>
        <p class="text-light"><strong>Description :</strong> <?= htmlspecialchars($jeu['description']) ?></p>
        <p class="text-light"><strong>Genres :</strong> <?= htmlspecialchars($jeu['genres'] ?? 'N/A') ?></p>
        <p class="text-light"><strong>Plateformes :</strong> <?= htmlspecialchars($jeu['platforms'] ?? 'N/A') ?></p>
        <p class="text-light"><strong>Tags :</strong> <?= htmlspecialchars($jeu['tags'] ?? 'N/A') ?></p>
        <a href="/game-library/public/home.php" class="btn btn-secondary mt-3">Retour</a>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
