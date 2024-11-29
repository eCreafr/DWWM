
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
       
        <h1>Contactez-nous</h1><h2>formulaire :</h2>
    </div>
    <div class="container">
<form method="post" action="php102-submit-contact.php">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" aria-describedby="email-help">
                <div id="email-help" class="form-text">Nous ne revendrons pas votre email.</div>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Votre message</label>
                <textarea class="form-control" placeholder="que pensez vous du classement du PSG en ce moment ?" id="message" name="message"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form></div>
    
    <!-- Le pied de page -->
    
    <?php require_once(__DIR__ . '/php102/footer.php'); ?>
    </div>
 
    </body>
</html>