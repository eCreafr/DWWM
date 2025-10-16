<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>on test le Post d'une page a l'autre</title>
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

        <h1>FORMULAIRE</h1>


        <form action="php004tp1b.php" method="POST">

            <label for="firstname" class="form-label">Votre prénom :</label>
            <input class="form-control" type="text" id="firstname" name="firstname" required> <br><br>
            <label for="name" class="form-label">Votre nom :</label>
            <input class="form-control" type="text" id="name" name="name" required> <br><br>



            <label for="email" class="form-label">Votre mail :</label>
            <input class="form-control" type="email" id="email" name="email" required> <br><br>

            <label for="message" class="form-label">Votre message :</label>
            <textarea class="form-control" name="message" id="message" required>...</textarea> <br><br>

            <button type="submit" class="btn  btn-primary">Envoyer mon message</button>
        </form>


        <br><br>
    </div>


    <div class="froggiesplaining">
        <span> Froggiesplaining :</span>
        <br>

        <img src="../html/img/froggie-300.png" alt="Froggie" class="overfrog" />
    </div>

    <div class="spacer"></div>

</body>

</html>