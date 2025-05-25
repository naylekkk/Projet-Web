<?php
    session_start();

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
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Accueil - Banque d’Images</title>
        <link rel="stylesheet" href="banque-image.css">
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    </head>

    <body>
        <?php include("en-tete.html"); ?>
        <!-- Barre latérale gauche -->
        <div class="barre-laterale">
            <?php echo "Bienvenue " . htmlspecialchars($_SESSION['login']) . " !";?>
            <hr>
            <nav>
                <a href="page_images.php" class="bouton-lateral">Accueil</a><br>
                <a href="?action=logout" class="item-lateral">Déconnexion</a><br>
                <a class="item-lateral" href="#">🔍 Recherche</a><br>
                <a class="item-lateral" href="page_depot.php">📤 Dépôt</a>
            </nav>
            <hr>
            <h2>📞 Contacts</h2>
            <ul class="liste-contacts">
                <li>Alice 📸 💬</li>
                <li>Bob 📸 💬</li>
                <li>Claire 📸 💬</li>
            </ul>
        </div>

        <div class="contenu-principal">
        </div>
    </body>
</html>