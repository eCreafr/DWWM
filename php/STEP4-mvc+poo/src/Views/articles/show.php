<!-- Vue d'affichage d'un article complet -->
<!-- Cette vue affiche tous les détails d'un article spécifique -->

<?php

use App\Helpers\StringHelper;
?>

<div class="container my-5">

    <!-- Bouton retour vers la liste des articles -->
    <div class="mb-4">
        <a class="btn btn-primary" role="button" href="<?= BASE_URL ?>/home.html">
            &larr; Retour à la liste
        </a>
    </div>

    <!-- Article complet -->
    <article>
        <!-- Image illustrative de l'article -->
        <img src="https://picsum.photos/800/400?random=<?= $article['id']; ?>"
            class="img-fluid mb-4"
            alt="<?= htmlspecialchars($article['titre']); ?>">

        <!-- En-tête de l'article -->
        <header class="mb-4">
            <!-- Date de publication -->
            <p class="text-muted">
                <mark><?= htmlspecialchars($article['date_publication']); ?></mark>
            </p>

            <!-- Titre de l'article -->
            <h1><?= htmlspecialchars($article['titre']); ?></h1>

            <!-- Auteur de l'article -->
            <?php if (!empty($article['auteur'])): ?>
                <p class="text-muted">
                    Par <?= htmlspecialchars($article['auteur']); ?>
                </p>
            <?php endif; ?>
        </header>

        <!-- Informations sur le match si disponibles -->
        <?php if (!empty($article['match_id']) && $article['match_id'] > 0): ?>
            <div class="alert alert-info mb-4" role="alert">
                <h4 class="alert-heading">Résultat du match</h4>

                <!-- Affichage des équipes -->
                <?php if (!empty($article['equipe1']) && !empty($article['equipe2'])): ?>
                    <p class="mb-2">
                        <strong>
                            <?= htmlspecialchars($article['equipe1']); ?>
                            vs
                            <?= htmlspecialchars($article['equipe2']); ?>
                        </strong>
                    </p>
                <?php endif; ?>

                <!-- Affichage du score -->
                <?php if (!empty($article['score'])): ?>
                    <p class="mb-2">
                        <strong style="color:#FF0000">
                            Score : <?= htmlspecialchars($article['score']); ?>
                        </strong>
                    </p>
                <?php endif; ?>

                <!-- Affichage du lieu -->
                <?php if (!empty($article['lieu'])): ?>
                    <p class="mb-2">
                        Lieu : <?= htmlspecialchars($article['lieu']); ?>
                    </p>
                <?php endif; ?>

                <!-- Affichage du résumé du match -->
                <?php if (!empty($article['resume'])): ?>
                    <hr>
                    <p class="mb-0">
                        <?= nl2br(htmlspecialchars($article['resume'])); ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Contenu complet de l'article -->
        <div class="article-content">
            <!-- nl2br() convertit les sauts de ligne en balises <br> -->
            <?= nl2br(htmlspecialchars($article['contenu'])); ?>
        </div>

    </article>

    <!-- Actions sur l'article (modifier, supprimer) -->
    <div class="mt-5 text-center">
        <!-- Bouton Modifier -->
        <a class="btn btn-success rounded-pill"
            role="button"
            href="<?= BASE_URL ?>/edit.html?id=<?= $article['id']; ?>">
            <img src="<?= BASE_URL ?>/assets/img/pencil-square.svg" alt="">
            Modifier cet article
        </a>

        <!-- Bouton Supprimer (ouvre une modal) -->
        <button type="button"
            class="btn btn-danger rounded-pill"
            data-bs-toggle="modal"
            data-bs-target="#deleteModal">
            <img src="<?= BASE_URL ?>/assets/img/trash.svg" alt="">
            Supprimer cet article
        </button>
    </div>

</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= BASE_URL ?>/deletepost.html" method="POST">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteModalLabel">
                        Confirmer la suppression
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Champ caché avec l'ID de l'article -->
                    <input type="hidden" name="id" value="<?= $article['id']; ?>">

                    <p>Êtes-vous sûr de vouloir supprimer définitivement cet article ?</p>

                    <!-- Option pour supprimer aussi le match -->
                    <?php if (!empty($article['match_id']) && $article['match_id'] > 0): ?>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="supprimerMatch" name="supprimerMatch">
                            <label class="form-check-label" for="supprimerMatch">
                                Supprimer également les résultats du match associé
                            </label>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </div>
            </form>
        </div>
    </div>
</div>
