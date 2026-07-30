<?php
declare(strict_types=1);

/**
 * ===================================================================
 * CORRIGÉ FORMATEUR — ÉTAPE 2 — Classe abstraite Document
 * ===================================================================
 */
abstract class Document
{
    public function __construct(
        protected string $titre,
        protected int $annee,
        protected bool $disponible = true
    ) {}

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function getAnnee(): int
    {
        return $this->annee;
    }

    public function estDisponible(): bool
    {
        return $this->disponible;
    }

    // ---------- TODO 1 corrigé ----------
    abstract public function getDescription(): string;
}
