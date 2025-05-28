<?php
// active l'affichage des erreurs php dans le navigateur 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// html en utf8
header('Content-Type: text/html; charset=utf-8');
require '../base_de_donnees/config.php';
session_start();

// utilisateur connecté ?
if (!isset($_SESSION['id'])) {
    header('Location: page_connexion.php');
    exit;
}

//initialisations des variables
$images_trouvees = [];
$termes_recherche = "";

//il faut que le formulaire soit non vite
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["recherche"])) {
    // On récupère la chaîne de caractère pour la mettre en minuscule pour éviter les erreurs
    $termes_recherche = strtolower(trim($_POST["recherche"]));
    // on sépare les mots clés avec les virgules
    $mots = array_filter(explode(",", $termes_recherche));
    $conditions = []; // conditions SQL pour WHERE
    $params = []; // valeurs des paramètres LIKE
    $types = ""; 

    // pour chaque mot-clé , on prépare la recherche avec LIKE
    foreach ($mots as $mot) {
        $mot = trim($mot);
        $conditions[] = "descriptif LIKE ?";
        $params[] = "%" . $mot . "%"; // on recherche dans le descriptif
        $types .= "s";
    }

    // si il y au moins un mot clé valide
    if (!empty($conditions)) {
        // requête sql dynamique 
        // on mets aussi un score de pertinence
        $sql = "SELECT i.*, u.username,
                (" . implode(" + ", array_fill(0, count($conditions), "CASE WHEN descriptif LIKE ? THEN 1 ELSE 0 END")) . ") AS pertinence
                FROM images i
                JOIN users u ON i.auteur_id = u.id
                WHERE " . implode(" OR ", $conditions) . "
                ORDER BY pertinence DESC, date_enregistrement DESC";
        

        $rqt = mysqli_prepare($connexion, $sql);
        // on reprends les paramètres pour la partie SELECT (pertinence) + WHERE
        $params_total = array_merge($params, $params);  // pour les LIKE et les pertinences

        // on lie tous les paramètres avec bon nombre de "s"
        mysqli_stmt_bind_param($rqt, str_repeat("s", count($params_total)), ...$params_total);
        mysqli_stmt_execute($rqt);
        $result = mysqli_stmt_get_result($rqt);
        // on stocke toutes les images trouvées .
        while ($image = mysqli_fetch_assoc($result)) {
            $images_trouvees[] = $image;
        }
        mysqli_stmt_close($rqt);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche d'images</title>
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
    <h2>🔍 Recherche d'images</h2>
    <form method="POST">
        <input type="text" name="recherche" placeholder="Entrez des mots-clés séparés par des virgules" value="<?php echo htmlspecialchars($termes_recherche); ?>" required>
        <input type="submit" value="Rechercher">
    </form>

    <?php 
        
        if (!empty($images_trouvees)) {
        echo "<h3>Résultats :</h3>";
        foreach ($images_trouvees as $img) {
            echo "<div class='carte-image'>";
            //image cliquable menant à la page commentaire
            echo "<a href='page_commentaire_image.php?id=" . $img['id'] . "'>";
            echo "<img src='" . htmlspecialchars($img['chemin_fichier']) . "' alt='" . htmlspecialchars($img['nom']) . "' class='miniature'>";
            echo "</a>";
            echo "<h3>" . htmlspecialchars($img['nom']) . "</h3>";
            echo "<p>" . htmlspecialchars($img['descriptif']) . "</p>";
            echo "<p>👤 <a href='page_images_contact.php?id=" . $img['auteur_id'] . "'>" . htmlspecialchars($img['username']) . "</a></p>";
            echo "</div>";
        }

        // aucun resultat trouvé 
    } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "<p>Aucune image trouvée.</p>";
    }
    ?>
</div>
</body>
</html>
