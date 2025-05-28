<?php
    session_start();
    
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require '../base_de_donnees/config.php';

    $m_erreur = "";
    $m_succes="";

    if($_SERVER["REQUEST_METHOD"]  == "POST"){
        $login =  htmlspecialchars(trim($_POST['login'])); //htmlspecialchar permet d'éviter les injections de script malveillants
        //trim supprime les espaces inutiles
        $mot_de_passe = htmlspecialchars($_POST['mdp']);

        $rqt = mysqli_prepare($connexion, "SELECT id, password FROM users WHERE username = ?");
        mysqli_stmt_bind_param($rqt, "s", $login);
        mysqli_stmt_execute($rqt);
        $result = $rqt->get_result();

        if($row = $result->fetch_assoc()){
            if(password_verify($mot_de_passe, $row['password'])){
                $_SESSION['login'] = $login;
                $_SESSION['id'] = $row['id'];  
                $m_succes = "Connexion en cours...";
                header("refresh:2;url=page_images.php");
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
            require '../base_de_donnees/config.php';
            include("../include_php/en-tete.php");
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

                    <?php if (!empty($m_erreur)) echo "<p class='erreur'>$m_erreur</p>"; ?>
                    <?php if (!empty($m_succes)) echo "<p class='succes'>$m_succes</p>"; ?>

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
            function togglePassword(){
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