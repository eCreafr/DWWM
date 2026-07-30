<?php
declare(strict_types=1);

/**
 * CORRIGÉ NIVEAU 3 — ÉTAPE 2 — Dvd
 *
 * ===================================================================
 * LE POINT ENTIER DE L'EXTENSION EST DANS CE FICHIER :
 * Dvd implémente Empruntable, mais PAS Reservable.
 * ===================================================================
 *
 * Règle métier de Claire : la rotation des DVD est trop rapide pour
 * justifier un système de réservation.
 *
 * Conséquences vérifiables, à faire tester au stagiaire :
 *   - `$dvd instanceof Reservable` retourne false
 *   - `$dvd->reserver('Karim')` produit une Error : méthode inconnue
 *   - le bouton « Réserver » ne s'affiche pas sur les DVD dans index.php
 *
 * Et la question qui va avec :
 *   « Où aurais-tu mis reserver() si tu n'avais pas d'interface ? »
 * → dans Document, et Dvd l'aurait héritée à tort.
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

    public function getRealisateur(): string { return $this->realisateur; }
    public function getDuree(): int          { return $this->duree;       }

    public function getDescription(): string
    {
        return sprintf(
            '%s (%d), réalisé par %s — %d min',
            $this->titre, $this->annee, $this->realisateur, $this->duree
        );
    }

    public function emprunter(): void { $this->disponible = false; }
    public function rendre(): void    { $this->disponible = true;  }
}
