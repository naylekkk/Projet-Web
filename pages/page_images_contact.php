<?php
//affiche les errueurs php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//html en utf 8
header('Content-Type: text/html; charset=utf-8');

//on inclut le fichier de connexion à la base de données
require '../base_de_donnees/config.php';
session_start();


//vérification que l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header('Location: page_connexion.php');
    exit;
}

//on récupère l'id du contact à afficher
$id_contact = isset($_GET['id']) ? intval($_GET['id']) : 0;

// on récupère le pseudo du contact 
$rqt = mysqli_prepare($connexion, "SELECT username FROM users WHERE id = ?");
mysqli_stmt_bind_param($rqt, "i", $id_contact);
mysqli_stmt_execute($rqt);
$res = mysqli_stmt_get_result($rqt);
$contact = mysqli_fetch_assoc($res);
mysqli_stmt_close($rqt);

//si aucun contact , on affiche le message d'erreur 
if (!$contact) {
    echo "Utilisateur introuvable.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Images de <?php echo htmlspecialchars($contact['username']); ?></title>
    <link rel="stylesheet" href="banque-image.css">
</head>
<body>
<?php include("../include_php/en-tete.php"); ?>

<div class="barre-laterale">
    <?php echo "Bienvenue " . htmlspecialchars($_SESSION['login']) . " !"; ?>
    <hr>
    <nav>
        <a href="page_images.php" class="item-lateral">Accueil</a><br>
        <?php include("../include_php/deconnexion.php");?>
        <a class="item-lateral" href="#">🔍 Recherche</a><br>
        <a class="item-lateral" href="page_depot.php">📤 Dépôt</a>
    </nav>
    <hr>
    <?php include("../include_php/contacts.php") ?>
</div>

<div class="contenu-principal">
    <h2>Images de <?php echo htmlspecialchars($contact['username']); ?></h2>

    <?php
    //on récupère les images postées par le contact 
    $rqt = mysqli_prepare($connexion, "SELECT id, nom, descriptif, chemin_fichier FROM images WHERE auteur_id = ? ORDER BY date_enregistrement DESC");
    mysqli_stmt_bind_param($rqt, "i", $id_contact);
    mysqli_stmt_execute($rqt);
    $result = mysqli_stmt_get_result($rqt);

    // si le contact a posté des images
    if (mysqli_num_rows($result) > 0) {
        while($image = mysqli_fetch_assoc($result)) {
            echo "<div class='carte-image'>";
            //image cliquable
            echo "<a href='page_commentaire_image.php?id=" . $image['id'] . "'>";
            echo "<img src='" . htmlspecialchars($image['chemin_fichier']) . "' alt='" . htmlspecialchars($image['nom']) . "' class='miniature'>";
            echo "</a>";
            //titre et description de l'image
            echo "<h3>" . htmlspecialchars($image['nom']) . "</h3>";
            echo "<p>" . htmlspecialchars($image['descriptif']) . "</p>";
            echo "</div>";
        }
    } else {
        //aucun résultat sur ce contact
        echo "<p>Ce contact n'a pas encore déposé d'image.</p>";
    }
    ?>
</div>
</body>
</html>
