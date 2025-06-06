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
            <h1>Résultats des matchs de football pour toi <?php if (isset($_SESSION['LOGGED_USER'])) : echo $_SESSION['LOGGED_USER']['prenom'] . ' ' . $_SESSION['LOGGED_USER']['nom'];
                                                            endif; ?>
            </h1>
        </div>
        <?php if (isset($_SESSION['LOGGED_USER'])) :  ?>
            <div class="container d-flex flex-wrap gap-5 justify-content-center">
                <?php foreach ($Matches as $match) {  ?>
                    <article class="card my-3 p-5 bg-secondary bg-opacity-25 w-25">
                        <!-- Affiche les équipes avec leur couleur -->
                        <h3>
                            <span>
                                <?php echo $match['equipe1']; ?>
                            </span>
                            vs
                            <span>
                                <?php echo $match['equipe2']; ?>
                            </span>
                        </h3>
                        <!-- Affiche le score -->
                        <p><strong>Score : </strong><?php echo $match['score']; ?></p>
                        <!-- Affiche le résumé du match -->
                        <div><?php echo $match['resume']; ?></div>
                    </article>

                <?php } ?>
            <?php else :  ?>
                <div class="container d-flex flex-wrap gap-5 justify-content-center">
                    <div> <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <h2>Sorry</h2>les résultats sont reservés aux abonnés<br><br><br><br><br><br>
                    </div>
                </div>
            <?php endif;  ?>
            </div>

            <!-- Le pied de page -->

            <?php require_once(__DIR__ . '/footer.php'); ?>
    </div>

</body>

</html>