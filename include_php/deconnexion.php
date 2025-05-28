<?php
    // Vérifie l'utilisateur est connecté
    if(!isset($_SESSION['login'])){
        // Redirige l'utilisateur vers la page de connexion
        header("Location: page_connexion.php");
        exit;
    }
    if (isset($_GET['action']) && $_GET['action'] === 'logout') {
        // Supprime toutes les variables de session (efface les données stockées pour la session)
        session_unset();
        // Détruit la session en cours
        session_destroy();
        // Redirige l'utilisateur vers la page de connexion après la déconnexion
        header('Location: page_connexion.php');
        exit();
    }
    
    echo "<a href='?action=logout' class='item-lateral'>Déconnexion</a><br>";
?>
