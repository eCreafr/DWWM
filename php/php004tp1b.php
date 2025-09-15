<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP 004 | Get & Post</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../html/css/froggie.css" />
    <style>
        .cadre {
            border: #000 solid 1px;
            margin: 10px;
            padding: 10px;
        }

        .spacer {
            height: 25vh;
        }
    </style>
</head>

<body>
    <br><br>
    <div class="cadre">

        <h1>RECEPTION DU MESSAGE</h1>
        <p>

            <?php
            if (
                $_SERVER['REQUEST_METHOD'] === 'POST'
                && isset($_POST["name"])
                && $_POST["name"] !== ''
                && isset($_POST["firstname"])
                && $_POST["firstname"] !== ''
                && $_POST["email"] !== ''
            ) {


                echo '<div class="card" style="width: 18rem;">
  <img src="img/004-00.png" class="card-img-top" alt="mail">
  <div class="card-body">
    <h5 class="card-title">Vous avez ... 1 .... Nouveau message...</h5>
    <p class="card-text">Bonjour, <br>' . htmlspecialchars($_POST["firstname"]) . ' ' . htmlspecialchars($_POST["name"]) . ' vient de vous écrire un message vital :
    <br><br>' . htmlspecialchars($_POST["message"]) . ' <br><br> Repondez lui sur</p>
    <a href="mailto:' . htmlspecialchars($_POST["email"]) . '" class="btn btn-primary">' . htmlspecialchars($_POST["email"]) . '</a>
    <br>ça a l\'air urgent ! non ?
  </div>
</div>
    ';
            } else {
                echo '<strong>Oups</strong>';
            }
            ?>


            <br><br>
    </div>


    <div class="froggiesplaining">
        <span> Froggiesplaining :</span>
        <br>
        R.A.S.

        <img src="../html/img/froggie-300.png" alt="Froggie" class="overfrog" />
    </div>

</body>

</html>