<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $recipe['title'] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
</head>

<body>
    <main class="container">
        <a href="http://lateste.fr/git/php/php120-kit-mvc-poo/public/">Home</a>
        <h1><?= $recipe['title'] ?></h1>
        <p>
            <?= $recipe['description'] ?>
        </p>
    </main>
    <a href="delete?id=<?= $recipe['id'] ?>"
        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette recette?')">
        Supprimer cette recette
    </a>
</body>

</html>