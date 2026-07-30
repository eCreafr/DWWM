<?php
declare(strict_types=1);

/**
 * CORRIGÉ NIVEAU 3 — ÉTAPE 2 — Livre
 *
 * Trois raccordements sur une seule ligne de déclaration :
 *   extends Document       → hérite du type et du code commun
 *   implements Empruntable → contrat emprunter/rendre
 *   implements Reservable  → contrat de réservation (Dvd ne l'a PAS)
 *
 * Et `use TraitReservation` injecte l'implémentation partagée avec
 * JeuVideo, sans créer de lien de parenté entre les deux.
 */
class Livre extends Document implements Empruntable, Reservable
{
    use TraitReservation;

    public function __construct(
        string $titre,
        int $annee,
        private string $auteur
    ) {
        parent::__construct($titre, $annee);
    }

    public function getAuteur(): string
    {
        return $this->auteur;
    }

    public function getDescription(): string
    {
        return sprintf('%s (%d), de %s', $this->titre, $this->annee, $this->auteur);
    }

    public function emprunter(): void { $this->disponible = false; }
    public function rendre(): void    { $this->disponible = true;  }
}
