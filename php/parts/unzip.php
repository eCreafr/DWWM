<?php
$zip = new ZipArchive;
if ($zip->open('site.zip') === TRUE) {
    $zip->extractTo('.');
    $zip->close();
    echo 'Décompression terminée !';
} else {
    echo 'Échec de l\'ouverture du fichier ZIP.';
}
