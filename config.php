<?php
$host = 'localhost';
$db   = 'banque_images';
$user = 'votre_utilisateur_sql';
$pass = 'votre_mot_de_passe_sql'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
