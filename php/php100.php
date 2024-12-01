<?php
// Définir une liste d'utilisateurs avec des informations personnelles
$utilisateurs = [
    [
        'full_name' => 'Thomas Martin',
        'email' => 'thomas@exemple.com',
        'age' => 21,
    ],
    [
        'full_name' => 'walker Texas Ranger',
        'email' => 'sebastien@exemple.com',
        'age' => 34,
    ],
    [
        'full_name' => 'Julien Boudigues',
        'email' => 'jul@exemple.com',
        'age' => 21,
    ],
];

// Définir une liste de recettes avec leurs détails
$recettesDeMamie = [
    [
        'title' => 'Cassoulet',
        'recipe' => "1. Tremper les haricots blancs toute la nuit.  <br>
                     2. Égoutter et cuire les haricots avec un bouquet garni. <br>
                     3. Faire revenir des morceaux de canard confit et de saucisse.  <br>
                     4. Mélanger les haricots avec les viandes.  <br>
                     5. Ajouter de la tomate et du bouillon.  <br>
                     6. Laisser mijoter au four à 150°C pendant 2 heures. <br> 
                     7. Parsemer de chapelure et gratiner avant de servir. <br>",
        'author' => 'bobdylan@exemple.com',
        'is_enabled' => true,
    ],
    [
        'title' => 'Couscous royal',
        'recipe' => "1. Faire cuire les morceaux de poulet, merguez, et agneau.  <br>
                     2. Préparer un bouillon avec carottes, courgettes, navets et pois chiches.  <br>
                     3. Assaisonner avec ras el hanout, curcuma et paprika.  <br>
                     4. Cuire la semoule avec de l'eau chaude et de l'huile d'olive.  <br>
                     5. Servir les viandes sur la semoule, avec les légumes et le bouillon. <br>",
        'author' => 'thomas@exemple.com',
        'is_enabled' => true,
    ],
    [
        'title' => 'Pizza de la mama',
        'recipe' => "1. Préparer une pâte fine avec de la farine, de l'eau, de la levure et de l'huile d'olive.  <br>
                     2. Étaler une sauce tomate maison sur la pâte.  <br>
                     3. Ajouter de la mozzarella, des tranches de jambon et des olives.  <br>
                     4. Cuire au four à 250°C pendant 8 à 10 minutes.  <br>
                     5. Ajouter des feuilles de basilic frais avant de servir. <br>",
        'author' => 'sebastien@exemple.com',
        'is_enabled' => true,
    ],
    [
        'title' => 'Couscous encore',
        'recipe' => "1. Suivre les étapes classiques du couscous (voir Couscous royal).  <br>
                     2. Personnaliser avec des légumes de saison : patates douces, poireaux.  <br>
                     3. Ajouter des abricots secs pour une touche sucrée.  <br>
                     4. Servir avec une sauce piquante harissa maison. <br>",
        'author' => 'thomas@exemple.com',
        'is_enabled' => true,
    ],
    [
        'title' => 'Lasagnes team béchamel',
        'recipe' => "1. Préparer une sauce bolognaise maison avec tomates et viande hachée.  <br>
                     2. Réaliser une béchamel crémeuse.  <br>
                     3. Alterner dans un plat des couches de pâte, de bolognaise, de béchamel et de fromage râpé.  <br>
                     4. Terminer par une couche de fromage.  <br>
                     5. Cuire au four à 180°C pendant 40 minutes. <br>",
        'author' => 'jul@exemple.com',
        'is_enabled' => true,
    ],
    [
        'title' => 'Pot au feu du dimanche',
        'recipe' => "1. Plonger des morceaux de bœuf (jarret, queue) dans une grande marmite d'eau froide.  <br>
                     2. Ajouter carottes, poireaux, céleri, navets et oignons piqués de clous de girofle.  <br>
                     3. Laisser mijoter à feu doux pendant 3 heures.  <br>
                     4. Servir avec du bouillon, du gros sel et de la moutarde. <br>",
        'author' => 'sebastien@exemple.com',
        'is_enabled' => true,
    ],
    [
        'title' => 'Schroumpfade',
        'recipe' => "1. Mélanger de la farine, de l'eau et une pincée de sel pour une pâte fine. <br>
                     2. Préparer une garniture à base de fromage fondu et d'herbes aromatiques.  <br>
                     3. Former des petits galets farcis avec la pâte.  <br>
                     4. Cuire dans une poêle avec un peu de beurre jusqu'à coloration dorée. <br>",
        'author' => 'jul@exemple.com',
        'is_enabled' => false,
    ],
];



// Fonction pour afficher l'auteur d'une recette
function displayAuthor(string $authorEmail, array $utilisateurs): string
{
    // Parcourt la liste des utilisateurs pour trouver un email correspondant
    foreach ($utilisateurs as $user) {
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
function getRecipes(array $recettesDeMamie): array
{
    $valid_recipes = []; // Initialisation d'un tableau pour stocker les recettes valides

    foreach ($recettesDeMamie as $recipe) {
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
        foreach (getRecipes($recettesDeMamie) as $recipe) : ?>
            <article class="card my-3 p-5 bg-secondary bg-opacity-10 w-25">
                <!-- Affiche le titre de la recette -->
                <h3><?php echo $recipe['title']; ?></h3>
                <!-- Affiche les instructions de la recette -->
                <div><?php echo $recipe['recipe']; ?></div>
                <!-- Affiche l'auteur de la recette en utilisant displayAuthor -->
                <br><br><i><?php echo displayAuthor($recipe['author'], $utilisateurs); ?></i>
            </article>
        <?php endforeach ?>
    </div>
</body>

</html>