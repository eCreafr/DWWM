<?php


/* //debug routeur

echo "URL complète: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "Dernier segment: " . basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) . "<br>";
echo "Paramètres GET: ";
print_r($_GET);
echo "<br>"; */



require __DIR__ . '/controllers/recipe-controller.php';

// Instancier le contrôleur
$controller = new RecipeController();

// Obtenir l'URL complète
$fullPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Extraire le dernier segment de l'URL
$urlPath = basename($fullPath);
// Traiter en fonction du dernier segment
if ('' === $urlPath || 'public' === $urlPath || 'index.php' === $urlPath) {
    $controller->browseRecipes();
} elseif ('show' === $urlPath) {
    if (isset($_GET['id'])) {
        $controller->showRecipe($_GET['id']);
    } else {
        header('HTTP/1.1 404 Not Found');
        echo "ID de recette manquant";
    }
} elseif ('add' === $urlPath) {
    $controller->addRecipe();
} elseif ('delete' === $urlPath) {
    if (isset($_GET['id'])) {
        $controller->deleteRecipe($_GET['id']);
    } else {
        header('HTTP/1.1 404 Not Found');
        echo "ID de recette manquant";
    }
} else {
    header('HTTP/1.1 404 Not Found');
    echo "Page non trouvée: $urlPath";
}
