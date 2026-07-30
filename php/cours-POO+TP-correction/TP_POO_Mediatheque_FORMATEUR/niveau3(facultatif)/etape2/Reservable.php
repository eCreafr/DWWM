<?php
declare(strict_types=1);

/**
 * ===================================================================
 * CORRIGÉ NIVEAU 3 — ÉTAPE 2 — Interface Reservable
 * ===================================================================
 * Défi : « ajouter une interface Reservable implémentée UNIQUEMENT par
 * Livre et JeuVideo — démontrer l'intérêt du multi-contrat ».
 *
 * POURQUOI CETTE EXTENSION EST LA PLUS FORMATRICE DES TROIS
 * ---------------------------------------------------------
 * Tant que les trois classes implémentent les mêmes interfaces,
 * l'intérêt d'une interface reste théorique : on pourrait tout aussi
 * bien mettre emprunter() et rendre() dans Document.
 *
 * Ici, la règle métier de Claire est : les DVD ne se réservent pas
 * (rotation trop rapide). Donc Dvd n'implémente PAS Reservable.
 *
 * C'est LE cas où l'interface devient indispensable :
 *   - on ne peut pas mettre reserver() dans Document, sinon Dvd
 *     en hériterait alors qu'il ne doit pas l'avoir ;
 *   - on ne peut pas créer une classe intermédiaire, PHP étant à
 *     héritage simple et Livre/JeuVideo n'ayant rien d'autre en commun.
 *
 * L'interface est la seule réponse correcte. C'est cette démonstration
 * qu'il faut faire produire au stagiaire.
 */
interface Reservable
{
    /**
     * Pose une réservation au nom d'un adhérent.
     *
     * @throws RuntimeException si le document est déjà réservé
     */
    public function reserver(string $adherent): void;

    /** Annule la réservation en cours, s'il y en a une. */
    public function annulerReservation(): void;

    public function estReserve(): bool;

    /** Nom de l'adhérent ayant réservé, ou null. */
    public function getReservePar(): ?string;
}
