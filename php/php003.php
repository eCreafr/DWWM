<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP 003 | exemple Fonction date</title>
</head>

<body>
    <p>Aujourd'hui nous sommes le <strong>

            <?= date('d/m/Y h:i:s'); ?>

        </strong>. <br> <br> ( page vers la fonction date / heure <a href="https://www.php.net/manual/fr/function.date.php">https://www.php.net/manual/fr/function.date.php</a>)</p>
    <br><br>

    <?php
    // Assuming today is March 10th, 2001, 5:16:18 pm, and that we are in the
    // Mountain Standard Time (MST) Time Zone

    $today = date("F j, Y, g:i a");                 // March 10, 2001, 5:16 pm
    $today = date("m.d.y");                         // 03.10.01
    $today = date("j, n, Y");                       // 10, 3, 2001
    $today = date("Y/m/d");                         // 2001/03/10
    $today = date('h-i-s, j-m-y, it is w Day');     // 05-16-18, 10-03-01, 1631 1618 6 Satpm01
    $today = date('\i\t \i\s \t\h\e jS \d\a\y.');   // it is the 10th day.
    $today = date("D M j G:i:s T Y");               // Sat Mar 10 17:16:18 MST 2001
    $today = date('H:m:s \m \i\s\ \m\o\n\t\h');     // 17:03:18 m is month
    $today = date("H:i:s");                         // 17:16:18
    $today = date("Y-m-d H:i:s");                   // 2001-03-10 17:16:18 (the MySQL DATETIME format)
    ?>

    <p>Aujourd'hui nous sommes le <strong>
            <?php
            // Créer une nouvelle instance DateTime avec l'heure actuelle
            $date = new DateTime();

            // Ajouter 1 heure
            $date->modify('+2 hour');

            // Afficher la date formatée
            echo $date->format('d/m/Y H:i:s');


            ?>.</strong>
    </p>

    <br><br>
    Bonne version amha :

    <p>Aujourd'hui nous sommes le <strong>
            <?php
            // Définir le fuseau horaire
            date_default_timezone_set('Europe/Paris'); // Ajustez selon vos besoins

            // Créer une nouvelle instance DateTime
            $date = new DateTime();

            // Ajouter 1 heure ou pas 
            // $date->modify('+1 hour');

            // Afficher la date formatée
            echo $date->format('D/M/Y H:i:s');
            ?></strong></p>

    <br><br>
    et pour avoir une version formatée a la française : <br><br>
    Nous sommes le <?php

                    date_default_timezone_set('Europe/Paris');

                    // Create an IntlDateFormatter instance for French locale
                    $formatter = new IntlDateFormatter(
                        'fr_FR',
                        IntlDateFormatter::LONG,
                        IntlDateFormatter::NONE,
                        'Europe/Paris',
                        IntlDateFormatter::GREGORIAN,
                        'EEEE d MMMM yyyy \'et il est\' HH\'h\'mm'
                    );

                    // Format the current date and time
                    echo $formatter->format(new DateTime());


                    ?>

</body>

</html>