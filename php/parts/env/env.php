<?php

/**
 * env.php - Version ULTRA minimaliste
 * Inclure ce fichier : require_once 'env.php';
 */

// Charger .env
function env($key, $default = null)
{
    static $env = null;

    if ($env === null) {
        $env = [];
        if (file_exists('.env')) {
            foreach (file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                if ($line[0] !== '#' && strpos($line, '=')) {
                    list($k, $v) = explode('=', $line, 2);
                    $env[trim($k)] = trim($v, ' "\'');
                }
            }
        }
    }

    return $env[$key] ?? $default;
}
