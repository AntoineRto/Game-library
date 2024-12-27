<?php include 'navbar.php'; ?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../public/assets/css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>


<div class="container">
    <div class="card mt-5">
        <div class="card-body">
            <h5 class="card-title text-center">Connexion</h5>
            
            <?php if (isset($_GET['error'])): ?>
                <p class="error-message text-danger text-center"><?php echo htmlspecialchars($_GET['error']); ?></p>
            <?php endif; ?>

            <form action="../src/Handlers/login_handler.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Se connecter</button>
            </form>

            <div class="text-center mt-4">
                <p>Pas encore de compte ?</p>
                <a href="/game-library/public/register" class="btn btn-secondary">Créer un compte</a>
            </div>
        </div>
    </div>
</div>


    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

    
    
    
    
    
    

</body>
</html>