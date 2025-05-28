<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require '../base_de_donnees/config.php';

    $m_erreur = "";
    $m_succes="";

    if($_SERVER["REQUEST_METHOD"]  == "POST"){
        $login =  htmlspecialchars(trim($_POST['login'])); //htmlspecialchar permet d'éviter les injections de script malveillants
        //triim supprime les espaces inutiles
        $password =  password_hash($_POST['mdp'], PASSWORD_DEFAULT); //Hashe le mot de passe en utilisant la méthhode de hashage actuel
        $nom = htmlspecialchars(trim($_POST['nom']));
        $prenom = htmlspecialchars(trim($_POST['prenom']));
        $email = htmlspecialchars(trim($_POST['email']));

        //On vérifie si l'email et le login existent déjà ou  pas
        $verif = mysqli_prepare($connexion, "SELECT id FROM users WHERE username = ? OR email = ?");
        mysqli_stmt_bind_param($verif, "ss", $login, $email);
        mysqli_stmt_execute($verif);
        mysqli_stmt_store_result($verif);

        if (mysqli_stmt_num_rows($verif) > 0) {
            $m_erreur= "Ce login ou cet email est déjà utilisé, réessayez.";
            $m_succes = "";
        }

        else{
            $rqt = mysqli_prepare($connexion,"INSERT INTO users (username, prenom, nom, email, password) VALUES (?, ?, ?, ?, ?)");
            if($rqt){
                mysqli_stmt_bind_param($rqt,"sssss",$login,$prenom,$nom,$email,$password);
                $succes = mysqli_stmt_execute($rqt);

                if($succes){
                    header("refresh:3;url=page_connexion.php");
                    $m_succes = "Inscription réussie ! Redirection vers la page connexion dans 3 secondes";
                    $m_erreur = "";
                }
                else{
                    echo "Erreur lors de l'inscription" . mysqli_stmt_error($rqt);
                }

                mysqli_stmt_close($rqt);
            }
            else{
                echo "Erreur de requête: " .mysqli_error($connexion);
            }
        }

        mysqli_stmt_close($verif);
    }
?>

<!DOCTYPE html> 
<html>
    <head>
        <title>Inscription - Banque d'Images</title> 
        <link rel="icon" href="../data/logo.png">   
        <link rel="stylesheet" type="text/css" href="banque-image.css">   
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    </head>
    <body>
        <?php include("../include_php/en-tete.php"); ?>
        <p class="bienvenue">Bienvenue sur L'Œil d'Or. Inscrivez-vous pour accéder à vos images.</p>
        <div class="contenu-sans-barre-lateral">
            <form method = "POST" action = "" autocomplete="off">
                <fieldset class = "fieldset_inscription">
                    <legend>Inscription</legend>
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
                    <div class="fieldset_div">
                        <label for="nom">Nom</label>
                        <input type="text" name="nom" placeholder="Nom">
                    </div>
                    <div class="fieldset_div">
                        <label for="prenom">Prénom </label>
                        <input type="text" name="prenom" placeholder="Prénom">
                    </div>
                    <div class="fieldset_div">
                        <label for="email">E-mail </label>
                        <input type="text" name="email" placeholder="E-mail">
                    </div>

                    <?php if (!empty($m_erreur)) echo "<p class='erreur'>$m_erreur</p>"; ?>
                    <?php if (!empty($m_succes)) echo "<p class='succes'>$m_succes</p>"; ?>

                    <div class = "submit">
                        <button type="submit" id="bouton_submit">S'inscrire</button>
                    </div>
                </fieldset>
            </form>
            <p class="connexion">
                Vous avez déjà un compte ? 
                <?php
                    echo '<a href="page_connexion.php">Connectez-vous.</a>'
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