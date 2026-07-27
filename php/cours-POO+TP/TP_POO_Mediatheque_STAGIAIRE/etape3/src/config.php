<?php
declare(strict_types=1);

/**
 * TP POO — ÉTAPE 3 — Paramètres de connexion.
 *
 * ADAPTEZ CES VALEURS À VOTRE STACK LOCALE :
 *   - MAMP        : user 'root', pass 'root', port 8889 souvent
 *   - XAMPP/WAMP  : user 'root', pass ''
 *   - Homebrew    : user 'root', pass '' ou celui défini à l'install
 *   - Docker      : selon votre docker-compose.yml
 *
 * [NOTE SÉCURITÉ] En production, ces valeurs ne vivent JAMAIS dans un
 * fichier versionné. Elles vont dans des variables d'environnement, et
 * le fichier est ajouté au .gitignore. On le fait « en dur » ici pour
 * ne pas alourdir le TP — mais sachez le dire au jury (CP8).
 */
return [
    'host'     => 'localhost',
    'port'     => 3306,
    'dbname'   => 'mediatheque',
    'charset'  => 'utf8mb4',
    'user'     => 'root',
    'password' => 'root',
];
