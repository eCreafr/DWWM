<?php
declare(strict_types=1);

/**
 * ===================================================================
 * CORRIGÉ FORMATEUR — ÉTAPE 2 — Classe Dvd
 * ===================================================================
 */
class Dvd extends Document implements Empruntable
{
    public function __construct(
        string $titre,
        int $annee,
        private string $realisateur,
        private int $duree
    ) {
        parent::__construct($titre, $annee);
    }

    public function getRealisateur(): string
    {
        return $this->realisateur;
    }

    public function getDuree(): int
    {
        return $this->duree;
    }

    public function getDescription(): string
    {
        return sprintf(
            '%s (%d), réalisé par %s — %d min',
            $this->titre,
            $this->annee,
            $this->realisateur,
            $this->duree
        );
    }

    public function emprunter(): void
    {
        $this->disponible = false;
    }

    public function rendre(): void
    {
        $this->disponible = true;
    }
}
