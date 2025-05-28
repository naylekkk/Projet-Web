<?php
    header('Content-Type: text/html; charset=utf-8');
    require '../base_de_donnees/config.php';

    session_start();

    $m_erreur = "";
    $m_succes="";

    
    if($_SERVER["REQUEST_METHOD"]  == "POST"){
        $nom = htmlspecialchars($_POST['nom']);
        $description = htmlspecialchars($_POST['description']);
        $mots_cles = htmlspecialchars($_POST['mots_cles']);
        $fichier = $_FILES['image'];
        $auteur = $_SESSION['id']; 
        $chemin = "../data/uploads/";

        // Crée le dossier s'il n'existe pas
        if (!file_exists($chemin)) {
            if (!mkdir($chemin, 0755, true)) { //Accorde les permissions pour pouvoir déposer des images dans le répertoire
                $m_erreur = "Impossible de créer le dossier '$chemin'. Vérifie les permissions.";
            }
        }

        if($fichier['error'] == 0){
            $tmp_chemin = $fichier['tmp_name'];
            $nom_du_fichier = basename($fichier['name']); //basename enlève le chemin du  nom du fichier
            $extension = strtolower(pathinfo($nom_du_fichier,PATHINFO_EXTENSION)); 
            //pathinfo permet de découper le nom du fichier en prenant une partie, 
            //comme par exemple seulement l'extension grâce à PATHINFO_EXTENSION
            $extensions_autorisees = ['jpg','jpeg','png','webp','gif']; //On n'autorise  que ces extensions

            if(in_array($extension,$extensions_autorisees)){
                $nom_unique = pathinfo($nom_du_fichier,PATHINFO_FILENAME) . uniqid() . '.' . $extension; 
                //uniqid permet de crée un identifiant unique à l'image
                $chemin_final = $chemin . $nom_unique;

                if (!is_writable($chemin)) {  //Si on ne peut pas écrire, il faut accorder les permissions au dossier
                    $m_erreur = "Le dossier cible n'est pas accessible en écriture : $chemin";
                }
                else{
                if(move_uploaded_file($tmp_chemin,$chemin_final)){ //On déplace le fichier temporaire dans le répertoire upload
                    //On enregistre dans la base de données :
                    $rqt = mysqli_prepare($connexion,"INSERT INTO images (nom, descriptif, mots_cles, chemin_fichier, auteur_id) VALUES (?, ?, ?, ?, ?)");
                    mysqli_stmt_bind_param($rqt,"ssssi", $nom,$description,$mots_cles,$chemin_final,$auteur); //On affecte les paramètres
                    if(mysqli_stmt_execute($rqt)){ //Si bien exécuté, l'image a bien été déposée
                        $m_succes = "Image déposée avec succès !";
                    } else {
                        $m_erreur = "Erreur lors de l'enregistrement en base de données.";
                    }
                    mysqli_stmt_close($rqt);
                }

                else{
                    $m_erreur = "Erreur lors du déplacement";
                }}
            }
            else{
                $m_erreur = "Extension non autorisée !"; //Mauvaise extension
            }
        }
        else{
            $m_erreur = "Erreur lors de l'upload";
        }
    }
?>

<html>
    <head>
        <title>Dépôt - Banque d'Images</title>
        <link rel="icon" href="../data/logo.png">
        <link rel="stylesheet" type="text/css" href="banque-image.css"> 
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    </head>
    <body>
        <?php include("../include_php/en-tete.php"); //on inclut l'en-tête?>
    <div class="container-principal">

    <!-- Barre latérale gauche -->
    <div class="barre-laterale">
        <?php echo "Bienvenue " . htmlspecialchars($_SESSION['login']) . " !";?>
        <hr>
        <nav>
            <a href="page_images.php" class="item-lateral">Accueil</a><br>
            <?php include("../include_php/deconnexion.php");//On inclut le bouton déconnexion et son fonctionnement?>
            <a class="item-lateral" href="#">🔍 Recherche</a><br>
            <a class="bouton-lateral" href="page_depot.php">📤 Dépôt</a>
        </nav>
        <hr>
        <?php include("../include_php/contacts.php") //On inclut la liste de contacts?>
        </ul>
    </div>
            <div class = "contenu-principal">
                <form action="" method="POST" enctype="multipart/form-data">
                    <fieldset>
                        <legend>Dépôt d'Images</legend>
                        <div class="fieldset_div">
                            <label>Nom de l'image :</label>
                            <input type="text" name="nom" required placeholder="Nom">
                        </div>

                        <div class="fieldset_div">
                            <label>Description :</label>
                            <textarea name="description" placeholder="Description de l'image"></textarea><br>
                        </div>

                        <div class="fieldset_div">
                            <label>Mots-clés :</label>
                            <input type="text" name="mots_cles" placeholder="Séparés par des virgules (ex: ciel,bleu,nuages)"><br>
                        </div>

                        <div class="fieldset_div">
                            <label>Fichier image :</label>
                            <input type="file" name="image" accept="image/*" required>
                        </div>
                        
                        <?php if (!empty($m_erreur)) echo "<p class='erreur'>$m_erreur</p>"; ?>
                        <?php if (!empty($m_succes)) echo "<p class='succes'>$m_succes</p>"; ?>

                        <div class="submit">
                            <input type="submit" value="Déposer l'image">
                        </div>    
                </fieldset>
                </form>
            </div>
        </div>
    </body>
</html>