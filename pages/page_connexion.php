<html>
    <head>
        <title>Page de connexion</title>
        <link rel="stylesheet" type="text/css" href="banque-image.css">    
    </head>
    <body>
        <?php
            require '../base_de_donnees/config.php';
            include("en-tete.html");
        ?>
        <form method = "POST" action = "../base_de_donnes/connexion.php"  autocomplete="off">
            <fieldset class = "fieldset_connexion">
                <legend class="legend_connexion">Connexion</legend>
                <div class="log"> 
                    <label for="login">Login </label>
                    <input  type="text" name="login" placeholder="Login">
                </div>
                <div class="log">
                    <label for="mdp">Mot de Passe </label>
                    <div class = "mdp">
                        <input type="password" id="password" name="mdp" placeholder="Mot de Passe" autocomplete="new-password">
                        <button type="button" onclick="togglePassword()" class = "bouton_mdp">
                            <img src=../data/icones/oeilferme.png id="oeil">
                        </button>
                    </div>
                </div>
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