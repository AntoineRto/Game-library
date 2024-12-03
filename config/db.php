<?php

$host = 'localhost';
$dbname = 'game_library';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Active mode erreur si nécessaire
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}