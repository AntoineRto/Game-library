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

    <?php
        require_once '../config/db.php';

        $collections = [];
        $stmt = $pdo->prepare("
            SELECT collection.id_collection, collection.added_at, collection.personnal_note, collection.time_played, collection.favori,
                jeu.Id_jeu, jeu.title, jeu.description, jeu.release_date,
                img.url,
                GROUP_CONCAT(DISTINCT tags.name SEPARATOR ', ') AS tags
            FROM collection
            JOIN jeu ON collection.Id_jeu = jeu.Id_jeu
            LEFT JOIN Asso_8 ON jeu.Id_jeu = Asso_8.Id_jeu
            LEFT JOIN img ON Asso_8.Id_img = img.Id_img
            LEFT JOIN Asso_5 ON jeu.Id_jeu = Asso_5.Id_jeu
            LEFT JOIN tags ON Asso_5.Id_tags = tags.Id_tags
            WHERE collection.id_user = :id_user
            GROUP BY collection.id_collection
        ");
        $stmt->execute(['id_user' => $_SESSION['user_id']]);
        $collection = $stmt->fetchAll();
        ?>


    <div class="container mt-5">
        <h1 class="text-light">Ma Collection</h1>
        <div class="row mt-4">
            <?php foreach ($collections as $collection): ?>
                <div class="col-md-4">
                    <div class="game-card">
                        <img src="<?= htmlspecialchars($collection['url'] ?? '../public/assets/img/default.jpg') ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($collection['title']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($collection['title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($collection['description']) ?></p>
                            <p class="card-text">
                                <small class="text-muted">Status: <?= htmlspecialchars($collection['status']) ?></small>
                            </p>
                        </div>
                        <div class="card-footer">
                            <form method="POST" action="/game-library/public/update-collection">
                                <input type="hidden" name="id_collection" value="<?= $collection['id_collection'] ?>">
                                <select name="status" class="form-control mb-2">
                                    <option value="à faire" <?= $collection['status'] === 'à faire' ? 'selected' : '' ?>>À faire</option>
                                    <option value="fait" <?= $collection['status'] === 'fait' ? 'selected' : '' ?>>Fait</option>
                                    <option value="favoris" <?= $collection['status'] === 'favoris' ? 'selected' : '' ?>>Favoris</option>
                                    <option value="lâché" <?= $collection['status'] === 'lâché' ? 'selected' : '' ?>>Lâché</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                            </form>
                            <form method="POST" action="/game-library/public/delete-from-collection" class="mt-2">
                                <input type="hidden" name="id_collection" value="<?= $collection['id_collection'] ?>">
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
