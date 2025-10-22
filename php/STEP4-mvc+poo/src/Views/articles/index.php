<!-- Vue de la page d'accueil - Liste des articles -->
<!-- Cette vue affiche tous les articles sous forme de cartes -->

<div class="container d-flex flex-wrap justify-content-center">

    <!-- Bouton d'ajout d'article en haut de page -->
    <!-- Ce bouton n'est visible que pour les administrateurs -->
    <?php if (\App\Helpers\AuthHelper::isAdmin()): ?>
        <div class="p-3 m-3 col-12 text-center">
            <a class="btn btn-outline-primary rounded-pill" role="button" href="<?= BASE_URL ?>/add.html">
                <img src="<?= BASE_URL ?>/assets/img/file-earmark-plus.svg" alt="">
                AJOUTER UN NOUVEL ARTICLE
            </a>
        </div>
    <?php endif; ?>

    <?php
    // Affichage du message de succès (suppression, ajout, modification)
    // Ces messages sont stockés en session et affichés une seule fois
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success my-4 col-12">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
        // Supprime le message après affichage pour qu'il ne réapparaisse pas
        unset($_SESSION['success_message']);
    }

    // Affichage des messages d'erreur
    if (isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-danger my-4 col-12">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
        unset($_SESSION['error_message']);
    }
    ?>

    <?php
    // Importation des classes helpers pour les utiliser dans la vue
    use App\Helpers\StringHelper;
    use App\Helpers\UrlHelper;

    // Boucle sur tous les articles récupérés par le contrôleur
    foreach ($articles as $article):
    ?>
        <div class="col-lg-3 col-md-4 col-sm-6 p-3">
            <div class="card">
                <!-- Image aléatoire via Lorem Picsum, unique par article grâce à l'ID -->
                <img src="https://picsum.photos/300/150?random=<?= $article['id']; ?>"
                    class="card-img-top"
                    alt="<?= htmlspecialchars($article['titre']); ?>">

                <div class="card-body">
                    <!-- Date de publication -->
                    <p>
                        <mark><?= htmlspecialchars($article['date_publication']); ?></mark>
                    </p>

                    <!-- Titre de l'article -->
                    <h5 class="card-title"><?= htmlspecialchars($article['titre']); ?></h5>

                    <!-- Affichage du score si disponible -->
                    <?php if (!empty($article['score'])): ?>
                        <strong style="color:#FF0000">Score : <?= htmlspecialchars($article['score']); ?></strong>
                    <?php endif; ?>

                    <!-- Affichage du lieu si disponible -->
                    <?php if (!empty($article['lieu'])): ?>
                        <p>à <?= htmlspecialchars($article['lieu']); ?></p>
                    <?php endif; ?>

                    <!-- Contenu tronqué de l'article (200 caractères max) -->
                    <p class="card-text">
                        <?= htmlspecialchars(StringHelper::truncate($article['contenu'], 200)); ?>
                    </p>

                    <?php if (\App\Helpers\AuthHelper::isUser()): ?>

                        <!-- Lien vers l'article complet avec URL SEO-friendly -->
                        <?php
                        $url = UrlHelper::createArticleUrl(
                            $article['id'],
                            $article['titre'],
                            $article['score'] ?? null
                        );
                        ?>
                        <a class="btn btn-primary rounded-pill" href="<?= BASE_URL ?>/<?= $url; ?>">
                            Voir l'article complet
                        </a>
                    <?php else: ?>

                        <a class="btn btn-primary rounded-pill" href="<?= BASE_URL ?>/login.html">
                            Voir l'article complet </a>(connexion requise)
                    <?php endif; ?>
                </div>
            </div>

            <!-- Boutons d'action : Modifier et Supprimer -->
            <!-- Ces boutons ne sont visibles que pour les administrateurs -->
            <?php if (\App\Helpers\AuthHelper::isAdmin()): ?>
                <div class="col-12 text-center">
                    <!-- Bouton Modifier -->
                    <a class="btn btn-outline-success rounded-pill"
                        role="button"
                        href="<?= BASE_URL ?>/edit.html?id=<?= $article['id']; ?>">
                        <img src="<?= BASE_URL ?>/assets/img/pencil-square.svg" alt="Modifier">
                    </a>

                    <!-- Bouton Supprimer (ouvre une modal de confirmation) -->
                    <button type="button"
                        class="btn btn-outline-danger rounded-pill"
                        data-bs-toggle="modal"
                        data-bs-target="#Modal<?= $article['id']; ?>">
                        <img src="<?= BASE_URL ?>/assets/img/trash.svg" alt="Supprimer">
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Modal de confirmation de suppression pour cet article -->
        <div class="modal fade" id="Modal<?= $article['id']; ?>" tabindex="-1" aria-labelledby="ModalLabel<?= $article['id']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Formulaire de suppression (POST vers deletepost.html) -->
                    <form action="<?= BASE_URL ?>/deletepost.html" method="POST">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="ModalLabel<?= $article['id']; ?>">
                                Suppression Article #<?= $article['id']; ?>
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <!-- Champ caché contenant l'ID de l'article à supprimer -->
                            <div class="mb-3 visually-hidden">
                                <label for="id" class="form-label">Identifiant de l'article</label>
                                <input type="hidden" class="form-control" id="id" name="id" value="<?= $article['id']; ?>">
                            </div>

                            <p>Êtes-vous sûr de vouloir supprimer cet article ?</p>

                            <!-- Si l'article a un match associé, proposer de le supprimer aussi -->
                            <?php if (!empty($article['match_id']) && $article['match_id'] > 0): ?>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="supprimerMatch" name="supprimerMatch">
                                    <label class="form-check-label" for="supprimerMatch">
                                        Voulez-vous aussi supprimer les résultats du match associé ?
                                    </label>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <?php endforeach; ?>

    <!-- Bouton d'ajout d'article en bas de page -->
    <!-- Ce bouton n'est visible que pour les administrateurs -->
    <?php if (\App\Helpers\AuthHelper::isAdmin()): ?>
        <div class="p-3 m-3 col-12 text-center">
            <a class="btn btn-outline-primary rounded-pill" role="button" href="<?= BASE_URL ?>/add.html">
                <img src="<?= BASE_URL ?>/assets/img/file-earmark-plus.svg" alt="">
                AJOUTER UN NOUVEL ARTICLE
            </a>
        </div>
    <?php endif; ?>

</div>