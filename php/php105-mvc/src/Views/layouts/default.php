<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Titre de la page (défini par chaque contrôleur) -->
    <title><?= htmlspecialchars($title ?? "L'Actu avec Sport 2000"); ?></title>

    <!-- Meta description pour le SEO -->
    <meta name="description" content="<?= htmlspecialchars($metadesc ?? "L'actualité sportive"); ?>">

    <!-- Fichiers CSS Bootstrap et personnalisés -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap.min.css">
</head>

<body>
    <!-- En-tête du site avec le logo/titre -->
    <header>
        <a href="<?= BASE_URL ?>/home.html">L'EQUIPE</a>
    </header>

    <!-- Contenu principal de la page -->
    <!-- La variable $content est définie par la méthode render() du contrôleur -->
    <!-- Elle contient le HTML généré par la vue spécifique (ex: articles/index.php) -->
    <?= $content ?>

    <!-- Pied de page du site -->
    <footer class="mt-5 py-3 text-center">
        Pied de page du site | Mentions légales |
        <a class="btn btn-danger" role="button" href="<?= BASE_URL ?>/home.html">revenir à l'accueil</a>
    </footer>

    <!-- JavaScript Bootstrap -->
    <script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.js"></script>
</body>

</html>
