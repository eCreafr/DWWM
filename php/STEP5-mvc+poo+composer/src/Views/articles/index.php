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
    use App\Helpers\ImageHelper;

    // Boucle sur tous les articles récupérés par le contrôleur
    foreach ($articles as $article):
        // Récupère le chemin de l'image uploadée ou utilise picsum par défaut
        $imagePath = ImageHelper::getArticleImagePath($article['id'], $article['image'] ?? null);
        $imageUrl = $imagePath ? BASE_URL . $imagePath : "https://picsum.photos/300/150?random={$article['id']}";
    ?>
        <div class="col-lg-3 col-md-4 col-sm-6 p-3">
            <div class="card card-custom">
                <!-- Image uploadée ou image aléatoire via Lorem Picsum -->
                <div class="position-relative">
                    <img src="<?= $imageUrl ?>" style="height: 100px;"
                        class="card-img-top object-fit-cover"
                        alt="<?= htmlspecialchars($article['titre']); ?>">

                    <!-- Date de publication en overlay -->
                    <p class="position-absolute bottom-0 start-0 m-2" style="z-index: 10;">
                        <mark><?= htmlspecialchars($article['date_publication']); ?></mark>
                    </p>
                </div>

                <div class="card-body d-flex flex-column">

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

                    <!-- Contenu tronqué de l'article (200 caractères max par php, 3 lignes max par css card-text ) -->
                    <p class="card-text">
                        <?= htmlspecialchars(StringHelper::truncate(StringHelper::stripHtml($article['contenu']), 200)); ?>
                    </p>

                    <?php if (\App\Helpers\AuthHelper::isUser() or \App\Helpers\AuthHelper::isAdmin()):  ?>

                        <!-- Lien vers l'article complet avec URL SEO-friendly -->
                        <?php
                        $url = UrlHelper::createArticleUrl(
                            $article['id'],
                            $article['titre'],
                            $article['score'] ?? null
                        );
                        ?>
                        <div class="btn btn-primary rounded-pill mt-auto d-flex justify-content-between align-items-center">
                            <a class="text-white text-decoration-none flex-grow-1" href="<?= BASE_URL ?>/<?= $url; ?>">
                                Voir l'article complet
                            </a>

                            <!-- Boutons d'action : Modifier et Supprimer (visible pour les admins uniquement) -->
                            <?php if (\App\Helpers\AuthHelper::isAdmin()): ?>
                                <div class="d-flex gap-2">
                                    <!-- Bouton Modifier -->
                                    <a role="button"
                                        href="<?= BASE_URL ?>/edit.html?id=<?= $article['id']; ?>"
                                        class="btn btn-link bg-light  rounded-pill ">
                                        <img src="<?= BASE_URL ?>/assets/img/pencil-square.svg" alt="Modifier">
                                    </a>

                                    <!-- Bouton Supprimer (ouvre une modal de confirmation) -->
                                    <button type="button"
                                        class="btn btn-link rounded-pill bg-light"
                                        data-bs-toggle="modal"
                                        data-bs-target="#Modal<?= $article['id']; ?>">
                                        <img src="<?= BASE_URL ?>/assets/img/trash.svg" alt="Supprimer" style="filter: invert(27%) sepia(98%) saturate(7466%) hue-rotate(357deg) brightness(91%) contrast(115);">
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>

                        <a class="btn btn-primary rounded-pill mt-auto" href="<?= BASE_URL ?>/login.html">
                            Voir l'article complet </a>(connexion requise)
                    <?php endif; ?>
                </div>
            </div>

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