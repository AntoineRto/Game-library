<?php 

    include 'header.php'; ?>
    
    
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register</title>
    </head>
    <body>
    
    
    <form action="/register" method="post">
        <label>Nom d'utilisateur :</label>
        <input type="text" name="username" required>
        <label>Email :</label>
        <input type="email" name="email" required>
        <label>Mot de passe :</label>
        <input type="password" name="password" required>
        <button type="submit">S'inscrire</button>
    </form>
    
    
    <p>Déjà inscrit ? <a href="/login">Se connecter</a></p>
    



    </body>
    </html>


    <?php include 'footer.php'; ?>
