<?php
// Inclure la configuration de la base de données
require_once '../config/db.php';
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

// Si aucun jeu n'est trouvé, afficher un message
if (!$jeu) {
    echo "Jeu non trouvé.";
    exit;
}

$stmtReviews = $pdo->prepare("
    SELECT reviews.review, reviews.note, utilisateur.username, reviews.created_at
    FROM reviews
    JOIN Asso_7 ON reviews.id_reviews = Asso_7.id_reviews
    JOIN utilisateur ON reviews.id_user = utilisateur.id_user
    WHERE Asso_7.id_jeu = :id_jeu
");
$stmtReviews->execute(['id_jeu' => $id_jeu]);
$reviews = $stmtReviews->fetchAll();


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
        <!-- Affichage des détails du jeu -->
        <h1 class="text-light"><?= htmlspecialchars($jeu['title']) ?></h1>
        <p class="text-light"><strong>Date de sortie :</strong> <?= htmlspecialchars($jeu['release_date']) ?></p>
        <p class="text-light"><strong>Description :</strong> <?= htmlspecialchars($jeu['description']) ?></p>
        <p class="text-light"><strong>Genres :</strong> <?= htmlspecialchars($jeu['genres'] ?? 'N/A') ?></p>
        <p class="text-light"><strong>Plateformes :</strong> <?= htmlspecialchars($jeu['platforms'] ?? 'N/A') ?></p>
        <p class="text-light"><strong>Tags :</strong> <?= htmlspecialchars($jeu['tags'] ?? 'N/A') ?></p>

        <!-- Bouton pour ajouter à la collection -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <form method="POST" action="/game-library/public/add-to-collection">
                <input type="hidden" name="Id_jeu" value="<?= $id_jeu ?>">
                <button type="submit" class="btn btn-primary">Ajouter à ma collection</button>
            </form>
        <?php else: ?>
            <a href="/game-library/public/login" class="btn btn-secondary">Connectez-vous pour ajouter à votre collection</a>
        <?php endif; ?>

        <!-- Bouton de retour -->
        <a href="/game-library/public/home.php" class="btn btn-secondary mt-3">Retour</a>
    </div>

    <!-- Section des avis des utilisateurs -->
    <div class="container mt-5">
        <h3 class="text-light">Avis des utilisateurs</h3>
        <?php if (count($reviews) > 0): ?>
            <?php foreach ($reviews as $review): ?>
                <p>
                    <strong><?= htmlspecialchars($review['username']) ?></strong> : 
                    <?= htmlspecialchars($review['review']) ?> 
                    (Note : <?= htmlspecialchars($review['note']) ?>/5)
                </p>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-light">Aucun avis disponible pour ce jeu.</p>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
