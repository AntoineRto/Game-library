<?php require_once '../config/db.php'; ?>
<?php include 'navbar.php'; ?>
<?php include 'header.php'; ?>

<?php
// Récupérer les jeux, tags, genres et plateformes de la base de données
$stmt = $pdo->query("
    SELECT jeu.Id_jeu, jeu.title, jeu.description, jeu.release_date, img.url,
           GROUP_CONCAT(DISTINCT tags.name SEPARATOR ', ') AS tags,
           GROUP_CONCAT(DISTINCT genre.name SEPARATOR ', ') AS genres,
           GROUP_CONCAT(DISTINCT platform.name SEPARATOR ', ') AS platforms
    FROM jeu
    LEFT JOIN Asso_8 ON jeu.Id_jeu = Asso_8.Id_jeu
    LEFT JOIN img ON Asso_8.Id_img = img.Id_img
    LEFT JOIN Asso_5 ON jeu.Id_jeu = Asso_5.Id_jeu
    LEFT JOIN tags ON Asso_5.Id_tags = tags.Id_tags
    LEFT JOIN Asso_3 ON jeu.Id_jeu = Asso_3.Id_jeu
    LEFT JOIN genre ON Asso_3.Id_genre = genre.Id_genre
    LEFT JOIN Asso_2 ON jeu.Id_jeu = Asso_2.Id_jeu
    LEFT JOIN platform ON Asso_2.Id_platform = platform.Id_platform
    GROUP BY jeu.Id_jeu
");
$jeux = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des jeux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../public/assets/css/home.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Liste des jeux</h1>
    <div class="row mt-4">
        <!-- Boucle pour afficher les cartes des jeux -->
        <?php foreach ($jeux as $jeu): ?>
            <div class="col-md-4">
                <div class="game-card">
                    <!-- Image du jeu -->
                    <img src="<?= htmlspecialchars($jeu['url'] ?? '../public/assets/img/default.jpg') ?>" 
                         class="card-img-top" 
                         alt="<?= htmlspecialchars($jeu['title']) ?>">
                    <div class="card-body">
                        <!-- Titre du jeu -->
                        <h5 class="card-title"><?= htmlspecialchars($jeu['title']) ?></h5>
                        <!-- Description du jeu -->
                        <p class="card-text description"><?= htmlspecialchars($jeu['description']) ?></p>
                        <!-- Autres informations -->
                        <p class="card-text"><small class="text-muted">Sortie le <?= htmlspecialchars($jeu['release_date']) ?></small></p>
                        <p class="card-text"><strong>Genres :</strong> <?= htmlspecialchars($jeu['genres'] ?? 'N/A') ?></p>
                        <p class="card-text"><strong>Plateformes :</strong> <?= htmlspecialchars($jeu['platforms'] ?? 'N/A') ?></p>
                        <p class="card-text"><strong>Tags :</strong> <?= htmlspecialchars($jeu['tags'] ?? 'N/A') ?></p>
                    </div>
                    <!-- Pied de la carte avec les boutons -->
                        <div class="card-footer">
                            <!-- Bouton "Voir plus" pour accéder aux détails du jeu -->
                            <a href="/game-library/public/templates/game-details?id=<?= $jeu['Id_jeu'] ?>" class="btn btn-secondary">Voir plus</a>
                            <!-- Bouton "Ajouter à la collection" ou "Connectez-vous" selon l'état de connexion -->
                            <button class="btn btn-primary">
                                <?= isset($_SESSION['user_id']) ? 'Ajouter à la collection' : 'Connectez-vous pour ajouter à votre collection!' ?>
                            </button>
                        </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<script>
    document.querySelectorAll('.game-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.classList.add('hovered'); // Ajoute la classe pour agrandir la carte
        });

        card.addEventListener('mouseleave', () => {
            card.classList.remove('hovered'); // Retire l'agrandissement de la carte
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

<?php include 'footer.php'; ?>
</html>