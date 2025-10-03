<?php


// Fonction pour vérifier si un match est actif
function isActiveMatch(array $match): bool
{
    return $match['is_active'];
}




// Fonction pour récupérer uniquement les matchs actifs
function getActiveMatches(array $matches): array
{
    $active_matches = [];

    foreach ($matches as $match) {
        if (isActiveMatch($match)) {
            $active_matches[] = $match;
        }
    }

    return $active_matches;
}




// creation du cookie pour le prénom

if (!empty($_REQUEST['firstname'])) {
    // Stocker le prénom dans un cookie pour 7 jours
    setcookie('firstname', htmlspecialchars($_REQUEST['firstname']), time() + (7 * 24 * 60 * 60), "/");
}

// redirige apres le login
function redirectToUrl(string $url): never
{
    header("Location: {$url}");
    exit();
}


// deconnexion

// Vérifier si l'action est "logout"
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_start();
    session_unset(); // Supprime toutes les variables de session
    session_destroy(); // Détruit la session
    header("Location:index.php");
    exit();
}






// On récupère tout le contenu de la table sport articles
$sqlQuery = '
    SELECT a.id, a.titre, a.contenu, a.date_publication, r.score, r.lieu
    FROM s2_articles_presse a
    LEFT JOIN s2_resultats_sportifs r ON a.match_id = r.id 
    ORDER BY `a`.`date_publication` 
    DESC; ';
$newsbdd = $mysqlClient->prepare($sqlQuery);
$newsbdd->execute();
$news = $newsbdd->fetchAll();



// On récupère tous les abonnés
$sqlQueryAbo = 'SELECT * FROM `s2_abonnes`';
$abobdd = $mysqlClient->prepare($sqlQueryAbo);
$abobdd->execute();
$abonnes = $abobdd->fetchAll();

// On récupère tous les matches
$sqlQueryMatches = '
SELECT * FROM `s2_resultats_sportifs`; ';
$bddMatch = $mysqlClient->prepare($sqlQueryMatches);
$bddMatch->execute();
$Matches = $bddMatch->fetchAll();
