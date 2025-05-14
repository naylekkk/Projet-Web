<?php
$host = 'localhost';
$db   = 'banque_images';
$user = 'votre_username_sql';
$pass = 'votre_mdp_sql';

try {
    // Connexion au serveur MySQL (sans base sélectionnée)
    $pdo = new PDO("mysql:host=$host;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Créer la base de données si elle n'existe pas
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

    // Sélection de la base
    $pdo->exec("USE $db");

    // Création de la table users si elle n'existe pas
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            prenom VARCHAR(50) NOT NULL,
            nom VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

} catch (PDOException $e) {
    die("Erreur de connexion ou de création de base : " . $e->getMessage());
}
?>
