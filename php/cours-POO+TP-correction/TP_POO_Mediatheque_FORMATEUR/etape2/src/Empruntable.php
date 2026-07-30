<?php
declare(strict_types=1);

/**
 * ===================================================================
 * CORRIGÉ FORMATEUR — ÉTAPE 2 — Interface Empruntable
 * ===================================================================
 */
interface Empruntable
{
    // ---------- TODO 1 corrigé ----------
    public function emprunter(): void;

    // ---------- TODO 2 corrigé ----------
    public function rendre(): void;
}
