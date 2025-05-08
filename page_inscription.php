<html>
    <head>
        <title>Page d'inscription</title>
        <style>
            fieldset{
                border:2px solid blue;
                width:300px;
                margin: 0 auto;
            }
            .connexion{
                text-align: center;
            }
            div{
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
            <div>
                <label for="login">Login </label>
                <input  type="text" name="login" placeholder="Login">
            </div>
            <div>
                <label for="mdp">Mot de Passe </label>
                <input type="password" name="mdp" placeholder="Mot de Passe">
            </div>
            <div>
                <label for="nom">Nom</label>
                <input type="text" name="nom" placeholder="Nom">
            </div>
            <div>
                <label for="prenom">Prénom </label>
                <input type="text" name="prenom" placeholder="Prénom">
            </div>
            <div>
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