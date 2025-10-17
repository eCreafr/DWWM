<?php

// ==========================================
// UTILISATION
// ==========================================

// Récupérer une variable
$apiKey = env('MANDRILL_API_KEY');
$debug = env('DEBUG', false);


// Connexion MySQL simple
function db()
{
    static $pdo = null;
    if (!$pdo) {
        try {
            $pdo = new PDO(
                sprintf(
                    'mysql:host=%s;dbname=%s;charset=utf8mb4',
                    env('DB_HOST', 'localhost'),
                    env('DB_NAME')
                ),
                env('DB_USER'),
                env('DB_PASS')
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            die('Erreur DB: ' . $e->getMessage());
        }
    }
    return $pdo;
}
// ==========================================