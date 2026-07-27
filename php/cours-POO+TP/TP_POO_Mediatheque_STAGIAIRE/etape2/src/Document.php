<?php
declare(strict_types=1);

/**
 * TP POO — ÉTAPE 2 — Hiérarchie de classes
 *
 * Classe abstraite Document : FOURNIE À 70 %.
 * Un seul TODO à compléter en bas de fichier.
 *
 * Pourquoi « abstract » ?
 * Parce qu'un « document générique » n'existe pas dans la médiathèque
 * de Claire. Il n'y a que des livres, des DVD et des jeux vidéo.
 * Le mot-clé abstract interdit `new Document(...)` et force à passer
 * par une classe fille concrète.
 */
abstract class Document
{
    /**
     * Propriétés en `protected` (et non `private`) :
     * les classes filles doivent pouvoir y accéder directement.
     * Elles restent inaccessibles depuis l'extérieur de la hiérarchie.
     */
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

    /**
     * TODO 1 — Déclarez ici une méthode ABSTRAITE nommée getDescription()
     * qui retourne une string.
     *
     * Rappel de syntaxe : une méthode abstraite n'a PAS de corps.
     * Elle se termine par un point-virgule, pas par des accolades.
     *
     *   abstract public function nomDeLaMethode(): typeDeRetour;
     *
     * Effet attendu : PHP refusera désormais toute classe fille qui
     * n'implémente pas getDescription(). C'est un CONTRAT imposé.
     */

    // TODO 1 : votre déclaration ici
}
