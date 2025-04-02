<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>List of Recipes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
</head>

<body>
    <main class="container">
        <a href="add">Add</a>
        <h1>List of Recipes</h1>
        <ul>
            <?php foreach ($recipes as $recipe) : ?>
                <li>
                    <a href="show?id=<?= $recipe['id'] ?>">
                        <?= $recipe['title'] ?>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
    </main>
</body>

</html>