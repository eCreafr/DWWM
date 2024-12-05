

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

            <h1>Contactez-nous</h1>
            <h2>formulaire :</h2>
        </div>
    
        <div class="container">
            <form method="post" action="submit-contact.php">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" placeholder="<?php if (isset($_SESSION['LOGGED_USER'])) : echo $_SESSION['LOGGED_USER']['email'];
                                                        endif; ?>" class="form-control" id="email" name="email" aria-describedby="email-help">
                    <div id="email-help" class="form-text">Nous ne revendrons pas votre email.</div>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Votre message</label>
                    <textarea class="form-control" placeholder="    <?php if (isset($_SESSION['LOGGED_USER'])) : echo $_SESSION['LOGGED_USER']['nom'];
                endif; ?>
 que pensez vous du classement du PSG en ce moment ?" id="message" name="message"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        </div>

        <!-- Le pied de page -->

        <?php require_once(__DIR__ . '/footer.php'); ?>
    </div>

</body>

</html>