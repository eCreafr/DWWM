<?php
// Détection automatique de l'URL de base
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$baseFolder = dirname($_SERVER['SCRIPT_NAME']);
define('BASE_URL', $protocol . $host . $baseFolder);

// Si vous préférez le définir manuellement par exemple :
// define('BASE_URL', '/git/php/php103+50/public');