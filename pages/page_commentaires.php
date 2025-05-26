<?php
    header('Content-Type: text/html; charset=utf-8');
    require '../base_de_donnees/config.php';

    session_start();

    if(!isset($_SESSION['login'])){
        header("Location: page_connexion.php");
        exit;
    }

    $m_erreur = "";
    $m_succes="";

    if (isset($_GET['action']) && $_GET['action'] === 'logout') {
        session_unset();
        session_destroy();
        header('Location: page_connexion.php');
        exit();
    }

    $mon_id = $_SESSION['id'];
    $id_contact  = $_GET['id'];
    $rqt = mysqli_prepare($connexion, "
        SELECT c.*, 
            u_auteur.username AS auteur_nom,
            u_dest.username AS destinataire_nom
        FROM commentaires c
        JOIN users u_auteur ON c.auteur_id = u_auteur.id
        JOIN users u_dest ON c.destinataire_id = u_dest.id
        WHERE (
            (c.auteur_id = ? AND c.destinataire_id = ?)
            OR
            (c.auteur_id = ? AND c.destinataire_id = ?)
        )
        ORDER BY c.date_commentaire ASC
    ");
    mysqli_stmt_bind_param($rqt, "iiii", $id_contact, $mon_id, $mon_id, $id_contact);
    mysqli_stmt_execute($rqt);
    $result = mysqli_stmt_get_result($rqt);

?>


<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Commentaires - Banque d’Images</title>
        <link rel="icon" href="../data/logo.png">
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
                <a href="page_images.php" class="item-lateral">Accueil</a><br>
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
            <?php            
                while ($row = mysqli_fetch_assoc($result)){
                    echo "<div class=conteneur-commentaires>";
                        echo "<strong>". $row['auteur_nom'] . " à " . $row['destinataire_nom'] . "</strong><br>";
                    echo"<div class='commentaires'>";
                        echo "Commentaire : <br>" . htmlspecialchars($row['commentaire'], ENT_QUOTES, 'UTF-8') . "<br>";
                    echo "</div>";
                    echo "Date du commentaire : ".$row['date_commentaire']."</p>";
                    echo "</div>";
                }
            ?> 
        </div>
    </body>
</html>