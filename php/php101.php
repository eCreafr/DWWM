<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>require_once</title>

    <link href="../css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid d-flex flex-column justify-content-center ">

        <?php require(__DIR__ . '/php101header.php');
        //__DIR__ retourne le chemin absolu du fichier  ?>

        <!-- Le corps -->

        <div id="corps" class="container">
            <h1>titre de la page 1</h1>

            <p>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatem delectus natus sapiente, modi sint libero consectetur voluptas facilis quasi omnis minima temporibus, labore, rem eos doloribus tenetur odio fugiat excepturi.

                TP : à vous de jouer, reprennez votre page de resultat de sport, et creez un site de 3 pages, accueil, resultats, contact, en utilisant des require_once pour appeller header et footer, et si necessaire des fonctions ou des variables
                (corrections en 102.php)
            </p>
        </div>

        <!-- Le pied de page -->
        <?php require_once('php101footer.php'); 
        // once impose de l'appeler une fois
        // pas de DIR donc chemin relatif 
        ?>

    </div>

</body>

</html>