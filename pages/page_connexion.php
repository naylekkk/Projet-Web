<?php
    session_start();
    
    //debug
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    //Config.php pour se connecter à la base de données
    require '../base_de_donnees/config.php';

    //On initialise un message d'erreur et un message de succès pour savoir si la requête est réussie ou non
    $m_erreur = "";
    $m_succes="";

    //Si le serveur reçoit une requête POST
    if($_SERVER["REQUEST_METHOD"]  == "POST"){


        $login =  htmlspecialchars(trim($_POST['login'])); //htmlspecialchar permet d'éviter les injections de script malveillants
        //trim supprime les espaces inutiles
        $mot_de_passe = htmlspecialchars($_POST['mdp']);

        //On prépare la requête qui permet de récupérer l'id et le mot de passe haché du login
        $rqt = mysqli_prepare($connexion, "SELECT id, password FROM users WHERE username = ?");
        mysqli_stmt_bind_param($rqt, "s", $login); //On affecte le paramètre login de type string (s) 
        mysqli_stmt_execute($rqt); //exécution
        $result = mysqli_stmt_get_result($rqt); //On récupère le résultat

        //fetch_assoc transforme $result en un tableau 2D (1D pour cette fois car il n'y a  qu'un seul utilisateur)
        if($row = mysqli_fetch_assoc($result)){
            if(password_verify($mot_de_passe, $row['password'])){ //On vérifie si le haché correspond au mot de passe entrée
                $_SESSION['login'] = $login; //Le login de la session = le login en entrée
                $_SESSION['id'] = $row['id'];  //l'id de la session = l'id en entrée (récupéré grâce à la requête sql)
                $m_succes = "Connexion en cours..."; 
                header("refresh:2;url=page_images.php"); //Redirection de 2 secondes vers l'url page_images.php
            } else {
                $m_erreur = "Mot de passe incorrect !";
            }
        } else {
            $m_erreur = "Login inexistant, inscrivez-vous.";
        }

        mysqli_stmt_close($rqt);
    }
?>

<!DOCTYPE html> 
<html>
    <head>
        <title>Connexion - Banque d'Images</title>
        <link rel="icon" href="../data/logo.png">
        <link rel="stylesheet" type="text/css" href="banque-image.css"> 
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    </head>
    <body>
        <?php
            include("../include_php/en-tete.php"); //On inclut l'en-tête
        ?>
        <p class="bienvenue">Bienvenue sur L'Œil d'Or. Connectez-vous pour accéder à vos images.</p>
        <div class="contenu-sans-barre-lateral">
            <form method = "POST" action = "" autocomplete="off">
                <fieldset class = "fieldset_connexion">
                    <legend>Connexion</legend>
                    <div class="fieldset_div"> 
                        <label for="login">Login </label>
                        <input  type="text" name="login" placeholder="Login">
                    </div>
                    <div class="fieldset_div">
                        <label for="mdp">Mot de Passe </label>
                        <div class = "mdp">
                            <input type="password" id="password" name="mdp" placeholder="Mot de Passe" autocomplete="new-password">
                            <button type="button" onclick="togglePassword()" class = "bouton_mdp">
                                <img src=../data/icones/oeilferme.png id="oeil">
                            </button>
                        </div>
                    </div>

                    <?php if (!empty($m_erreur)) echo "<p class='erreur'>$m_erreur</p>";  //message d'erreur en rouge?>
                    <?php if (!empty($m_succes)) echo "<p class='succes'>$m_succes</p>"; //message de succès en vert?>

                    <div class = "submit">
                        <button type="submit" id="bouton_submit">Se connecter</button>
                    </div>
                </fieldset>
            </form>
            <p class = "inscription">
                Vous n'êtes pas inscrits ? 
                <?php
                    echo '<a href="page_inscription.php">Inscrivez-vous.</a>'
                ?>
            </p>
        </div>
        <script>
            function togglePassword(){ //Permet d'afficher/masquer le mot de passe quand on veut se connecter
                const input  = document.getElementById("password");
                const oeil = document.getElementById("oeil");
                if(input.type == "password"){
                    input.type = "text";
                    oeil.src = "../data/icones/oeil.png"
                }
                else{
                    input.type = "password";
                    oeil.src = "../data/icones/oeilferme.png"
                }
            }
        </script>
    </body>
</html>