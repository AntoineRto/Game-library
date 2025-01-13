<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de recherche</title>
    <?php include 'navbar.php'; ?>
    <link rel="stylesheet" href="../public/assets/css/home.css">
</head>
<body>
    

    <div class="container mt-5">
        <h1>Résultats pour "<?= htmlspecialchars($_GET['query']) ?>"</h1>

        <?php if (!empty($results)): ?>
            <div class="row mt-4">
                <?php foreach ($results as $result): ?>
                    <div class="col-md-4">
                        <div class="game-card">
                            <img src="<?= htmlspecialchars($result['url'] ?? '../public/assets/img/default.jpg') ?>" 
                                 class="card-img-top" 
                                 alt="<?= htmlspecialchars($result['title']) ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($result['title']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($result['description']) ?></p>
                                <a href="/game-library/public/game-details?id=<?= $result['Id_jeu'] ?>" class="btn btn-primary">Voir plus</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucun résultat trouvé pour votre recherche.</p>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
