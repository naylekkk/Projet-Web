<?php
// html encodé en utf8
    header('Content-Type: text/html; charset=utf-8');
    require '../base_de_donnees/config.php';
    session_start();
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Accueil - Banque d’Images</title>
        <link rel="icon" href="../data/logo.png">
        <link rel="stylesheet" href="banque-image.css">
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    </head>

    <body>
        <?php include("../include_php/en-tete.php"); ?>
        <!-- Barre latérale gauche -->
        <div class="barre-laterale">
            <?php echo "Bienvenue " . htmlspecialchars($_SESSION['login']) . " !";?>
            <hr>
            <nav>
                <a href="page_images.php" class="bouton-lateral">Accueil</a><br>
            <?php include("../include_php/deconnexion.php");//On inclut le bouton déconnexion et son fonctionnement?>
                <a class="item-lateral" href="page_recherche.php">🔍 Recherche</a><br>
                <a class="item-lateral" href="page_depot.php">📤 Dépôt</a>
            </nav>
            <hr>
        <?php include("../include_php/contacts.php") //On inclut la liste de contacts?>
        </div>

        
<div class="contenu-principal">
    <h2>Mes images déposées 📷</h2>

    <?php
    //on recup l'id de l'utilisateur 
        $id_utilisateur = $_SESSION['id'];
        // on prépare la requète pour récupérer toutes les images postées par cet utilisateur 
        $rqt = mysqli_prepare($connexion, "SELECT id, nom, descriptif, chemin_fichier FROM images WHERE auteur_id = ? ORDER BY date_enregistrement DESC");
        mysqli_stmt_bind_param($rqt, "i", $id_utilisateur);
        mysqli_stmt_execute($rqt);
        $resultat = mysqli_stmt_get_result($rqt);
        // on vérifie si il y a des résultats
        if (mysqli_num_rows($resultat) > 0) {
            while($image = mysqli_fetch_assoc($resultat)) {
                echo "<div class='carte-image'>";
                // l'image cliquable mene à sa page commentaire
                echo "<a href='page_commentaire_image.php?id=" . $image['id'] . "'>";
                echo "<img src='" . htmlspecialchars($image['chemin_fichier']) . "' alt='" . htmlspecialchars($image['nom']) . "' class='miniature'>";
                echo "</a>";
                //affichage du titre  de l'image et de sa description
                echo "<h3>" . htmlspecialchars($image['nom']) . "</h3>";
                echo "<p>" . htmlspecialchars($image['descriptif']) . "</p>";
                echo "</div>";
            }
        } else {
            //si il n'y a pas d'image déposé 
            echo "<p>Vous n'avez encore déposé aucune image.</p>";
        }
        // fermeture de la requete
        mysqli_stmt_close($rqt);
    ?>
</div>
</body>
</html>