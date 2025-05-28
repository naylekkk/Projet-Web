<?php
    if(!isset($_SESSION['login'])){
        header("Location: page_connexion.php");
        exit;
    }
    
    if (isset($_GET['action']) && $_GET['action'] === 'logout') {
        session_unset();
        session_destroy();
        header('Location: page_connexion.php');
        exit();
    }
    echo "<a href='?action=logout' class='item-lateral'>Déconnexion</a><br>";
?>