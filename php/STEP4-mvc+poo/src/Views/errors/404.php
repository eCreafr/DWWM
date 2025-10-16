<!-- Vue de la page d'erreur 404 -->
<!-- Cette vue s'affiche quand une page demandée n'existe pas -->

<div class="container my-5">

    <div class="text-center">

        <!-- Icône ou emoji d'erreur -->
        <h1 class="display-1 text-danger">404</h1>

        <!-- Message d'erreur principal -->
        <h2 class="mb-4">Oups... Vous êtes perdu ?</h2>

        <!-- Message explicatif -->
        <div class="alert alert-danger" role="alert">
            <p class="mb-0">
                La page que vous recherchez n'existe pas ou a été déplacée.
            </p>
        </div>

        <!-- Bouton pour retourner à l'accueil -->
        <div class="mt-4">
            <a href="<?= BASE_URL ?>/home.html" class="btn btn-primary btn-lg">
                Retour à l'accueil
            </a>
        </div>

    </div>

</div>
