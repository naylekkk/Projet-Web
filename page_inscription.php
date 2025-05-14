<?php
    require 'config.php'
    include("en-tete.html")
?>

<html>
    <head>
        <title>Page d'inscription</title>
        <style>
            fieldset{
                border:4px solid blue;
                width:300px;
                margin: 0 auto;
                margin-top:20px;
            }
            .connexion{
                text-align: center;
            }
            .register{
                display: flex;
                flex-direction: column;
                margin-bottom: 10px;
            }
            label {
                margin-bottom: 5px;
            }
            legend{
                font-weight:bold;
            }
        </style>
    </head>
    <body>
        <fieldset>
            <legend>Inscription</legend>
            <div class="register">
                <label for="login">Login </label>
                <input  type="text" name="login" placeholder="Login">
            </div>
            <div class="register">
                <label for="mdp">Mot de Passe </label>
                <input type="password" name="mdp" placeholder="Mot de Passe">
            </div>
            <div class="register">
                <label for="nom">Nom</label>
                <input type="text" name="nom" placeholder="Nom">
            </div>
            <div class="register">
                <label for="prenom">Prénom </label>
                <input type="text" name="prenom" placeholder="Prénom">
            </div>
            <div class="register">
                <label for="email">E-mail </label>
                <input type="text" name="email" placeholder="E-mail">
            </div>
        </fieldset>
        <p class="connexion">
            Vous avez déjà un compte ? 
            <?php
                echo '<a href="page_connexion.php">Connectez-vous.</a>'
            ?>
        </p>
    </body>
</html>