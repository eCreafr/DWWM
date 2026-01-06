<?php

/**
 * Chargeur de fichier .env
 *
 * Charge les variables d'environnement depuis le fichier .env
 * Cette fonction parse le fichier .env et définit les variables dans $_ENV et via putenv()
 */

function loadEnv(string $path): void
{
    if (!file_exists($path)) {
        throw new Exception("Le fichier .env est introuvable. Copiez .env.example vers .env et configurez vos variables.");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // Ignore les commentaires
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Parse les lignes au format KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            // Retire les guillemets si présents
            $value = trim($value, '"\'');

            // Définit la variable d'environnement
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}

/**
 * Récupère une variable d'environnement
 *
 * @param string $key Le nom de la variable
 * @param mixed $default La valeur par défaut si la variable n'existe pas
 * @return mixed La valeur de la variable ou la valeur par défaut
 */
function env(string $key, $default = null)
{
    return $_ENV[$key] ?? getenv($key) ?: $default;
}
