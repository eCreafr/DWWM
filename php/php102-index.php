
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
       
        <h1>bienvenue sur le site de l'équipe</h1><h2>à la une :</h2>
    </div>
    
    <div class="container d-flex flex-wrap gap-5 justify-content-center">
       <div class="card w-25"><img src="img/102-card.jpeg" class="img-fluid" alt="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor modi similique quia numquam ad, facilis perspiciatis possimus quam laboriosam dolorem unde voluptatem minus nisi quas quidem officia quis expedita omnis.</div>
       <div class="card w-25"><img src="img/102-card.jpeg" class="img-fluid" alt="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor modi similique quia numquam ad, facilis perspiciatis possimus quam laboriosam dolorem unde voluptatem minus nisi quas quidem officia quis expedita omnis.</div>
       <div class="card w-25"><img src="img/102-card.jpeg" class="img-fluid" alt="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor modi similique quia numquam ad, facilis perspiciatis possimus quam laboriosam dolorem unde voluptatem minus nisi quas quidem officia quis expedita omnis.</div>
       <div class="card w-25"><img src="img/102-card.jpeg" class="img-fluid" alt="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor modi similique quia numquam ad, facilis perspiciatis possimus quam laboriosam dolorem unde voluptatem minus nisi quas quidem officia quis expedita omnis.</div>
       <div class="card w-25"><img src="img/102-card.jpeg" class="img-fluid" alt="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor modi similique quia numquam ad, facilis perspiciatis possimus quam laboriosam dolorem unde voluptatem minus nisi quas quidem officia quis expedita omnis.</div>
       <div class="card w-25"><img src="img/102-card.jpeg" class="img-fluid" alt="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor modi similique quia numquam ad, facilis perspiciatis possimus quam laboriosam dolorem unde voluptatem minus nisi quas quidem officia quis expedita omnis.</div>
    </div>
    
    <!-- Le pied de page -->
    
    <?php require_once(__DIR__ . '/php102/footer.php'); ?>
    </div>
 
    </body>
</html>