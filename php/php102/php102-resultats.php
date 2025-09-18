<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once(__DIR__ . '/head.php'); ?>
    <title>structure site PHP par section / bloc</title>
</head>

<body>
    <div style="background: #F8F9F9;" class="container-fluid d-flex flex-column justify-content-center ">


        <?php require_once(__DIR__ . '/header.php'); ?>

        <!-- Le corps -->

        <div class="container text-center">

            <!-- on appelle avec un cookie et pas get ou Post :  -->
            <h1>Résultats des matchs de football pour toi
                <?php echo htmlspecialchars($_COOKIE['firstname'] ?? 'ici votre prénom'); ?>
            </h1>
        </div>

        <div class="container d-flex flex-wrap gap-5 justify-content-center">
            <?php
            // Afficher les matchs actifs
            foreach (getActiveMatches($matches) as $match) : ?>
                <article class="card my-3 p-5 bg-secondary bg-opacity-25 w-25">
                    <!-- Affiche les équipes avec leur couleur -->
                    <h3>
                        <span style="color: <?php echo $match['equipe1']['color']; ?>">
                            <?php echo $match['equipe1']['name']; ?>
                        </span>
                        vs
                        <span style="color: <?php echo $match['equipe2']['color']; ?>">
                            <?php echo $match['equipe2']['name']; ?>
                        </span>
                    </h3>
                    <!-- Affiche le score -->
                    <p><strong>Score : </strong><?php echo $match['score']; ?></p>
                    <!-- Affiche le résumé du match -->
                    <div><?php echo $match['resume']; ?></div>
                </article>

            <?php endforeach; ?>


        </div>

        <!-- Le pied de page -->

        <?php require_once(__DIR__ . '/footer.php'); ?>
    </div>

</body>

</html>