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
?>