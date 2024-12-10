<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar Example</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Navbar CSS -->
    <link href="../public/assets/css/navbar.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand" href="/game-library/public/">
            <img src="../public/assets/img/controllerLogo.jpg" alt="Logo">
        </a>

        <!-- Barre de recherche -->
        <form class="d-flex flex-grow-1 mx-3">
            <input class="form-control search-bar" type="search" placeholder="Rechercher" aria-label="Search">
        </form>

        <!-- Liens à droite -->
        <div class="d-flex align-items-center">
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Bouton Déconnexion pour les utilisateurs connectés -->
                <a class="nav-link me-3" href="/game-library/public/logout">Déconnexion</a>
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        More
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <?php if ($_SESSION['role'] >= 3): ?>
                            <!-- Options pour les administrateurs -->
                            <li><a class="dropdown-item" href="#">Ajouter un jeu</a></li>
                        <?php endif; ?>
                        <?php if ($_SESSION['role'] >= 2): ?>
                            <!-- Options pour les utilisateurs -->
                            <li><a class="dropdown-item" href="#">Profil</a></li>
                            <li><a class="dropdown-item" href="#">Ma Collection</a></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item" href="#">Aide</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <!-- Boutons Connexion et Inscription pour les invités -->
                <a class="nav-link me-3" href="/game-library/public/login">Connexion</a>
                <a class="nav-link me-3" href="/game-library/public/register">Inscription</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Bootstrap JS (pour dropdown) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
