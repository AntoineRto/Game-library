<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game library</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../public/assets/css/navbar.css" rel="stylesheet">
    <!-- Icone font awesome !-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Script de recherche !-->

</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand" href="/game-library/public/">
            <img src="../public/assets/img/logolibrairie.png" alt="Logo">
        </a>

        <form id="searchForm" action="/game-library/public/search" method="GET" class="d-flex flex-grow-1 mx-3">
            <input id="searchInput" class="form-control me-2" type="search" name="query" placeholder="Rechercher un jeu..." aria-label="Search" required>
            <button class="btn btn-outline-success" type="submit">Rechercher</button>
        </form>


        

        <!-- Liens à droite -->
        <div class="d-flex align-items-center">
            <!-- Bouton Accueil (visible pour tous) -->
            <a class="nav-link me-3" href="/game-library/public/">Accueil</a>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Liens pour les utilisateurs connectés -->
                <?php if ($_SESSION['role'] >= 3): ?>
                    <!-- Administrateur : Afficher "Ajouter un jeu" -->
                    <a class="nav-link me-3" href="/game-library/public/add-game">Ajouter un jeu</a>
                <?php endif; ?>
                
                <?php if ($_SESSION['role'] >= 2): ?>
                    <!-- Utilisateur : Afficher "Profil" -->
                    <a class="nav-link me-3" href="/game-library/public/profile">Profil</a>
                <?php endif; ?>

                <?php if ($_SESSION['role'] >= 2): ?>
                    <!-- Utilisateur : Afficher "Collection" -->
                    <a class="nav-link me-3" href="/game-library/public/collection">Collection</a>
                <?php endif; ?>
                
                <!-- Bouton Déconnexion -->
                <a class="nav-link me-3" href="/game-library/public/logout">Déconnexion</a>
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
