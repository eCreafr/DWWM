<?php
// ============================================================
// PAGE DE TRAITEMENT D'AJOUT - ADDPOST.PHP
// ============================================================
// Cette page reçoit les données du formulaire add.php
// et les insère dans la base de données.
// Elle gère aussi l'upload d'image et l'ajout de match.
// ============================================================

// Inclusion de la connexion à la base de données
include('db.php');

// Récupération de toutes les données envoyées par le formulaire
// $_POST est un tableau contenant toutes les valeurs des champs du formulaire
$postData = $_POST;

// ============================================================
// VALIDATION DU FORMULAIRE - PARTIE ARTICLE
// ============================================================
// On vérifie que tous les champs obligatoires sont remplis

if (
    empty($postData['titre'])                        // Si le titre est vide
    || empty($postData['contenu'])                   // OU si le contenu est vide
    || empty($postData['auteur'])                    // OU si l'auteur est vide
    || trim(strip_tags($postData['titre'])) === ''   // OU si le titre ne contient que des espaces/balises HTML
    || trim(strip_tags($postData['contenu'])) === '' // OU si le contenu ne contient que des espaces/balises HTML
    || trim(strip_tags($postData['auteur'])) === ''  // OU si l'auteur ne contient que des espaces/balises HTML
) {
    // Si au moins une condition est vraie, on affiche un message d'erreur et on arrête tout
    echo 'Il faut un titre + un contenu + un auteur pour soumettre le formulaire.';
    return; // Arrête l'exécution du script
}

// ============================================================
// NETTOYAGE DES DONNÉES DE L'ARTICLE
// ============================================================
// trim() : supprime les espaces en début et fin de chaîne
// strip_tags() : supprime toutes les balises HTML (sécurité contre les failles XSS)

$titre = trim(strip_tags($postData['titre']));
$contenu = trim(strip_tags($postData['contenu']));
$auteur = trim(strip_tags($postData['auteur']));

// Initialiser match_id à 0 par défaut (0 = pas de match associé)
$match_id = 0;

// ============================================================
// TRAITEMENT OPTIONNEL : AJOUT D'UN MATCH
// ============================================================
// On vérifie si l'utilisateur a coché la case "ajouterMatch"

if (isset($postData['ajouterMatch']) && $postData['ajouterMatch'] === 'on') {

    // Si la case est cochée, on vérifie que tous les champs du match sont remplis
    if (
        empty($postData['equipe1'])
        || empty($postData['equipe2'])
        || empty($postData['score'])
        || empty($postData['lieu'])
    ) {
        echo 'Tous les champs du match doivent être remplis.';
        return;
    }

    // Nettoyage des données du match
    $equipe1 = trim(strip_tags($postData['equipe1']));
    $equipe2 = trim(strip_tags($postData['equipe2']));
    $score = trim(strip_tags($postData['score']));
    $lieu = trim(strip_tags($postData['lieu']));
    $resume = trim(strip_tags($postData['resume'])); // facultatif

    // ------------------------------------------------------------
    // ÉTAPE 1 : Insertion du match dans la base de données
    // ------------------------------------------------------------
    // On doit d'abord insérer le match pour obtenir son ID
    // qui sera utilisé comme clé étrangère dans l'article

    // Préparation de la requête d'insertion (sécurité contre les injections SQL)
    // Les :equipe1, :equipe2, etc. sont des placeholders (marqueurs)
    $insertMatch = $mysqlClient->prepare('
        INSERT INTO s2_resultats_sportifs(equipe1, equipe2, score, lieu, resume, date_match)
        VALUES (:equipe1, :equipe2, :score, :lieu, :resume, :date_match)
    ');

    // Exécution de la requête avec les vraies valeurs
    // On remplace les placeholders par les vraies données
    $insertMatch->execute([
        'equipe1' => $equipe1,
        'equipe2' => $equipe2,
        'score' => $score,
        'lieu' => $lieu,
        'date_match' => date('Y-m-d'),  // Date du jour au format Année-Mois-Jour
        'resume' => $resume,
    ]);

    // Récupération de l'ID du match qui vient d'être inséré
    // lastInsertId() retourne le dernier ID auto-incrémenté
    $match_id = $mysqlClient->lastInsertId();
}

// ============================================================
// ÉTAPE 2 : Insertion de l'article dans la base de données
// ============================================================
// Maintenant qu'on a potentiellement un match_id, on peut insérer l'article

$insertArticle = $mysqlClient->prepare('
    INSERT INTO s2_articles_presse(titre, contenu, auteur, date_publication, match_id)
    VALUES (:titre, :contenu, :auteur, :date_publication, :match_id)
');

$insertArticle->execute([
    'titre' => $titre,
    'contenu' => $contenu,
    'auteur' => $auteur,
    'date_publication' => date('Y-m-d'),  // Date du jour
    'match_id' => $match_id,              // 0 si pas de match, sinon l'ID du match
]);

// Récupération de l'ID de l'article qui vient d'être ajouté
// Cet ID sera utilisé pour renommer l'image
$article_id = $mysqlClient->lastInsertId();


// ============================================================
// TRAITEMENT DE L'UPLOAD D'IMAGE
// ============================================================
// $_FILES est un tableau contenant les informations sur les fichiers uploadés

// Vérification qu'une image a été uploadée et qu'il n'y a pas d'erreur
// $_FILES['image']['error'] == 0 signifie : pas d'erreur lors de l'upload
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

    // Vérification du type MIME du fichier (sécurité)
    // mime_content_type() détecte le vrai type du fichier (pas juste l'extension)
    $typeMime = mime_content_type($_FILES['image']['tmp_name']);

    // On accepte uniquement les images JPEG
    if ($typeMime === "image/jpeg") {

        // Déplacement du fichier temporaire vers le dossier img/
        // avec renommage selon l'ID de l'article
        $dossier = "img/";
        $nomFichier = $article_id . ".jpg";  // Ex: si article_id = 5, le fichier sera 5.jpg

        // move_uploaded_file() déplace le fichier du dossier temporaire vers sa destination finale
        // $_FILES['image']['tmp_name'] = chemin temporaire du fichier uploadé
        move_uploaded_file($_FILES['image']['tmp_name'], $dossier . $nomFichier);

    } else {
        echo "Erreur : seul le format JPG est accepté.";
    }
}

// ============================================================
// PRÉPARATION DE L'AFFICHAGE DES INFORMATIONS DU MATCH
// ============================================================

$matchInfo = ''; // Variable vide par défaut

// Si un match a été ajouté (match_id > 0), on prépare son affichage en HTML
if ($match_id > 0) {
    $matchInfo = "<div class='card mt-3'>
        <div class='card-body'>
            <h5 class='card-title'>Informations du match</h5>
            <p>{$equipe1} vs {$equipe2}</p>
            <p>Score : {$score}</p>
            <p>Lieu : {$lieu}</p>
            " . ($resume ? "<p>Commentaire : {$resume}</p>" : "") . "
        </div>
    </div>";
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article ajouté</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="container">
        <h1>Article ajouté avec succès !</h1>

        <!-- Affichage des informations de l'article ajouté -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo $titre; ?></h5>
                <p class="card-text"><b>Par <?php echo $auteur; ?></b></p>
                <p class="card-text"><?php echo $contenu; ?></p>

                <!-- Affichage de l'image uploadée -->
                <img src="img/<?php echo $article_id; ?>.jpg" alt="<?php echo $titre; ?>" class="img-fluid">
            </div>
        </div>

        <!-- Affichage des informations du match (si un match a été ajouté) -->
        <?php echo $matchInfo; ?>
    </div>

    <!-- Bouton pour retourner à la page d'accueil -->
    <a class="btn btn-primary" role="button" href="./">RETOUR</a>
</body>

</html>
