<?php

//ne fonctionne qu'avec un vrai certificat SSL, donc en ligne sur votre ovh par exemple
// voir démo live ici https://ecrea.fr/php/apimeteo.php



// Clé API et emplacement
$apiKey = "votre propre clé API en vous inscrivant a l'offre gratuite sur le site openweathermap.org";
$lat = "44.60954984533323";
$lon = "-1.1197549342861468";
// $city = "Bordeaux"; // alternative si on a pas les coordonnées GPS
$url = "http://api.openweathermap.org/data/2.5/weather?lon=$lon&lat=$lat&appid=$apiKey&units=metric&lang=fr";

// Récupération des données
$response = file_get_contents($url);
$data = json_decode($response, true);

// Traitement des données
if ($data['cod'] == 200) {
    echo "Météo à " . $data['name'] . ": " . $data['weather'][0]['description'] . "<br>";
    echo "Température : " . $data['main']['temp'] . "°C<br>";
} else {
    echo "Erreur : impossible de récupérer les données météo.";
}
