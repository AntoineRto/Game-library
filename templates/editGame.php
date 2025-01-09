<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le jeu</title>
    <link rel="stylesheet" href="../public/assets/css/editGame.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h1 class="text-light">Modifier le jeu</h1>
        <form action="/game-library/public/edit-game-submit" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= htmlspecialchars($jeu['Id_jeu']) ?>">

            <div class="mb-3">
                <label for="title" class="form-label text-light">Titre du jeu</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($jeu['title']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="release_date" class="form-label text-light">Date de sortie</label>
                <input type="date" class="form-control" id="release_date" name="release_date" value="<?= htmlspecialchars($jeu['release_date']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label text-light">Description</label>
                <textarea class="form-control" id="description" name="description" rows="5" required><?= htmlspecialchars($jeu['description']) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="tags" class="form-label text-light">Tags</label>
                <select class="form-control" id="tags" name="tags[]" multiple>
                    <?php
                    $stmtTags = $pdo->query("SELECT id_tags, name FROM tags");
                    while ($row = $stmtTags->fetch()) {
                        $selected = in_array($row['id_tags'], explode(',', $jeu['tags'])) ? 'selected' : '';
                        echo "<option value='{$row['id_tags']}' {$selected}>{$row['name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="platform" class="form-label text-light">Plateformes</label>
                <select class="form-control" id="platform" name="platform[]" multiple>
                    <?php
                    $stmtPlatforms = $pdo->query("SELECT id_platform, name FROM platform");
                    while ($row = $stmtPlatforms->fetch()) {
                        $selected = in_array($row['id_platform'], explode(',', $jeu['platforms'])) ? 'selected' : '';
                        echo "<option value='{$row['id_platform']}' {$selected}>{$row['name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label text-light">Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
