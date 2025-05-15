<?php
    require 'config.php';
    include("en-tete.html");
?>

<html>
    <head>
        <title>Page de connexion</title>
        <style>
            fieldset{
                border:4px solid red;
                width:300px;
                margin: 0 auto;
                margin-top:20px;
            }
            .inscription{
                text-align: center;
            }
            .log{
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
            <legend>Connexion</legend>
            <div class="log"> 
                <label for="login">Login </label>
                <input  type="text" name="login" placeholder="Login">
            </div>
            <div class="log">
                <label for="mdp">Mot de Passe </label>
                <input type="password" name="mdp" placeholder="Mot de Passe">
            </div>
        </fieldset>
        <p class = "inscription">
            Vous n'êtes pas inscrits ? 
            <?php
                echo '<a href="page_inscription.php">Inscrivez-vous.</a>'
            ?>
        </p>
    </body>
</html>