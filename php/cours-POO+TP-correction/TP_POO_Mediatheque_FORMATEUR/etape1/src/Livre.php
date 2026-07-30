<?php
declare(strict_types=1);

/**
 * ===================================================================
 * CORRIGÉ FORMATEUR — ÉTAPE 1 — Classe Livre
 * ===================================================================
 * Niveaux 1 et 2 complets.
 */
class Livre
{
    public function __construct(
        private string $titre,
        private string $auteur,
        private int $annee
    ) {}

    // ---------- NIVEAU 1 ----------

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function getAuteur(): string
    {
        return $this->auteur;
    }

    public function getAnnee(): int
    {
        return $this->annee;
    }

    // ---------- NIVEAU 2 ----------

    public function setTitre(string $titre): void
    {
        if (trim($titre) === '') {
            throw new InvalidArgumentException('Le titre ne peut pas être vide.');
        }

        $this->titre = trim($titre);
    }

    public function afficher(): string
    {
        return sprintf('%s (%d), de %s', $this->titre, $this->annee, $this->auteur);
    }
}
