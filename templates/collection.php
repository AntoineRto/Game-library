<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Collection</title>
    <?php include 'navbar.php'; ?>
    <link rel="stylesheet" href="../public/assets/css/collection.css">
</head>
<body>
    
    <?php
        require_once '../config/db.php';

        $stmt = $pdo->prepare("
    SELECT collection.id_collection, collection.added_at, collection.personnal_note, collection.time_played, collection.favori,
           jeu.Id_jeu, jeu.title, jeu.description, jeu.release_date,
           img.url,
           GROUP_CONCAT(DISTINCT tags.name SEPARATOR ', ') AS tags,
           status.name AS status_name
    FROM collection
    JOIN jeu ON collection.id_jeu = jeu.id_jeu
    LEFT JOIN asso_8 ON jeu.id_jeu = asso_8.id_jeu
    LEFT JOIN img ON asso_8.id_img = img.id_img
    LEFT JOIN asso_5 ON jeu.id_jeu = asso_5.id_jeu
    LEFT JOIN tags ON asso_5.id_tags = tags.id_tags
    JOIN status ON collection.id_status = status.id_status
    WHERE collection.id_user = :id_user
    GROUP BY collection.id_collection
");
$stmt->execute(['id_user' => $_SESSION['user_id']]);
$collections = $stmt->fetchAll();


    ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success']); ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($_SESSION['error']); ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

    <div class="container mt-5">
    <h1>Ma Collection</h1>

    <?php if (!empty($collections)): ?>
        <div class="row mt-4">
            <?php foreach ($collections as $item): ?>
                <div class="col-md-4">
                    <div class="game-card">
                        <img src="<?= htmlspecialchars($item['url'] ?? '../public/assets/img/default.jpg') ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($item['title']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($item['title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($item['description']) ?></p>
                            <p class="card-text"><small class="text-muted">Ajouté le <?= htmlspecialchars($item['added_at']) ?></small></p>
                            <p class="card-text"><strong>Tags :</strong> <?= htmlspecialchars($item['tags'] ?? 'Aucun') ?></p>
                            <p class="card-text"><strong>Statut :</strong> <?= htmlspecialchars($item['status_name']) ?></p>
                        </div>
                        <div class="footer">    
                            <form action="/game-library/public/update-status" method="POST" class="mt-2">
                                <input type="hidden" name="id_collection" value="<?= $item['id_collection'] ?>">
                                <select name="id_status" class="form-control">
                                    <option value="1" <?= $item['status_name'] === 'à faire' ? 'selected' : '' ?>>À faire</option>
                                    <option value="2" <?= $item['status_name'] === 'fait' ? 'selected' : '' ?>>Fait</option>
                                    <option value="3" <?= $item['status_name'] === 'favoris' ? 'selected' : '' ?>>Favoris</option>
                                    <option value="4" <?= $item['status_name'] === 'lâché' ? 'selected' : '' ?>>Lâché</option>
                                </select>
                                <button type="submit" class="btn btn-primary mt-2">Mettre à jour</button>
                            </form>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Votre collection est vide pour le moment.</p>
    <?php endif; ?>
</div>




    <?php include 'footer.php'; ?>
</body>
</html>
