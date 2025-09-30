<?php
/**
 * CRÉATION D'UN ARTICLE (CREATE - CRUD)
 * Ce fichier traite les données du formulaire et insère un nouvel article en base de données
 */

// ============================================
// 1. CONNEXION À LA BASE DE DONNÉES
// ============================================
include('php050-connect.php');


// ============================================
// 2. RÉCUPÉRATION DES DONNÉES DU FORMULAIRE
// ============================================
$postData = $_POST;


// ============================================
// 3. VALIDATION DES DONNÉES
// ============================================
// On vérifie que tous les champs obligatoires sont remplis
$erreurs = [];

if (empty($postData['titre'])) {
    $erreurs[] = "Le titre est obligatoire.";
}

if (empty($postData['contenu'])) {
    $erreurs[] = "Le contenu est obligatoire.";
}

if (empty($postData['auteur'])) {
    $erreurs[] = "L'auteur est obligatoire.";
}

// Si des erreurs ont été détectées, on arrête et on affiche les erreurs
if (!empty($erreurs)) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Erreur - Formulaire incomplet</title>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="d-flex flex-column min-vh-100">
        <div class="container mt-5">
            <div class="alert alert-danger">
                <h4>Erreur lors de la soumission :</h4>
                <ul>
                    <?php foreach ($erreurs as $erreur): ?>
                        <li><?= $erreur; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <a class="btn btn-secondary" href="php050-R.php">Retour</a>
        </div>
    </body>
    </html>
    <?php
    exit(); // On arrête l'exécution du script
}


// ============================================
// 4. NETTOYAGE DES DONNÉES
// ============================================
// trim() : enlève les espaces au début et à la fin
// strip_tags() : enlève les balises HTML pour éviter les failles XSS
$titre = trim(strip_tags($postData['titre']));
$contenu = trim(strip_tags($postData['contenu']));
$auteur = trim(strip_tags($postData['auteur']));

// Vérification finale : après nettoyage, les champs ne doivent pas être vides
if ($titre === '' || $contenu === '' || $auteur === '') {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Erreur - Données invalides</title>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="d-flex flex-column min-vh-100">
        <div class="container mt-5">
            <div class="alert alert-danger">
                <h4>Erreur :</h4>
                <p>Les champs ne peuvent pas contenir uniquement des espaces ou des balises HTML.</p>
            </div>
            <a class="btn btn-secondary" href="php050-R.php">Retour</a>
        </div>
    </body>
    </html>
    <?php
    exit();
}


// ============================================
// 5. INSERTION EN BASE DE DONNÉES
// ============================================
try {
    // Préparation de la requête SQL avec des paramètres nommés (:titre, :contenu, etc.)
    // Les requêtes préparées protègent contre les injections SQL
    $requete = $mysqlClient->prepare(
        'INSERT INTO s2_articles_presse(titre, contenu, auteur, date_publication, match_id)
         VALUES (:titre, :contenu, :auteur, :date_publication, :match_id)'
    );

    // Exécution de la requête avec les valeurs réelles
    $requete->execute([
        'titre' => $titre,
        'contenu' => $contenu,
        'auteur' => $auteur,
        'date_publication' => date('Y-m-d'), // Date du jour au format YYYY-MM-DD
        'match_id' => 0, // Valeur par défaut (pourrait lier l'article à un match spécifique)
    ]);

    // Récupération de l'ID de l'article qui vient d'être inséré
    $articleId = $mysqlClient->lastInsertId();
    $datePublication = date('d/m/Y'); // Date formatée pour l'affichage

} catch (PDOException $e) {
    // En cas d'erreur lors de l'insertion, on affiche un message
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Erreur - Insertion impossible</title>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="d-flex flex-column min-vh-100">
        <div class="container mt-5">
            <div class="alert alert-danger">
                <h4>Erreur lors de l'insertion en base de données :</h4>
                <p><?= $e->getMessage(); ?></p>
            </div>
            <a class="btn btn-secondary" href="php050-R.php">Retour</a>
        </div>
    </body>
    </html>
    <?php
    exit();
}


// ============================================
// 6. AFFICHAGE DE LA CONFIRMATION
// ============================================
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article ajouté avec succès</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="container mt-5">

        <!-- Message de succès -->
        <div class="alert alert-success">
            <h4>✓ Article ajouté avec succès !</h4>
            <p>Article n°<?= $articleId; ?> créé le <?= $datePublication; ?></p>
        </div>

        <!-- Aperçu de l'article créé -->
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($titre); ?></h5>
                <p class="card-text">
                    <b>Par <?= htmlspecialchars($auteur); ?></b>
                    <span class="text-muted"> - <?= $datePublication; ?></span>
                </p>
                <p class="card-text"><?= htmlspecialchars($contenu); ?></p>
            </div>
        </div>

        <!-- Bouton de retour -->
        <a class="btn btn-primary" href="php050-R.php">Retour à la liste des articles</a>

    </div>
</body>
</html>