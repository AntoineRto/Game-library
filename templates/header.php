<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header Carousel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header>
    <!-- Carrousel -->
    <div id="gameCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <!-- Première image -->
            <div class="carousel-item active">
                <img src="../public/assets/img/banner1.png" class="d-block w-100" alt="Jeux vidéo populaires">
                <div class="carousel-caption d-none d-md-block">
                    <h2>Bienvenue dans notre Librairie de Jeux</h2>
                    <p>Découvrez les meilleurs jeux vidéo dans notre collection.</p>
                </div>
            </div>
            <!-- Deuxième image -->
            <div class="carousel-item">
                <img src="../public/assets/img/banner1.png" class="d-block w-100" alt="Nouvelle collection">
                <div class="carousel-caption d-none d-md-block">
                    <h2>LOREM IPSUM</h2>
                    <p>LOREM IPSUM</p>
                </div>
            </div>
            <!-- Troisième image -->
            <div class="carousel-item">
                <img src="../public/assets/img/banner1.png" class="d-block w-100" alt="Offres exclusives">
                <div class="carousel-caption d-none d-md-block">
                    <h2>LOREM IPSUM</h2>
                    <p>LOREM IPSUM</p>
                </div>
            </div>
        </div>
        <!-- Contrôles du carrousel -->
        <button class="carousel-control-prev" type="button" data-bs-target="#gameCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Précédent</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#gameCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Suivant</span>
        </button>
    </div>
</header>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
