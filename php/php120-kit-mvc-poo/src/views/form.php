<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Add Your Recipe</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">

</head>

<body>
    <main class="container">
        <a href="http://lateste.fr/git/php/php120-kit-mvc-poo/public/">Home</a>
        <h1>Add Your Recipe</h1>

        <?php foreach ($errors as $error) : ?>
            <p><?= $error ?></p>
        <?php endforeach; ?>

        <form action="" method="post">
            <div>
                <label for="title">Title</label>
                <input id="title" name="title" type="text" value="<?= $recipe['title'] ?? '' ?>">
            </div>
            <div>
                <label for="description">Description</label>
                <textarea id="description" name="description"><?= $recipe['description'] ?? '' ?></textarea>
            </div>
            <button>Send</button>
        </form>
    </main>
</body>

</html>