<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Météo API</title>
    <!-- appeler bootstrap local ou cdn -->

</head>

<body>
    <div class="container container-meteo d-flex flex-column justify-content-center align-items-center p-5">
        <div class="card col-md-5 my-5 p-5 rounded bg-light bg-opacity-10 text-center">
            <?php
            // Clé API et emplacement
            $apiKey = "votrecléAPI";
            $lat = "44.60954984533323";
            $lon = "-1.1197549342861468";
            $url = "https://api.openweathermap.org/data/2.5/weather?lon=$lon&lat=$lat&appid=$apiKey&units=metric&lang=fr";

            // Récupération des données
            $response = file_get_contents($url);
            $data = json_decode($response, true);
            $iconCode = $data['weather'][0]['icon'];
            $iconUrl = "https://openweathermap.org/img/wn/{$iconCode}@2x.png";

            // Traitement des données
            if ($data['cod'] == 200) {
                echo "<h2 class='h2-title-thin'>Météo à " . $data['name'] . " :</h2> <br><h2 class='h2-gradient'> " . $data['weather'][0]['description'] . " </h2>";
                echo "<img src='{$iconUrl}' alt='" . $data['weather'][0]['description'] . "'> <br>";
                echo "Température : <strong> " . $data['main']['temp'] . "°C</strong> <br>";
                echo "Humidité :<strong>  " . $data['main']['humidity'] . "%</strong> <br>";
                echo "Vent : <strong> " . $data['wind']['speed'] . "m/s</strong> <br>";
            } else {
                echo "Erreur : impossible de récupérer les données météo.";
            }
            ?></div>


    </div>
</body>

</html>