<?php
// Définir une liste d'utilisateurs avec des informations personnelles
$users = [
    [
        'full_name' => 'Mickaël Andrieu',
        'email' => 'mickael.andrieu@exemple.com',
        'age' => 34,
    ],
    [
        'full_name' => 'Mathieu Nebra',
        'email' => 'mathieu.nebra@exemple.com',
        'age' => 34,
    ],
    [
        'full_name' => 'Laurène Castor',
        'email' => 'laurene.castor@exemple.com',
        'age' => 28,
    ],
];

// Définir une liste de recettes avec leurs détails
$recipes = [
    [
        'title' => 'Cassoulet',
        'recipe' => 'Etape 1 : des haricots blancs !',
        'author' => 'mickael.andrieu@exemple.com',
        'is_enabled' => true,
    ],
    [
        'title' => 'Couscous',
        'recipe' => 'Etape 1 : de la semoule',
        'author' => 'mickael.kelso@exemple.com',
        'is_enabled' => true,
    ],
      [
        'title' => 'Pizza',
        'recipe' => 'Etape 1 : de la semoule',
        'author' => 'mickael.andrieu@exemple.com',
        'is_enabled' => true,
    ],
      [
        'title' => 'Couscous',
        'recipe' => 'Etape 1 : de la semoule',
        'author' => 'laurene.castor@exemple.com',
        'is_enabled' => true,
    ],  [
        'title' => 'Lasagnes',
        'recipe' => 'Etape 1 : de la semoule',
        'author' => 'mickael.andrieu@exemple.com',
        'is_enabled' => true,
    ], [
        'title' => 'Pot au feu',
        'recipe' => 'Etape 1 : de la semoule',
        'author' => 'laurene.castor@exemple.com',
        'is_enabled' => true,
    ],
     [
        'title' => 'Schroumpfade',
        'recipe' => 'Etape 1 : de la semoule',
        'author' => 'mickael.andrieu@exemple.com',
        'is_enabled' => false,
    ],
    // D'autres recettes suivent...
];

// Fonction pour afficher l'auteur d'une recette
function displayAuthor(string $authorEmail, array $users): string
{
    // Parcourt la liste des utilisateurs pour trouver un email correspondant
    foreach ($users as $user) {
        // Vérifie si l'email correspond
        if ($authorEmail === $user['email']) {
            // Retourne le nom complet et l'âge sous forme d'une chaîne
            return $user['full_name'] . '(' . $user['age'] . ' ans)';
        }
    }
    // Si aucun auteur n'est trouvé, retourne une chaîne vide
    return 'recette issue de la communauté';
}

// Fonction pour vérifier si une recette est valide
function isValidRecipe(array $recipe): bool
{
    // Vérifie si la clé 'is_enabled' existe dans la recette
    if (array_key_exists('is_enabled', $recipe)) {
        $isEnabled = $recipe['is_enabled']; // Récupère la valeur de 'is_enabled'
    } else {
        $isEnabled = false; // Définit par défaut à false si la clé est absente
    }

    return $isEnabled; // Retourne true ou false selon la valeur
}

// Fonction pour récupérer uniquement les recettes valides
function getRecipes(array $recipes): array
{
    $valid_recipes = []; // Initialisation d'un tableau pour stocker les recettes valides

    foreach ($recipes as $recipe) {
        // Ajoute la recette au tableau si elle est valide
        if (isValidRecipe($recipe)) {
            $valid_recipes[] = $recipe;
        }
    }

    return $valid_recipes; // Retourne le tableau des recettes valides
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Recettes de cuisine</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Conteneur principal -->
    <div class="container text-center">
        <h1>Liste des recettes de cuisine</h1>
    </div>
    <div class="container d-flex flex-wrap gap-5 justify-content-center">
        <?php 
        // Parcourt toutes les recettes valides et les affiche sous forme de cartes
        foreach (getRecipes($recipes) as $recipe) : ?>
            <article class="card my-3 p-5 bg-secondary bg-opacity-10 w-25">
                <!-- Affiche le titre de la recette -->
                <h3><?php echo $recipe['title']; ?></h3>
                <!-- Affiche les instructions de la recette -->
                <div><?php echo $recipe['recipe']; ?></div>
                <!-- Affiche l'auteur de la recette en utilisant displayAuthor -->
                <i><?php echo displayAuthor($recipe['author'], $users); ?></i>
            </article>
        <?php endforeach ?>
    </div>
</body>
</html>
