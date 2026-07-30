<?php

// Niveau 3 : liste des adherents, utilisee par le formulaire d'emprunt.
class AdherentModel
{
    private PDO $pdo;

    public function __construct()
    {
        require __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;
    }

    public function tousLesAdherents(): array
    {
        return $this->pdo->query('SELECT * FROM adherents ORDER BY nom ASC')->fetchAll();
    }
}
