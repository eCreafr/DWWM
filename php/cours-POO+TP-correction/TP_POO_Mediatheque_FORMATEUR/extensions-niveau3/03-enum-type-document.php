<?php
declare(strict_types=1);

/**
 * ===================================================================
 * EXTENSION NIVEAU 3 — ÉTAPE 2 — ENUM PHP 8.1
 * ===================================================================
 * Objectif : remplacer les chaînes 'livre' / 'dvd' / 'jeu_video'
 * par un type énuméré.
 *
 * Apport à défendre devant le jury :
 *   - une faute de frappe ('livres') devient une erreur détectée par
 *     PHP, au lieu d'un bug silencieux à l'exécution ;
 *   - l'autocomplétion de l'IDE fonctionne ;
 *   - les valeurs possibles sont documentées par le code lui-même ;
 *   - le `match` sur un enum est exhaustif : ajouter un cas oblige à
 *     traiter partout où l'enum est utilisé.
 */

/**
 * Enum ADOSSÉ (backed enum) : chaque cas a une valeur scalaire.
 * Elle correspond exactement aux valeurs de la colonne ENUM MySQL,
 * ce qui rend la conversion base ↔ code triviale.
 */
enum TypeDocument: string
{
    case Livre    = 'livre';
    case Dvd      = 'dvd';
    case JeuVideo = 'jeu_video';

    /** Libellé destiné à l'affichage. */
    public function libelle(): string
    {
        return match ($this) {
            self::Livre    => 'Livre',
            self::Dvd      => 'DVD',
            self::JeuVideo => 'Jeu vidéo',
        };
    }

    /** Classe CSS Bootstrap associée, pour un badge coloré. */
    public function couleurBadge(): string
    {
        return match ($this) {
            self::Livre    => 'text-bg-primary',
            self::Dvd      => 'text-bg-warning',
            self::JeuVideo => 'text-bg-info',
        };
    }
}

// ---------------------------------------------------------------
// USAGE
// ---------------------------------------------------------------

// Depuis la base : from() lève une ValueError si la valeur est inconnue.
// $type = TypeDocument::from($row['type']);

// Version tolérante : tryFrom() retourne null au lieu de lever.
// $type = TypeDocument::tryFrom($row['type']) ?? TypeDocument::Livre;

// Vers la base : on récupère la valeur scalaire.
// $stmt->execute(['type' => $type->value]);

// Affichage :
// echo $type->libelle();

// Parcourir tous les cas (utile pour générer un <select>) :
foreach (TypeDocument::cases() as $type) {
    echo $type->value . ' => ' . $type->libelle() . PHP_EOL;
}
