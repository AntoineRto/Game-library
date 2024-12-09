<?php include 'navbar.php'; ?> <!-- Inclure la navbar -->
<?php include 'header.php'; ?> <!-- Inclure l'en-tête -->
<?php require_once '../config/db.php'; ?>

<?php
// Récupérer tous les jeux de la table `jeu`
$stmt = $pdo->query("SELECT title, description, release_date FROM jeu");
$jeux = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Titre</title>
    <link rel="stylesheet" href="../public/assets/css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
require_once '../config/db.php';

// Requête SQL pour récupérer les jeux et leurs images
$stmt = $pdo->query("
    SELECT jeu.title, jeu.description, jeu.release_date, img.url
    FROM jeu
    LEFT JOIN Asso_8 ON jeu.Id_jeu = Asso_8.Id_jeu
    LEFT JOIN img ON Asso_8.Id_img = img.Id_img
");
$jeux = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des jeux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Liste des jeux</h1>
    <h1>CETTE PAGE EST CENSE ETRE CELLE PAR DEFAULT</h1>
    <div class="row">
        <?php foreach ($jeux as $jeu): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <!-- Afficher l'image ou une image par défaut -->
                    <img src="<?= htmlspecialchars($jeu['url'] ?? '/assets/img/default.jpg') ?>" 
                         class="card-img-top" 
                         alt="<?= htmlspecialchars($jeu['title']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($jeu['title']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($jeu['description']) ?></p>
                        <p class="card-text"><small class="text-muted">Sortie le <?= htmlspecialchars($jeu['release_date']) ?></small></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<?php include 'footer.php'; ?> <!-- Inclure le pied de page -->
