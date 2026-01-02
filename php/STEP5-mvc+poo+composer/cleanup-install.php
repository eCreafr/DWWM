<?php
/**
 * Script de nettoyage final - Supprime les fichiers d'installation
 * Ce script s'auto-supprime après avoir supprimé les autres fichiers
 */

$filesToDelete = [
    __DIR__ . '/install.php',
    __DIR__ . '/install-ui.php',
    __DIR__ . '/sport_2000.sql'
];

$deleted = [];
foreach ($filesToDelete as $file) {
    if (file_exists($file)) {
        if (@unlink($file)) {
            $deleted[] = basename($file);
        }
    }
}

// Auto-suppression de ce script
@unlink(__FILE__);

// Redirection vers le site
header('Location: public/index.php');
exit;
