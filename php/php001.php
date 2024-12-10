<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP 001 | variable, echo ...<?php //je pourrais mettre du php ici 
                                        ?> </title>
    <link rel="stylesheet" href="../html/css/froggie.css" />
    <style>
        .cadre {
            border: #000 solid 1px;
            margin: 10px;
            padding: 10px;
        }
    </style>
</head>

<body>

    <?php //ou ici 
    ?>


    <? //existe 
    ?>
    <?php //mais c'est cette forme la plus correcte 
    ?>














    <div>
        <div class="cadre">
            <strong>Afficher un message, du html avec echo</strong><br><br>
            <?php     // echo :
            echo ("coucou");  // on affiche coucou dans le html
            echo ("<br><strong>coucou</strong>"); // on affiche du code html  
            ?>

            <br><br>

            <strong>Une variable:</strong>

            <br><br>

            <?php

            // on defini des variables  

            $name = "Ayman";

            $client = "user";            //on charge le nom d'une variable dans une variable
            $$client = "bob";            //on charge la valeur "bob" dans la variable $user

            // on appelle ces variabkes avec echo de differente façon :
            echo "$name";
            echo ("<br>$user<br>");        //affiche bob
            echo ($$client);            //affiche bob
            echo ("<br>");
            echo ("$$client <br>");        //affiche $user
            echo ("{${$client}}<br>");    //affiche bob
            echo ("{${'user'}}<br>");  //affiche bob

            ?><br><br>
            <strong>Le nom d'une variable:</strong> <br><br>
            <ul>
                <li>a comme préfixe le signe $</li>
                <li>est sensible à la casse</li>
                <li>ne contient jamais de caractères accentués</li>
            </ul>
            <br><br>
            <strong>Les differentes variables : </strong><br><br>
            <?php
            $var0 = 33;
            $var1 = 33.500;
            $var2 = "La Teste";
            $var3 = true;
            $var4 = Null;
            $var5 = array("Manon", "Julien", "Sebastien", "Ayman", "Paul", "Thomas");

            var_dump($var0, $var1, $var2, $var3, $var4, $var5); //var_dump() affiche les informations structurées d'une variable, y compris son type et sa valeur. Les tableaux et les objets sont explorés récursivement, avec des indentations, pour mettre en valeur leur structure.
            ?>
        </div>





    </div>
    <div class="froggiesplaining">
        <span> Froggiesplaining :</span>
        <br>
        <img src="img/001-2.png" alt="">


        <img src="../html/img/froggie-300.png" alt="Froggie" class="overfrog" />
    </div>


    <div class="cadre">
        <h1>Générer Dynamiquement des variables</h1>


        <?php


        $questions = []; // Initialiser un tableau pour stocker les valeurs

        for ($i = 0; $i < 10; $i++) {
            $key = "quest" . $i; // Générer la clé pour chaque entrée
            $questions[$key] = "valeur" . $i; // Ajouter la valeur au tableau

            echo "$key = {$questions[$key]}<br>"; // Afficher la clé et la valeur
        }

        ?>


    </div>

    <div class="froggiesplaining">
        <span> Froggiesplaining :</span> <br>


        <img src="img/001-3.png" alt="">


        <img src="../html/img/froggie-300.png" alt="Froggie" class="overfrog" />
    </div>




</body>

</html>