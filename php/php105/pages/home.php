<!-- on ne met ici que la partie "main" du site entre header navbar et footer -->

<div class="container d-flex flex-wrap justify-content-center">
    <div class="p-3 m-3 col-12 text-center">
        <?= '<a class="btn btn-outline-primary rounded-pill" role="button" href="add.html">
    <img src="assets/img/file-earmark-plus.svg" alt="">
AJOUTER UN NOUVEL ARTICLE</a>'; ?></div>

    <?php

    // Message de succès qui s'affiche au retour d'une suppression d'article réussi :

    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success my-4 col-12">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
        // Supprimer le message après l'avoir affiché pour qu'il ne réapparaisse pas au rechargement
        unset($_SESSION['success_message']);
    }

    ?>

    <?php

    // On récupère tout le contenu de la table sport articles
    $sqlQuery = '
            SELECT a.id, a.titre, a.contenu, a.date_publication, a.match_id, r.score, r.lieu
            FROM s2_articles_presse a
            LEFT JOIN s2_resultats_sportifs r ON a.match_id = r.id 
            ORDER BY `a`.`date_publication` 
            DESC; ';
    $newsFraiches = $mysqlClient->prepare($sqlQuery);
    $newsFraiches->execute();
    $news = $newsFraiches->fetchAll();



    // On affiche chaque résumé d'article tronqué un à un, et on affiche a la fois des infos de la table articles et de la table résultats de match :
    foreach ($news as $new) {


    ?>
        <div class="p-3 m-3 col-lg-3">
            <div class="card">
                <!-- le lien vers lorem picsum utilise leur fonction random +l'id de notre article pour avoir une image unique  -->
                <img src="https://picsum.photos/300/150?random=<?= $new['id']; ?>" class="card-img-top" alt="<?= $new['titre']; ?>">
                <div class="card-body">
                    <p>
                        <mark> <?= $new['date_publication']; ?> </mark>

                        <br>

                    <h5 class="card-title"><?= $new['titre']; ?></h5>

                    <?= $new['score'] ? "<strong style=\"color:#FF0000\">Score : {$new['score']}</strong>" : "" ?>

                    <?= $new['lieu'] ? "<p>à {$new['lieu']}</p>" : "" ?>


                    <p class="card-text"><?= truncateString($new['contenu'], 200); ?></p>

                    <?php
                    // pour avoir une URL SEO FRIENDLY :
                    $url = createArticleUrl($new['id'], $new['titre'], $new['score']); ?>

                    <a class="btn btn-primary rounded-pill" href="<?= BASE_URL ?>/<?= $url; ?>">Voir l'article complet</a>


                </div>
            </div>

            <div class=" col-12 text-center">
                <a class="btn btn-outline-success rounded-pill" role="button" href="edit.html?id=<?= $new['id']; ?>"><img src="assets/img/pencil-square.svg" alt=""></a>

                <button type="button" class="btn btn-outline-danger rounded-pill" data-bs-toggle="modal" data-bs-target="#Modal<?= $new['id']; ?>"><img src="assets/img/trash.svg" alt=""></button>

            </div>

        </div>

        <!-- Modal pop up pour delete -->
        <div class="modal fade" id="Modal<?= $new['id']; ?>" tabindex="-1" aria-labelledby="ModalLabel<?= $new['id']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="deletepost.html" method="POST">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="ModalLabel<?= $new['id']; ?>">Suppression Article #<?= $new['id']; ?></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="mb-3 visually-hidden">
                                <label for="id" class="form-label">Identifiant de l'article</label>
                                <input type="hidden" class="form-control" id="id" name="id" value="<?= $new['id']; ?>">
                            </div>

                            Êtes-vous sur ?

                            <?php if ($new['match_id'] > 0): ?>
                                <!-- case à cocher pour le match -->
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="supprimerMatch" name="supprimerMatch">
                                    <label class="form-check-label" for="supprimerMatch">Voulez-vous aussi supprimer les résultats du match associé ?</label>
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





    <?php
    }
    ?>
    <div class="p-3 m-3 col-12 text-center">
        <?= '<a class="btn btn-outline-primary rounded-pill" role="button" href="add.html">
    <img src="assets/img/file-earmark-plus.svg" alt="">
 AJOUTER UN NOUVEL ARTICLE</a>'; ?>
    </div>
</div>