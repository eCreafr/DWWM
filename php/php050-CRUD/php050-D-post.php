<?php
/**
 * ============================================
 * DELETE (CRUD) - EXÉCUTION DE LA SUPPRESSION
 * ============================================
 *
 * Ce fichier supprime définitivement un article de la base de données
 * C'est la partie "DELETE" du CRUD (Create, Read, Update, Delete)
 *
 * IMPORTANT : La suppression est IRRÉVERSIBLE !
 * Ce fichier ne doit être appelé qu'après confirmation de l'utilisateur (depuis php050-D.php)
 */

// ============================================
// 1. CONNEXION À LA BASE DE DONNÉES
// ============================================
include('php050-connect.php');

// ============================================
// 2. RÉCUPÉRATION DES DONNÉES DU FORMULAIRE
// ============================================
$postData = $_POST; // $_POST contient l'ID envoyé par le formulaire de confirmation

// ============================================
// 3. VALIDATION DE L'ID
// ============================================
// Vérification que l'ID est présent et valide
if (!isset($postData['id']) || !is_numeric($postData['id'])) {
    // Si pas d'ID ou ID invalide, on arrête tout
    echo 'Il faut un identifiant valide pour supprimer un article.';
    return; // Arrête l'exécution du script
}

// ============================================
// 4. SUPPRESSION EN BASE DE DONNÉES
// ============================================
// On utilise une REQUÊTE PRÉPARÉE pour éviter les injections SQL

// DELETE FROM : supprime des enregistrements dans la table
// WHERE id = :id : CRUCIAL ! Sans WHERE, TOUS les articles seraient supprimés !
$deleteArticleStatement = $mysqlClient->prepare('DELETE FROM s2_articles_presse WHERE id = :id');

// Exécution de la requête avec l'ID de l'article à supprimer
// (int) : conversion forcée en entier pour plus de sécurité
$deleteArticleStatement->execute([
    'id' => (int)$postData['id'],
]);

// À ce stade, l'article a été supprimé de la base de données
// Il n'y a pas de retour en arrière possible !

?>


<!-- ============================================
     5. AFFICHAGE DE LA PAGE DE CONFIRMATION
     ============================================ -->

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article supprimé</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Message de confirmation simple -->
    <p>C'est supprimé !</p> <br>

    <!-- Bouton pour retourner à la liste des articles -->
    <a class="btn btn-primary" role="button" href="php050-R.php">RETOUR</a>
</body>

</html>

<!--
NOTES PÉDAGOGIQUES :

1. COMMANDE SQL DELETE :
   - DELETE FROM nom_table : supprime des lignes dans une table
   - WHERE : INDISPENSABLE pour cibler un article précis
   - Sans WHERE, TOUTE la table serait vidée !

   Exemple catastrophique :
   DELETE FROM s2_articles_presse
   → TOUS les articles sont supprimés !

   Avec WHERE :
   DELETE FROM s2_articles_presse WHERE id = 5
   → Seul l'article n°5 est supprimé ✓

2. DIFFÉRENCE ENTRE LES FICHIERS DELETE :
   - php050-D.php : DEMANDE confirmation ("Êtes-vous sûr ?")
   - php050-D-post.php : EXÉCUTE la suppression (ce fichier)

3. POURQUOI 2 FICHIERS ?
   - Sécurité : évite les suppressions accidentelles
   - Bonne pratique : toujours demander confirmation avant une action irréversible
   - Flux : GET (affiche confirmation) → POST (exécute suppression)

4. VALIDATION DE L'ID :
   - isset() : vérifie que la variable existe
   - is_numeric() : vérifie que c'est un nombre
   - (int) : conversion forcée en entier (sécurité supplémentaire)

5. REQUÊTES PRÉPARÉES :
   - prepare() : prépare la requête avec un placeholder (:id)
   - execute() : remplace :id par la valeur réelle et exécute
   - Protection contre les injections SQL

6. SUPPRESSION DÉFINITIVE :
   - DELETE supprime physiquement les données de la BDD
   - Aucun moyen de récupérer l'article après (sauf sauvegarde)
   - Alternative : "soft delete" (marquer comme supprimé sans effacer)

7. FLUX COMPLET DE LA SUPPRESSION :
   Liste des articles (php050-R.php)
        ↓ Clic sur "Supprimer"
   Page de confirmation (php050-D.php?id=5)
        ↓ Clic sur "Oui !"
   Exécution de la suppression (php050-D-post.php)
        ↓ DELETE FROM ... WHERE id = 5
   Message de confirmation
        ↓ Clic sur "RETOUR"
   Retour à la liste (sans l'article supprimé)

8. AMÉLIORATION POSSIBLE :
   - Vérifier que l'article existe avant de le supprimer
   - Afficher le titre de l'article supprimé dans la confirmation
   - Implémenter un système de "soft delete" (suppression logique) :
     * Ajouter une colonne "deleted_at" dans la table
     * UPDATE au lieu de DELETE pour marquer comme supprimé
     * Permet de récupérer les articles supprimés par erreur
   - Logger les suppressions (qui, quand, quoi) pour l'audit
   - Rediriger automatiquement vers php050-R.php au lieu d'afficher une page
   - Ajouter un système de permissions (qui a le droit de supprimer ?)
   - Utiliser AJAX pour supprimer sans recharger la page

9. SÉCURITÉ :
   - Toujours valider les données utilisateur
   - Toujours utiliser des requêtes préparées
   - Toujours demander confirmation pour les actions destructives
   - En production, ajouter un token CSRF pour empêcher les attaques

10. RÉCAPITULATIF CRUD COMPLET :
    CREATE (C) : php050-C.php + php050-C-post.php → INSERT INTO
    READ (R)   : php050-R.php → SELECT
    UPDATE (U) : php050-U.php + php050-U-post.php → UPDATE ... WHERE
    DELETE (D) : php050-D.php + php050-D-post.php → DELETE FROM ... WHERE
-->
