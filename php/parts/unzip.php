<?php

// Récupération du chemin du dossier courant
$dossier = __DIR__;

// Liste tous les fichiers ZIP dans ce dossier
$zips = glob($dossier . '/*.zip');

// Vérifie si on a trouvé des fichiers ZIP
if (empty($zips)) {
    echo "Aucun fichier ZIP trouvé dans ce dossier.";
    exit;
}

// Boucle sur chaque fichier ZIP trouvé
foreach ($zips as $fichierZip) {
    echo "<p>Décompression de : <strong>" . basename($fichierZip) . "</strong><br>";

    $zip = new ZipArchive;
    if ($zip->open($fichierZip) === TRUE) {
        // On choisit d’extraire dans le même dossier que le zip
        $zip->extractTo($dossier);
        $zip->close();
        echo "✅ Extraction réussie !</p>";
    } else {
        echo "❌ Impossible d'ouvrir " . basename($fichierZip) . "</p>";
    }
}

echo "<hr>🚀 Terminé !";
