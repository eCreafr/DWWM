<?php
// Connexion PDO centralisee, utilisee par tous les Modeles.
// Adapter host/dbname/user/password a votre environnement local (WAMP/MAMP/Docker).

$pdo = new PDO(
    'mysql:host=localhost;dbname=ludotheque_bourg;charset=utf8mb4',
    'root',
    'root',
    [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
);
