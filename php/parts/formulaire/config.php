<?php
// ==================== CHARGEUR DE VARIABLES D'ENVIRONNEMENT ==================== //
/*
 * Ce fichier lit le fichier .env et charge les variables dans $_ENV
 * Il doit être inclus en premier dans chaque fichier PHP qui a besoin de clés API
 *
 * UTILISATION :
 *   require_once 'config.php';
 *   $maClé = $_ENV['MANDRILL_API_KEY'];
 */

function loadEnv(string $path): void {
    if (!file_exists($path)) return;

    // file() : lit le fichier et retourne un tableau de lignes
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // Ignore les commentaires (lignes commençant par #)
        if (str_starts_with(trim($line), '#')) continue;
        // Ignore les lignes sans signe =
        if (!str_contains($line, '=')) continue;

        // Sépare la clé et la valeur au premier = trouvé
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

// Charge le fichier .env situé dans le même dossier que config.php
loadEnv(__DIR__ . '/.env');
