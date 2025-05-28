<h2>📞 Contacts</h2>
<ul class="liste-contacts">
    <?php
        //Sélectioonne tout les utilisateurs où l'identifiant n'est pas celui de l'utilisateur connecté
        $rqt = mysqli_prepare($connexion,"SELECT * FROM users WHERE id != ?;"); 
        mysqli_stmt_bind_param($rqt,"i",$_SESSION['id']); //On affecte le paramètre de l'id utilisateur à la requête
        mysqli_stmt_execute($rqt); //exécution de la requête
        $contacts = mysqli_stmt_get_result($rqt);  //On récupère les résultats (chaque ligne)
        mysqli_stmt_close($rqt);
        //Chaque ligne du résultat est affecté à row
        while ($row = mysqli_fetch_assoc($contacts)){
            //On affiche les contacts selon leur nom d'utilisateur, et les icones cliquables
            echo "<li>".$row['username']."<div class=bouton-comm-images><a class='a-bouton' href=page_commentaires.php?id=".$row['id'].">💬</a><a class='a-bouton' href=page_images.php?id=".$row['id'].">📸</a></div></li><hr>";
        }
    ?>
</ul>