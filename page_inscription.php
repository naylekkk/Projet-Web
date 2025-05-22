<html>
    <head>
        <title>Page d'inscription</title>    
        <link rel="stylesheet" type="text/css" href="banque-image.css">    
    </head>
    <body>
        <?php
            require '../base_de_donnees/config.php';
            include("en-tete.html");
        ?>
        <form method = "POST" action="../base_de_donnees/inscription.php">
            <fieldset class = "fieldset_inscription">
                <legend class="legend_inscription">Inscription</legend>
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
    </body>
</html>