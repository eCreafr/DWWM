<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code news</title>
    <link rel="stylesheet" href="/css/common.css" type="text/css">
    <link rel="stylesheet" href="/css/header.css" type="text/css">
    <link rel="stylesheet" href="/css/footer.css" type="text/css">
    <?php 
    // ajout du CSS pour la vue à rendre
    if (isset($view_name)) { ?>
        <link rel="stylesheet" href="/css/<?= $view_name ?>.css" type="text/css">
    <?php } // fin du foreach d'intégration des fichiers CSS?>
</head>

<body>
    <?php require_once(__DIR__ . '/Header.php'); ?>
    <main>
        <!-- la variable $content utilisée ci-dessous est initialisé par la classe mère "Controller.php" -->
        <?= $content ?>
    </main>
    <?php require_once(__DIR__ . '/Footer.php'); ?>
</body>