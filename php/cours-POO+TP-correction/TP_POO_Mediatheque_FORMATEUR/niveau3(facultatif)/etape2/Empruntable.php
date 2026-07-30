<?php
declare(strict_types=1);

/** CORRIGÉ NIVEAU 3 — ÉTAPE 2 — Empruntable (inchangée). */
interface Empruntable
{
    public function emprunter(): void;
    public function rendre(): void;
}
