<?php
    require '../base_de_donnees/config.php';

    session_start();

    $m_erreur = "";
    $m_succes="";

    $mon_id = $_SESSION['id']; //id de l'utilisateur connecté
    $id_contact  = $_GET['id']; //id du contact (grâce à la requête GET)

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
    "); //Requête qui récupère tout les commentaires reçus par et envoyés au contact
    mysqli_stmt_bind_param($rqt, "iiii", $id_contact, $mon_id, $mon_id, $id_contact); //On affecte les paramètres
    mysqli_stmt_execute($rqt); //Exécution
    $result = mysqli_stmt_get_result($rqt); //On récupère  les résultats
    mysqli_stmt_close($rqt);

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
        <?php include("../include_php/en-tete.php"); //On inclut l'entête?>
        <!-- Barre latérale gauche -->
        <div class="barre-laterale">
            <?php echo "Bienvenue " . htmlspecialchars($_SESSION['login']) . " !";?>
            <hr>
            <nav>
                <a href="page_images.php" class="item-lateral">Accueil</a><br>
            <?php include("../include_php/deconnexion.php");//On inclut le bouton déconnexion et son fonctionnement?>
                <a class="item-lateral" href="page_recherche.php">🔍 Recherche</a><br>
                <a class="item-lateral" href="page_depot.php">📤 Dépôt</a>
            </nav>
            <hr>
            <?php include("../include_php/contacts.php") //On inclut la liste de contacts?>
        </div>

        <div class="contenu-principal">
            <?php            
                //On récupère le nom, le prénom et le login du contact
                $rqt = mysqli_prepare($connexion, "SELECT prenom, nom, username FROM users WHERE id = ?;");
                mysqli_stmt_bind_param($rqt, "i", $_GET['id']); //Affectation de l'id du contact
                mysqli_stmt_execute($rqt);
                $user = mysqli_stmt_get_result($rqt);
                $user_tab = mysqli_fetch_assoc($user); //On le met dans un tableau
                mysqli_stmt_close($rqt);

                //Commentaires envoyés et reçus par prenom nom (username)
                echo "<h1>Commentaires envoyés à et reçus par " . $user_tab['prenom'] . " " . $user_tab['nom'] . " (" . $user_tab['username'] .") :</h1>"; 
                $ilExisteCommentaire = false;
                
                while ($row = mysqli_fetch_assoc($result)){
                    $ilExisteCommentaire = true; //S'il y a au moins un commentaire : il existe un commentaire
                    echo "<div class=conteneur-commentaires>"; //On affiche les commentaires dans des blocs
                        echo "<strong>". $row['auteur_nom'] . " à " . $row['destinataire_nom'] . "</strong><br>";
                    echo"<div class='commentaires'>";
                        echo "Commentaire : <br>" . htmlspecialchars($row['commentaire']) . "<br>";
                    echo "</div>";
                    echo "Date du commentaire : ".$row['date_commentaire']."</p>";
                    echo "</div>";
                }
                if(!$ilExisteCommentaire){
                    //S'il n'y a pas de commentaire, on l'affiche
                    echo "<div class=conteneur-commentaires>Aucun commentaire trouvé !</div>";
                }
            ?> 
        </div>
    </body>
</html>