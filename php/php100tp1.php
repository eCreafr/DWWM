<?php
// Liste des matchs avec des couleurs pour chaque équipe
$matches = [
    [
        'sport' => 'football',
        'equipe1' => ['name' => 'Paris Saint-Germain', 'color' => '#004170'],
        'equipe2' => ['name' => 'Olympique de Marseille', 'color' => '#0083ca'],
        'score' => '4-0',
        'resume' => 'Un match spectaculaire avec un doublé de Mbappé et une domination totale du PSG.',
        'is_active' => true,
    ],
    [
        'sport' => 'football',
        'equipe1' => ['name' => 'Lyon', 'color' => '#DA291C'],
        'equipe2' => ['name' => 'Monaco', 'color' => '#FFCD00'],
        'score' => '2-2',
        'resume' => 'Une rencontre équilibrée où Lacazette a marqué un but crucial pour Lyon à la 89e minute.',
        'is_active' => true,
    ],
    [
        'sport' => 'football',
        'equipe1' => ['name' => 'Lens', 'color' => '#FFD700'],
        'equipe2' => ['name' => 'Lille', 'color' => '#D40000'],
        'score' => '1-0',
        'resume' => 'Une victoire serrée pour Lens grâce à un but de Fofana à la 75e minute.',
        'is_active' => true,
    ],
    [
        'sport' => 'football',
        'equipe1' => ['name' => 'Rennes', 'color' => '#CE1126'],
        'equipe2' => ['name' => 'Nice', 'color' => '#ED1C24'],
        'score' => '0-3',
        'resume' => 'Nice a surpris Rennes avec une défense solide et trois contre-attaques fulgurantes.',
        'is_active' => false,
    ],
        [
        'sport' => 'football',
        'equipe1' => ['name' => 'Paris Saint-Germain', 'color' => '#004170'],
        'equipe2' => ['name' => 'Olympique de Marseille', 'color' => '#0083ca'],
        'score' => '0-4',
        'resume' => 'Un match spectaculaire avec une domination totale de l\'OM.',
        'is_active' => true,
    ],
    [
        'sport' => 'football',
        'equipe1' => ['name' => 'Lyon', 'color' => '#DA291C'],
        'equipe2' => ['name' => 'Monaco', 'color' => '#FFCD00'],
        'score' => '2-2',
        'resume' => 'Une rencontre équilibrée où Lacazette a marqué un but crucial pour Lyon à la 89e minute.',
        'is_active' => true,
    ],
    [
        'sport' => 'football',
        'equipe1' => ['name' => 'Lens', 'color' => '#FFD700'],
        'equipe2' => ['name' => 'Lille', 'color' => '#D40000'],
        'score' => '1-0',
        'resume' => 'Une victoire serrée pour Lens grâce à un but de Fofana à la 75e minute.',
        'is_active' => false,
    ],
    [
        'sport' => 'football',
        'equipe1' => ['name' => 'Rennes', 'color' => '#CE1126'],
        'equipe2' => ['name' => 'Nice', 'color' => '#ED1C24'],
        'score' => '0-3',
        'resume' => 'Nice a surpris Rennes avec une défense solide et trois contre-attaques fulgurantes.',
        'is_active' => true,
    ],
];

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
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Résultats des matchs de football</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: #F8F9F9;">
    <div class="container text-center">
        <img src="img/equipe.png" alt="">
        <h1>Résultats des matchs de football</h1>
    </div>
    <div class="container d-flex flex-wrap gap-5 justify-content-center">
        <?php
        // Afficher les matchs actifs
        foreach (getActiveMatches($matches) as $match) : ?>
            <article class="card my-3 p-5 bg-secondary bg-opacity-25 w-25">
                <!-- Affiche les équipes avec leur couleur -->
                <h3>
                    <span style="color: <?php echo $match['equipe1']['color']; ?>">
                        <?php echo $match['equipe1']['name']; ?>
                    </span>
                    vs
                    <span style="color: <?php echo $match['equipe2']['color']; ?>">
                        <?php echo $match['equipe2']['name']; ?>
                    </span>
                </h3>
                <!-- Affiche le score -->
                <p><strong>Score : </strong><?php echo $match['score']; ?></p>
                <!-- Affiche le résumé du match -->
                <div><?php echo $match['resume']; ?></div>
            </article>
        <?php endforeach; ?>
    </div>
</body>
</html>
