<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mon profil</title>
    <?php include 'navbar.php'; ?>
    <link rel="stylesheet" href="../public/assets/css/editProfile.css">
</head>
<body>
    
    <div class="container mt-5">
        <h1 class="text-light">Modifier mon profil</h1>

        <!-- Message d'erreur -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <!-- Message de succès -->
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success_message) ?>
            </div>
        <?php endif; ?>

        <form action="/game-library/public/edit-profile" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Adresse email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe (laisser vide si inchangé)</label>
                <input type="password" class="form-control" id="password" name="password">
                <small class="text-muted">Doit contenir au moins 6 caractères.</small>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            <a href="/game-library/public/profile" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
