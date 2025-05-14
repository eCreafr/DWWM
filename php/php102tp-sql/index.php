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

            <h1><?php if (isset($_SESSION['LOGGED_USER'])) : echo $_SESSION['LOGGED_USER']['prenom'];
                endif; ?>bienvenue sur le site de l'équipe</h1>
            <h2>à la une :</h2>
        </div>

        <div class="container d-flex flex-wrap gap-5 justify-content-center">
            <div class="card col-12 col-md-4 p-3"><img src="img/102-card.jpeg" class="img-fluid" alt="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor modi similique quia numquam ad, facilis perspiciatis possimus quam laboriosam dolorem unde voluptatem minus nisi quas quidem officia quis expedita omnis.</div>
            <div class="card col-12 col-md-4 p-3"><img src="img/102-card.jpeg" class="img-fluid" alt="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor modi similique quia numquam ad, facilis perspiciatis possimus quam laboriosam dolorem unde voluptatem minus nisi quas quidem officia quis expedita omnis.</div>
            <div class="card col-12 col-md-4 p-3"><img src="img/102-card.jpeg" class="img-fluid" alt="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor modi similique quia numquam ad, facilis perspiciatis possimus quam laboriosam dolorem unde voluptatem minus nisi quas quidem officia quis expedita omnis.</div>

            <!-- Formulaire de connexion -->
            <?php require_once(__DIR__ . '/login.php');  ?>

            <?php if (isset($_SESSION['LOGGED_USER'])) :  ?>


                <div class="card col-12 col-md-4 p-3"><img src="img/102-card.jpeg" class="img-fluid" alt="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor modi similique quia numquam ad, facilis perspiciatis possimus quam laboriosam dolorem unde voluptatem minus nisi quas quidem officia quis expedita omnis.</div>
                <div class="card col-12 col-md-4 p-3"><img src="img/102-card.jpeg" class="img-fluid" alt="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor modi similique quia numquam ad, facilis perspiciatis possimus quam laboriosam dolorem unde voluptatem minus nisi quas quidem officia quis expedita omnis.</div>
                <div class="card col-12 col-md-4 p-3"><img src="img/102-card.jpeg" class="img-fluid" alt="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor modi similique quia numquam ad, facilis perspiciatis possimus quam laboriosam dolorem unde voluptatem minus nisi quas quidem officia quis expedita omnis.</div>
                <div class="card col-12 col-md-4 p-3"><img src="img/102-card.jpeg" class="img-fluid" alt="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor modi similique quia numquam ad, facilis perspiciatis possimus quam laboriosam dolorem unde voluptatem minus nisi quas quidem officia quis expedita omnis.</div>
                <div class="card col-12 col-md-4 p-3"><img src="img/102-card.jpeg" class="img-fluid" alt="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor modi similique quia numquam ad, facilis perspiciatis possimus quam laboriosam dolorem unde voluptatem minus nisi quas quidem officia quis expedita omnis.</div>
                <div class="card col-12 col-md-4 p-3"><img src="img/102-card.jpeg" class="img-fluid" alt="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor modi similique quia numquam ad, facilis perspiciatis possimus quam laboriosam dolorem unde voluptatem minus nisi quas quidem officia quis expedita omnis.</div>

            <?php endif;  ?>
        </div>

        <!-- Le pied de page -->

        <?php require_once(__DIR__ . '/footer.php'); ?>
    </div>

</body>

</html>