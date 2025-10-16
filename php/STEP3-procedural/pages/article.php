<div class="container text-center d-flex  flex-wrap justify-content-center">

    <?php

    if ($article) {
        echo "<div class='card col-9 m-5 p-3'>";
        echo "<img src=\"https://picsum.photos/800/150\" class=\"img-fluid rounded-top mb-2\" alt=\"\">";
        echo "<h1>{$article['titre']}</h1>";
        echo "<p>Date: {$article['date_publication']}</p>";
        echo $article['score'] ? "<strong style=\"color:#FF0000\">score : {$article['score']}</strong>" : "";
        echo $article['lieu'] ? "<p>lieu : {$article['lieu']}</p>" : "";
        echo "<p>{$article['contenu']}</p>";
        echo "</div>";
    } else {
        echo "<p>Article non trouvé.</p>";
    }

    ?>

    <div class="col-12">
        <!-- l'api share ne fonctionne qu'en httpS !  -->
        <button
            id="shareButton"
            class="btn btn-primary share-button"
            data-title="<?= $article['titre'] ? "{$article['titre']} " : ""; ?>"
            data-text="<?= $article['titre'] ? "{$article['titre']} " : ""; ?>"
            data-url="article.php?id=<?= ($_GET['id']); ?>"><img src="<?= BASE_URL ?>/assets/img/share.svg" alt="partager <?= $article['titre']; ?>" width="24px">
        </button>

        <a class="btn btn-primary" role="button" href="<?= BASE_URL ?>/home.html">RETOUR</a>

        <div id="shareAlert" class="alert"></div>
    </div>


</div>
<script src="../../js/share.js"></script>