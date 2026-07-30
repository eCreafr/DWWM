<?php
declare(strict_types=1);

/**
 * CORRIGÉ NIVEAU 3 — ÉTAPE 2 — Document
 * Identique au niveau 2 : l'extension Reservable ne touche PAS au parent.
 * C'est précisément ce qu'on veut démontrer.
 */
abstract class Document
{
    public function __construct(
        protected string $titre,
        protected int $annee,
        protected bool $disponible = true
    ) {}

    public function getTitre(): string    { return $this->titre;      }
    public function getAnnee(): int       { return $this->annee;      }
    public function estDisponible(): bool { return $this->disponible; }

    abstract public function getDescription(): string;
}
