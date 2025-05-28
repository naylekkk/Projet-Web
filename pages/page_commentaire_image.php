<?php
//les trois lignes sont là pour afficher les erreurs php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//html codé en utf8
header('Content-Type: text/html; charset=utf-8');

//connexion à la base de données
require '../base_de_donnees/config.php';
session_start();

//utilisateur  connecté ? sinon page connexion 
if (!isset($_SESSION['id'])) {
    header('Location: page_connexion.php');
    exit;
}

//on récupère l'id de l'image
$id_image = isset($_GET['id']) ? intval($_GET['id']) : 0;
$m_erreur = "";
$m_succes = "";

// quand le formulaire est soumis , on enregistre le commentaire 
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["commentaire"])) {
    $commentaire = htmlspecialchars($_POST["commentaire"]);
    //récupère l'id de l'utilisateur qui à posté le commentaire
    $auteur_id = $_SESSION['id'];
// quand le formulaire est soumis , on enregistre le commentaire 
    $commentaire = htmlspecialchars($_POST["commentaire"]);
    // récupère l'id de l'utilisateur qui a posté le commentaire
    $auteur_id = $_SESSION['id'];

    // On récupère l'id de l'auteur de l'image (destinataire du commentaire)
    $rqt = mysqli_prepare($connexion, "
        SELECT u.id 
        FROM users AS u, images AS i 
        WHERE u.id = i.auteur_id 
        AND i.id = ?
    ");
    mysqli_stmt_bind_param($rqt, "i", $id_image);
    mysqli_stmt_execute($rqt);
    $res = mysqli_stmt_get_result($rqt);
    $ligne = mysqli_fetch_assoc($res);
    $dest_id = $ligne['id']; // auteur de l'image
    mysqli_stmt_close($rqt);

    // Insertion du commentaire dans la base
    $rqt = mysqli_prepare($connexion, "INSERT INTO commentaires (image_id, auteur_id, commentaire, destinataire_id) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($rqt, "iisi", $id_image, $auteur_id, $commentaire, $dest_id);
    if (mysqli_stmt_execute($rqt)) {
    $m_succes = "Commentaire ajouté.";
    } else {
        $m_erreur = "Erreur lors de l'ajout du commentaire : " . mysqli_error($connexion);
    }
    mysqli_stmt_close($rqt);
}

// on récupère toutes les informations de l'image grâce à son ID
$rqt = mysqli_prepare($connexion, "SELECT * FROM images WHERE id = ?");
mysqli_stmt_bind_param($rqt, "i", $id_image);
mysqli_stmt_execute($rqt);
$result_image = mysqli_stmt_get_result($rqt);
$image = mysqli_fetch_assoc($result_image);
mysqli_stmt_close($rqt);

//quand l'image n'est pas trouvé , on affiche  un message d'erreur 
if (!$image) {
    echo "Image non trouvée.";
    exit;
}

//on récupère tout les commentaires lié à l'image
$rqt = mysqli_prepare($connexion, "SELECT c.commentaire, c.date_commentaire, u.username FROM commentaires c JOIN users u ON c.auteur_id = u.id WHERE c.image_id = ? ORDER BY c.date_commentaire ASC");
mysqli_stmt_bind_param($rqt, "i", $id_image);
mysqli_stmt_execute($rqt);
$result_commentaires = mysqli_stmt_get_result($rqt);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($image['nom']); ?> - Commentaires</title>
    <link rel="stylesheet" href="banque-image.css">
</head>
<body>
<?php include("../include_php/en-tete.php"); ?>
<div class="barre-laterale">
    <?php echo "Bienvenue " . htmlspecialchars($_SESSION['login']) . " !";?>
    <hr>
    <nav>
        <a href="page_images.php" class="item-lateral">Accueil</a><br>
        <?php include("../include_php/deconnexion.php");?>
        <a class="item-lateral" href="page_recherche.php">🔍 Recherche</a><br>
        <a class="item-lateral" href="page_depot.php">📤 Dépôt</a>
    </nav>
    <hr>
    <?php include("../include_php/contacts.php")?>
</div>

<div class="contenu-principal">
    <h2><?php echo htmlspecialchars($image['nom']); ?></h2>
    <img src="<?php echo htmlspecialchars($image['chemin_fichier']); ?>" alt="<?php echo htmlspecialchars($image['nom']); ?>" class="miniature-grande">
    <p><?php echo htmlspecialchars($image['descriptif']); ?></p>

    <h3>Commentaires</h3>
    <?php while($com = mysqli_fetch_assoc($result_commentaires)) {
        echo "<div class='commentaire'>";
        echo "<strong>" . htmlspecialchars($com['username']) . "</strong> (" . $com['date_commentaire'] . ")<br>";
        echo "<p>" . htmlspecialchars($com['commentaire']) . "</p>";
        echo "</div>";
    } ?>

    <h3>Ajouter un commentaire</h3>
    <?php if (!empty($m_erreur)) echo "<p class='erreur'>$m_erreur</p>"; ?>
    <?php if (!empty($m_succes)) echo "<p class='succes'>$m_succes</p>"; ?>
    <form method="POST">
        <textarea name="commentaire" placeholder="Votre commentaire..." required></textarea><br>
        <input type="submit" value="Envoyer">
    </form>
</div>
</body>
</html>