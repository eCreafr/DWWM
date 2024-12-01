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
    // Détruire la session
    session_destroy();
    header("Location:php102-index.php");
    exit();
}
