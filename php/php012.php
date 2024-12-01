<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boucles et tableaux</title>
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

        <h1>TABLEAUX</h1><br><br>

        <?php

        // tableau
        $user1 = ['Ayman', 'ayman@exemple.com', 'password123', 21];

        echo $user1[0]; // "Ayman"
        echo $user1[1]; // "ayman@exemple.com"
        echo $user1[3]; // 21

        // tableau de tableau !

        $ayman = ['Ayman', 'ayman@exemple.com', 'password123', 21];
        $manon = ['Manon', 'manon@exemple.com', '12345', 21];
        $paul = ['Paul', 'paul@exemple.com', 'lateste', 21];

        $users = [$ayman, $manon, $paul]; // tableau de tableau !

        echo $users[1][1]; // "manon@exemple.com"


        // avec boucle for each

        foreach ($users as $value) {
            echo "$value[0]<br>";
            echo "$value[1]<br>";
            echo "$value[2]<br><br><br>";
        }


        if (in_array($manon, $users)) {
            echo 'manon fait bien partie des utilisateurs enregistrés !';
        }

        ?>
    </div>

    <div class="froggiesplaining">
        <span> Froggiesplaining :</span>
        <br>

        <img src="../html/img/froggie-300.png" alt="Froggie" class="overfrog" />
    </div>

    <div class="spacer"></div>
    <br><br>
    <div class="cadre">
        <h1>BOUCLE WHILE</h1>
        <br><br>
        <img src="img/012-3.webp" alt="" width="450px"> <br><br>
        <?php
        // while / boucle


        $lines = 1;

        while ($lines <= 200) {
            echo ' ' . $lines . ' - j\'irais plus vite a copier 200 lignes en php. <br />';
            $lines++; // revient à ecrire $lines = $lines + 1
        }






        ?>
    </div>
    <div class="froggiesplaining">
        <span> Froggiesplaining :</span>
        <br>

        <img src="../html/img/froggie-300.png" alt="Froggie" class="overfrog" />
    </div>

    <div class="spacer"></div>

</body>

</html>