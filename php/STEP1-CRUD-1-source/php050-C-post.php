<?php
/**
 * ============================================
 * CREATE (CRUD) - TRAITEMENT DU FORMULAIRE D'AJOUT
 * ============================================
 *
 * Ce fichier reçoit les données du formulaire php050-C.php et les insère en base de données
 *
 * IMPORTANT : Les données utilisateur ne sont JAMAIS fiables !
 * Il faut toujours les valider et les nettoyer avant de les utiliser
 */

// ============================================
// 1. CONNEXION À LA BASE DE DONNÉES
// ============================================
include('php050-connect.php');


// ============================================
// 2. RÉCUPÉRATION DES DONNÉES DU FORMULAIRE
// ============================================
// $_POST est une "superglobale" PHP qui contient toutes les données envoyées par le formulaire
// On la stocke dans une variable pour plus de clarté
$postData = $_POST;

// ============================================
// 3. VALIDATION DES DONNÉES
// ============================================
// On vérifie que tous les champs obligatoires sont présents et non vides
// Cette vérification se fait en plusieurs étapes pour être plus sûr

if (
    empty($postData['titre'])                        // Vérifie si le champ 'titre' existe et n'est pas vide
    || empty($postData['contenu'])                   // Vérifie si le champ 'contenu' existe et n'est pas vide
    || empty($postData['auteur'])                    // Vérifie si le champ 'auteur' existe et n'est pas vide
    || trim(strip_tags($postData['titre'])) === ''   // Vérifie que le titre ne contient pas QUE des espaces/balises HTML
    || trim(strip_tags($postData['contenu'])) === '' // Vérifie que le contenu ne contient pas QUE des espaces/balises HTML
    || trim(strip_tags($postData['auteur'])) === ''  // Vérifie que l'auteur ne contient pas QUE des espaces/balises HTML
) {
    // Si une des conditions ci-dessus est vraie, on arrête tout et on affiche une erreur
    echo 'Il faut un titre + un contenu + un auteur pour soumettre le formulaire, sinon ça marche pas.';
    return; // Arrête l'exécution du script
}

// ============================================
// 4. NETTOYAGE DES DONNÉES (SÉCURITÉ)
// ============================================
// strip_tags() : enlève toutes les balises HTML pour éviter les attaques XSS
// trim() : enlève les espaces au début et à la fin de la chaîne
$titre = trim(strip_tags($postData['titre']));
$contenu = trim(strip_tags($postData['contenu']));
$auteur = trim(strip_tags($postData['auteur']));

// ============================================
// 5. INSERTION EN BASE DE DONNÉES
// ============================================
// On utilise une REQUÊTE PRÉPARÉE pour éviter les injections SQL
// C'est LA méthode sécurisée pour insérer des données en BDD

// prepare() : prépare la requête SQL avec des "placeholders" (:titre, :contenu, etc.)
$insertcontenu = $mysqlClient->prepare('INSERT INTO s2_articles_presse(titre, contenu, auteur, date_publication, match_id) VALUES (:titre, :contenu, :auteur, :date_publication, :match_id)');

// execute() : remplace les placeholders par les vraies valeurs et exécute la requête
$insertcontenu->execute([
    'titre' => $titre,                  // Remplace :titre par la valeur de $titre
    'contenu' => $contenu,              // Remplace :contenu par la valeur de $contenu
    'auteur' => $auteur,                // Remplace :auteur par la valeur de $auteur
    'date_publication' => date('Y-m-d'), // Génère automatiquement la date du jour au format YYYY-MM-DD (ex: 2025-10-07)
    'match_id' => 0,                     // Pour l'instant on met 0, mais on pourrait lier l'article à un match sportif spécifique
]);

?>


<!-- ============================================
     6. AFFICHAGE DE LA PAGE DE CONFIRMATION
     ============================================ -->

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ajout dans BDD</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="container">

        <h1>Article ajouté avec succès ! </h1>

        <!-- Carte Bootstrap pour afficher un aperçu de l'article créé -->
        <div class="card">
            <div class="card-body">
                <!-- <?= ?> est un raccourci pour <?php echo ?> -->
                <h5 class="card-title"><?= $titre; ?></h5>

                <!-- Affichage de l'auteur -->
                <p class="card-text"><b>Par <?php echo $auteur; ?></b> </p>

                <!-- Affichage du contenu de l'article -->
                <p class="card-text"> <?php echo $contenu; ?></p>
            </div>
        </div>

        <!-- Bouton pour retourner à la liste des articles (READ) -->
        <a class="btn btn-primary" role="button" href="php050-R.php">RETOUR</a>
    </div>
</body>

</html>

<!--
NOTES PÉDAGOGIQUES IMPORTANTES :

1. SÉCURITÉ - Pourquoi nettoyer les données ?
   - strip_tags() : empêche l'injection de code HTML/JavaScript malveillant (attaque XSS)
   - trim() : évite les espaces indésirables qui pourraient causer des bugs
   - Requêtes préparées : empêchent les injections SQL (attaque très dangereuse)

2. VALIDATION vs NETTOYAGE :
   - VALIDATION : vérifier que les données sont présentes et correctes (ligne 32-43)
   - NETTOYAGE : transformer les données pour les rendre sûres (ligne 50-52)
   - Les deux sont essentiels !

3. REQUÊTES PRÉPARÉES (ligne 61-70) :
   - Méthode sécurisée pour insérer des données en BDD
   - PDO sépare la structure SQL des données utilisateur
   - Impossible d'injecter du code SQL malveillant

   Exemple de ce qu'on évite :
   MAUVAIS : "INSERT INTO table VALUES ('" . $_POST['titre'] . "')"
   → Si l'utilisateur tape : '); DROP TABLE users; --
   → La requête devient : INSERT INTO table VALUES (''); DROP TABLE users; --')
   → CATASTROPHE : la table est supprimée !

   BON : Avec les requêtes préparées, le code malveillant est traité comme du texte, pas comme du SQL

4. FLUX DE DONNÉES :
   Formulaire (php050-C.php)
        ↓
   Réception ($_POST)
        ↓
   Validation (empty, isset)
        ↓
   Nettoyage (trim, strip_tags)
        ↓
   Insertion en BDD (requête préparée)
        ↓
   Confirmation à l'utilisateur

5. AMÉLIORATION POSSIBLE :
   - Utiliser htmlspecialchars() lors de l'affichage pour encore plus de sécurité
   - Rediriger vers php050-R.php au lieu d'afficher une page de confirmation
   - Ajouter un try/catch pour gérer les erreurs SQL
   - Valider le format de l'auteur (pas de chiffres par exemple)
-->
