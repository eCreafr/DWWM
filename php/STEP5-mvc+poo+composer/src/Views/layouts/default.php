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

    <!-- TinyMCE Editor (chargé conditionnellement si nécessaire) -->
    <?php if (isset($useTinyMCE) && $useTinyMCE === true): ?>
        <script src="https://cdn.tiny.cloud/1/<?= TINYMCE_API_KEY ?>/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <?php endif; ?>
</head>

<body>
    <!-- En-tête du site avec le logo/titre -->
    <header class="d-flex justify-content-between align-items-center px-4 py-3">
        <a href="<?= BASE_URL ?>/home.html">L'ACTU DWWM</a>

        <!-- Menu d'authentification -->
        <div class="auth-menu d-flex align-items-center gap-2">
            <?php if (\App\Helpers\AuthHelper::isLoggedIn()): ?>
                <!-- Si l'utilisateur est connecté, afficher son nom et le bouton de déconnexion -->
                <span class="me-2">
                    Bonjour,
                    <strong><?= htmlspecialchars(\App\Helpers\AuthHelper::getCurrentUserName()); ?></strong>
                    <?php if (\App\Helpers\AuthHelper::isAdmin()): ?>
                        <span class="badge bg-danger">ADMIN</span>
                    <?php else: ?>
                        <span class="badge bg-primary">USER</span>
                    <?php endif; ?>
                </span>
                <!-- Lien vers les paramètres 2FA -->
                <a href="<?= BASE_URL ?>/2fa-settings.html"
                    class="btn btn-sm btn-outline-secondary rounded-pill"
                    title="Authentification 2FA">
                    Sécurité 2FA
                </a>
                <!-- Bouton de déconnexion avec style Bootstrap -->
                <a href="<?= BASE_URL ?>/logout.html"
                    class="btn btn-sm rounded-pill text-light"
                    title="Se déconnecter">
                    Deconnexion
                </a>
            <?php else: ?>
                <!-- Si l'utilisateur n'est pas connecté, afficher les boutons de connexion et inscription -->
                <a href="<?= BASE_URL ?>/register.html"
                    class="btn btn-sm btn-success rounded-pill"
                    title="Créer un compte">
                    ✨ Inscription
                </a>
                <a href="<?= BASE_URL ?>/login.html"
                    class="btn btn-sm rounded-pill text-light"
                    title="Se connecter">
                    🔐 Connexion
                </a>
            <?php endif; ?>
        </div>
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