<?php
$host = 'localhost';
$db   = 'l2info';
$user = 'l2info';
$pass = 'l2info';
$connexion = mysqli_connect($host, $user, $pass, $db);

// Lire le fichier SQL
$sql = file_get_contents('base-site.sql');
if ($sql === false) {
    die('Erreur : impossible de lire le fichier SQL.');
}

// Exécuter les requêtes (si le fichier contient plusieurs requêtes séparées par ;)
$queries = explode(';', $sql);
foreach ($queries as $query) {
    $query = trim($query);  //trim() permet d'enlever les espaces inutiles
    if (!empty($query)) { //Si la requête n'est pas vide
        if (!mysqli_query($connexion, $query)) { //Si la requête n'est pas valide, on envoie une erreur.
            echo "Erreur : " . mysqli_error($connexion) . "<br>";
        }
    }
}

?>
