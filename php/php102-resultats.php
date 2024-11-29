<?php
require_once(__DIR__ . '/php102/variables.php');
require_once(__DIR__ . '/php102/functions.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>structure site PHP par section / bloc</title>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
    </head>
 
    <body>
    <div style="background: #F8F9F9;" class="container-fluid d-flex flex-column justify-content-center ">


    <?php require_once(__DIR__ . '/php102/header.php'); ?>

    <!-- Le corps -->
    
 <div class="container text-center">
       
        <h1>Résultats des matchs de football</h1>
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
    
    <?php require_once(__DIR__ . '/php102/footer.php'); ?>
    </div>
 
    </body>
</html>