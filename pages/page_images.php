<?php
    header('Content-Type: text/html; charset=utf-8');
    require '../base_de_donnees/config.php';
    session_start();

    if(!isset($_SESSION['login'])){
        header("Location: page_connexion.php");
        exit;
    }
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
                <?php include("../include_php/deconnexion.php");?>
                <a class="item-lateral" href="#">🔍 Recherche</a><br>
                <a class="item-lateral" href="page_depot.php">📤 Dépôt</a>
            </nav>
            <hr>
            <?php include("../include_php/contacts.php")?>
        </div>

        
<div class="contenu-principal">
    <h2>Mes images déposées 📷</h2>

    <?php
        $id_utilisateur = $_SESSION['id'];
        $rqt = mysqli_prepare($connexion, "SELECT id, nom, descriptif, chemin_fichier FROM images WHERE auteur_id = ? ORDER BY date_enregistrement DESC");
        mysqli_stmt_bind_param($rqt, "i", $id_utilisateur);
        mysqli_stmt_execute($rqt);
        $resultat = mysqli_stmt_get_result($rqt);

        if (mysqli_num_rows($resultat) > 0) {
            while($image = mysqli_fetch_assoc($resultat)) {
                echo "<div class='carte-image'>";
                echo "<a href='page_commentaire_image.php?id=" . $image['id'] . "'>";
                echo "<img src='" . htmlspecialchars($image['chemin_fichier']) . "' alt='" . htmlspecialchars($image['nom']) . "' class='miniature'>";
                echo "</a>";
                echo "<h3>" . htmlspecialchars($image['nom']) . "</h3>";
                echo "<p>" . htmlspecialchars($image['descriptif']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>Vous n'avez encore déposé aucune image.</p>";
        }

        mysqli_stmt_close($rqt);
    ?>
</div>
</body>
</html>