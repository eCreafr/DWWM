<?php

// controleur avec des fonctions en POO


class RecipeController
{
    private $recipeModel;

    public function __construct()
    {
        require_once __DIR__ . '/../models/recipe-model.php';
        $this->recipeModel = new RecipeModel();
    }

    public function browseRecipes(): void
    {
        // Fetching all recipes
        $recipes = $this->recipeModel->getAllRecipes();
        // Generate the web page
        require __DIR__ . '/../views/indexRecipe.php';
    }

    public function showRecipe(int $id): void
    {
        // Input parameter validation
        $id = filter_var($id, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
        if (false === $id || null === $id) {
            die("Wrong input parameter");
        }

        // Fetching a recipe
        $recipe = $this->recipeModel->getRecipeById($id);

        // Result check
        if (!isset($recipe['title']) || !isset($recipe['description'])) {
            header("HTTP/1.1 404 Not Found");
            die("Recipe not found");
        }

        // Generate the web page
        require __DIR__ . '/../views/showRecipe.php';
    }


    public function addRecipe(): void
    {
        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            $recipe = array_map('trim', $_POST);

            // Validate data
            $errors = $this->validateRecipe($recipe);

            // Save the recipe
            if (empty($errors)) {
                $this->recipeModel->saveRecipe($recipe);
                header('Location: /git/php/php120-kit-mvc-poo/public/');
            }
        }

        // Generate the web page
        require __DIR__ . '/../views/form.php';
    }

    public function validateRecipe(array $recipe): array
    {
        if (empty($recipe['title'])) {
            $errors[] = 'The title is required';
        }
        if (empty($recipe['description'])) {
            $errors[] = 'The description is required';
        }
        if (!empty($recipe['title']) && strlen($recipe['title']) > 255) {
            $errors[] = 'The title should be less than 255 characters';
        }

        return $errors ?? [];
    }


    public function deleteRecipe(int $id): void
    {
        // Input parameter validation
        $id = filter_var($id, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
        if (false === $id || null === $id) {
            die("Wrong input parameter");
        }

        // Vérifiez d'abord si la recette existe
        $recipe = $this->recipeModel->getRecipeById($id);

        // Result check
        if (!isset($recipe['title']) || !isset($recipe['description'])) {
            header("HTTP/1.1 404 Not Found");
            die("Recipe not found");
        }

        // Supprimer la recette
        $success = $this->recipeModel->deleteRecipe($id);

        if ($success) {
            // Rediriger vers la page d'accueil après suppression
            header('Location: /git/php/php120-kit-mvc-poo/public/');
        } else {
            // Gérer l'erreur de suppression
            die("Error deleting recipe");
        }
    }
}
