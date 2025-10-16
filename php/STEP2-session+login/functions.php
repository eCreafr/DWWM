<?php
/*
 * Fichier de fonctions utilitaires - functions.php
 * =================================================
 *
 * Ce fichier regroupe toutes les fonctions réutilisables du projet.
 * Il contient également le code de gestion de la déconnexion et
 * les requêtes SQL pour récupérer les données de la base.
 *
 * 🎯 Principe : Éviter de dupliquer du code en centralisant la logique métier
 */


// ============================================================================
// FONCTIONS UTILITAIRES POUR LES MATCHS
// ============================================================================

/*
 * Vérifie si un match est actif
 *
 * @param array $match Le tableau associatif représentant un match
 * @return bool True si le match est actif, False sinon
 */
function isActiveMatch(array $match): bool
{
    return $match['is_active'];
}



/*
 * Récupère uniquement les matchs actifs depuis un tableau de matchs
 *
 * Cette fonction filtre un tableau de matchs pour ne garder que ceux qui sont actifs.
 *
 * @param array $matches Tableau de tous les matchs
 * @return array Tableau contenant uniquement les matchs actifs
 */
function getActiveMatches(array $matches): array
{
    // Initialisation d'un tableau vide pour stocker les matchs actifs
    $active_matches = [];

    // Parcours de tous les matchs
    foreach ($matches as $match) {
        // Si le match est actif (fonction isActiveMatch)
        if (isActiveMatch($match)) {
            // On l'ajoute au tableau des matchs actifs
            $active_matches[] = $match;
        }
    }

    // Retour du tableau filtré
    return $active_matches;
}


// ============================================================================
// GESTION DES COOKIES
// ============================================================================

/*
 * Création d'un cookie pour stocker le prénom de l'utilisateur
 *
 * Les cookies sont des petites données stockées dans le navigateur de l'utilisateur.
 * Ils permettent de garder des informations entre les visites.
 *
 * Note : Dans ce projet, cette fonctionnalité semble peu utilisée.
 */
if (!empty($_REQUEST['firstname'])) {
    /*
     * setcookie() crée un cookie avec :
     * - 'firstname' : nom du cookie
     * - htmlspecialchars() : sécurise la valeur contre les injections XSS
     * - time() + (7 * 24 * 60 * 60) : expire dans 7 jours
     * - "/" : accessible sur tout le site
     */
    setcookie('firstname', htmlspecialchars($_REQUEST['firstname']), time() + (7 * 24 * 60 * 60), "/");
}

// ============================================================================
// FONCTION DE REDIRECTION
// ============================================================================

/*
 * Redirige l'utilisateur vers une autre page
 *
 * Cette fonction est utilisée après le login pour rediriger vers l'accueil.
 * Le type de retour "never" indique que la fonction arrête l'exécution du script.
 *
 * @param string $url L'URL de destination
 * @return never La fonction ne retourne jamais (elle arrête le script)
 */
function redirectToUrl(string $url): never
{
    // Envoi d'un header HTTP pour rediriger le navigateur
    header("Location: {$url}");
    // Arrêt du script pour éviter d'exécuter du code après la redirection
    exit();
}


// ============================================================================
// GESTION DE LA DÉCONNEXION
// ============================================================================

/*
 * Déconnexion de l'utilisateur
 *
 * Ce code vérifie si l'utilisateur a cliqué sur le lien "Déconnexion".
 * Si oui, il détruit la session et redirige vers l'accueil.
 *
 * Déclenchement : Lorsque l'URL contient ?action=logout
 */
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Démarrage de la session (nécessaire pour la manipuler)
    session_start();

    // Suppression de toutes les variables de session (LOGGED_USER, etc.)
    session_unset();

    // Destruction complète de la session
    session_destroy();

    // Redirection vers la page d'accueil
    header("Location:index.php");
    exit();
}




// ============================================================================
// REQUÊTES SQL - RÉCUPÉRATION DES DONNÉES
// ============================================================================

/*
 * Récupération de tous les articles de presse avec leurs informations de match
 *
 * Cette requête utilise une jointure LEFT JOIN pour combiner :
 * - Les articles de la table s2_articles_presse
 * - Les résultats sportifs de la table s2_resultats_sportifs
 *
 * LEFT JOIN : Garde tous les articles, même ceux sans match associé
 */
$sqlQuery = '
    SELECT a.id, a.titre, a.contenu, a.date_publication, r.score, r.lieu
    FROM s2_articles_presse a
    LEFT JOIN s2_resultats_sportifs r ON a.match_id = r.id
    ORDER BY `a`.`date_publication`
    DESC; ';

// Préparation de la requête (sécurise contre les injections SQL)
$newsbdd = $mysqlClient->prepare($sqlQuery);

// Exécution de la requête
$newsbdd->execute();

// Récupération de tous les résultats sous forme de tableau associatif
$news = $newsbdd->fetchAll();


/*
 * Récupération de tous les abonnés
 *
 * Cette requête simple récupère tous les utilisateurs enregistrés.
 * Elle est utilisée pour vérifier les identifiants lors de la connexion.
 */
$sqlQueryAbo = 'SELECT * FROM `s2_abonnes`';
$abobdd = $mysqlClient->prepare($sqlQueryAbo);
$abobdd->execute();
// $abonnes contient un tableau avec tous les abonnés
$abonnes = $abobdd->fetchAll();

/*
 * Récupération de tous les matchs sportifs
 *
 * Cette requête récupère l'ensemble des résultats sportifs
 * pour les afficher sur la page dédiée.
 */
$sqlQueryMatches = '
SELECT * FROM `s2_resultats_sportifs`; ';
$bddMatch = $mysqlClient->prepare($sqlQueryMatches);
$bddMatch->execute();
// $Matches contient un tableau avec tous les matchs
$Matches = $bddMatch->fetchAll();
