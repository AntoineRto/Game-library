<?php include 'header.php'; ?> <!-- Inclure l'en-tête -->
<?php include 'navbar.php'; ?> <!-- Inclure la navbar -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Titre</title>
    <link rel="stylesheet" href="../public/assets/css/home.css">
</head>
<body>
    <h1>Bibliothèque de jeux</h1>
    <p>Liste de jeux en cartes php.</p>
    
   
</body>
</html>


<?php require_once __DIR__ . '/../config/db.php';
echo "Connexion!!"
?> <!-- Test de connexion -->



<?php include 'footer.php'; ?> <!-- Inclure le pied de page -->
