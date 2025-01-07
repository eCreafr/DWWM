<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP 003 | exemple Fonction date</title>
</head>

<body>
    <p>Aujourd'hui nous sommes le <strong>

            <?php echo date('d/m/Y h:i:s'); ?>

        </strong>. <br> <br> ( page vers la fonction date / heure <a href="https://www.php.net/manual/fr/function.date.php">https://www.php.net/manual/fr/function.date.php</a>)</p>
    <br><br>

    <p>Aujourd'hui nous sommes le <strong>
            <?php
            // Créer une nouvelle instance DateTime avec l'heure actuelle
            $date = new DateTime();

            // Ajouter 1 heure
            $date->modify('+1 hour');

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