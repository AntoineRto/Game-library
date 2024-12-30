<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Collection</title>
    <link rel="stylesheet" href="../public/assets/css/collection.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h1 class="text-light">Ma Collection</h1>

        <?php
        $categories = ['à faire', 'fait', 'favoris', 'lâché'];
        foreach ($categories as $category): 
        ?>
            <h2 class="text-light"><?= ucfirst($category) ?></h2>
            <div class="row">
                <?php foreach ($games as $game): ?>
                    <?php if ($game['status'] === $category): ?>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <img src="<?= htmlspecialchars($game['url'] ?? '../public/assets/img/default.jpg') ?>" class="card-img-top" alt="<?= htmlspecialchars($game['title']) ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($game['title']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($game['description']) ?></p>
                                    <p class="card-text"><strong>Priorité :</strong> <?= $game['priority'] ? 'Oui' : 'Non' ?></p>
                                    <p class="card-text"><strong>Temps joué :</strong> <?= htmlspecialchars($game['time_played']) ?> minutes</p>

                                    <form method="POST" action="/game-library/public/update-status">
                                        <input type="hidden" name="id_jeu" value="<?= $game['id_jeu'] ?>">
                                        <select name="status" class="form-select mb-2">
                                            <option value="à faire" <?= $game['status'] === 'à faire' ? 'selected' : '' ?>>À faire</option>
                                            <option value="fait" <?= $game['status'] === 'fait' ? 'selected' : '' ?>>Fait</option>
                                            <option value="favoris" <?= $game['status'] === 'favoris' ? 'selected' : '' ?>>Favoris</option>
                                            <option value="lâché" <?= $game['status'] === 'lâché' ? 'selected' : '' ?>>Lâché</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                    </form>

                                    <form method="POST" action="/game-library/public/remove-game">
                                        <input type="hidden" name="id_jeu" value="<?= $game['id_jeu'] ?>">
                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
