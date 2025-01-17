<?php
// Nom du fichier contenant les logs JSON
$fichier_logs = 'articles_logs.json';

// Vérifier si le fichier existe
if (!file_exists($fichier_logs)) {
    die("Le fichier $fichier_logs n'existe pas.");
}

// Lire le contenu du fichier
$contenu_json = file_get_contents($fichier_logs);

// Vérifier si la lecture est réussie
if ($contenu_json === false) {
    die("Impossible de lire le fichier $fichier_logs.");
}

// Décoder le contenu JSON en tableau PHP
$logs = json_decode($contenu_json, true);

// Vérifier si le décodage est réussi
if ($logs === null) {
    die("Erreur lors du décodage des logs JSON.");
}

// Fonction pour afficher les données de manière propre
function afficher_log($log)
{
    echo "<div style='border:1px solid #ccc; margin:10px; padding:10px;'>";
    echo "<strong>Timestamp :</strong> " . htmlspecialchars($log['timestamp']) . "<br>";
    echo "<strong>Action :</strong> " . htmlspecialchars($log['action']) . "<br>";
    echo "<strong>IP :</strong> " . htmlspecialchars($log['ip']) . "<br>";
    echo "<strong>User Agent :</strong> " . htmlspecialchars($log['user_agent']) . "<br>";

    // Afficher les données supplémentaires
    echo "<strong>Données :</strong><pre>" . htmlspecialchars(json_encode($log['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . "</pre>";
    echo "</div>";
}

// Afficher chaque log dans le navigateur
echo "<h1>Logs</h1>";
foreach ($logs as $log) {
    afficher_log($log);
}
