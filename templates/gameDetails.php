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
           GROUP_CONCAT(DISTINCT platform.name SEPARATOR ', ') AS platforms,
           GROUP_CONCAT(DISTINCT tags.name SEPARATOR ', ') AS tags
    FROM jeu
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

// Récupérer les avis liés au jeu via Asso_7
$stmtReviews = $pdo->prepare("
    SELECT reviews.comment, reviews.note, reviews.created_at, utilisateur.username
    FROM reviews
    JOIN Asso_7 ON reviews.id_reviews = Asso_7.id_reviews
    JOIN utilisateur ON Asso_7.id_user = utilisateur.id_user
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
        <a href="/game-library/public/" class="btn btn-secondary mt-3">Retour</a>
    </div>

    <!-- Section des avis des utilisateurs -->
    <div class="container mt-5">
        <h3 class="text-light">Avis des utilisateurs</h3>
        <?php if (count($reviews) > 0): ?>
            <?php foreach ($reviews as $review): ?>
                <div class="review">
                    <p><strong><?= htmlspecialchars($review['username']) ?></strong></p>
                    <p><?= htmlspecialchars($review['comment']) ?></p>
                    <p>Note : <?= htmlspecialchars($review['note']) ?>/5</p>
                    <p><small>Posté le <?= htmlspecialchars($review['created_at']) ?></small></p>
                </div>
                <hr>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-light">Aucun avis disponible pour ce jeu.</p>
        <?php endif; ?>
    </div>

    <?php if (isset($_SESSION['user_id'])): ?>
    <div class="mt-5">
        <h3 class="text-light">Laissez un avis</h3>
        <form action="/game-library/public/add-review" method="POST">
            <input type="hidden" name="id_jeu" value="<?= $id_jeu ?>">
            <div class="form-group">
                <label for="comment" class="text-light">Commentaire :</label>
                <textarea id="comment" name="comment" class="form-control" rows="4" required></textarea>
            </div>
            <div class="form-group mt-3">
                <label for="note" class="text-light">Note :</label>
                <select id="note" name="note" class="form-control" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Soumettre</button>
        </form>
    </div>
    <?php else: ?>
        <p class="text-light mt-5">Veuillez <a href="/game-library/public/login" class="text-info">vous connecter</a> pour laisser un avis.</p>
    <?php endif; ?>
    
    <!-- Message de succès qd l'utilsateur poste son commentaire! -->
    <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
<?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>



    <?php include 'footer.php'; ?>
</body>
</html>
