<?php

// Niveau 3 : acces aux donnees de la table emprunts.
// La regle metier (un jeu disponible uniquement) vit dans EmpruntService, pas ici.
class EmpruntModel
{
    private PDO $pdo;

    public function __construct()
    {
        require __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;
    }

    public function creerEmprunt(int $jeuId, int $adherentId): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO emprunts (jeu_id, adherent_id, date_emprunt) VALUES (:jeu_id, :adherent_id, CURDATE())'
        );
        $stmt->bindValue(':jeu_id', $jeuId, PDO::PARAM_INT);
        $stmt->bindValue(':adherent_id', $adherentId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function cloturerEmprunt(int $jeuId): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE emprunts SET date_retour = CURDATE() WHERE jeu_id = :jeu_id AND date_retour IS NULL'
        );
        $stmt->bindValue(':jeu_id', $jeuId, PDO::PARAM_INT);
        $stmt->execute();
    }
}
