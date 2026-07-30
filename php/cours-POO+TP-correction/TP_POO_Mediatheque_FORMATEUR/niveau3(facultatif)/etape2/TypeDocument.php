<?php
declare(strict_types=1);

/**
 * ===================================================================
 * CORRIGÉ NIVEAU 3 — ÉTAPE 2 — enum TypeDocument (PHP 8.1)
 * ===================================================================
 * Défi : « remplacer le champ type par un enum et justifier l'apport ».
 *
 * ENUM ADOSSÉ (backed enum) : chaque cas porte une valeur scalaire,
 * qui correspond exactement aux valeurs de la colonne ENUM MySQL.
 * La conversion base <-> code devient triviale dans les deux sens.
 *
 * APPORT À DÉFENDRE DEVANT LE JURY — quatre arguments :
 *   1. une faute de frappe ('livres') devient une erreur détectée,
 *      au lieu d'un bug silencieux à l'exécution ;
 *   2. l'autocomplétion de l'IDE fonctionne ;
 *   3. les valeurs possibles sont documentées par le code lui-même ;
 *   4. un `match` sur un enum est exhaustif : PHP lève une
 *      UnhandledMatchError si un cas n'est pas traité, ce qui force
 *      la mise à jour de tout le code le jour où un type est ajouté.
 */
enum TypeDocument: string
{
    case Livre    = 'livre';
    case Dvd      = 'dvd';
    case JeuVideo = 'jeu_video';

    public function libelle(): string
    {
        return match ($this) {
            self::Livre    => 'Livre',
            self::Dvd      => 'DVD',
            self::JeuVideo => 'Jeu vidéo',
        };
    }

    public function couleurBadge(): string
    {
        return match ($this) {
            self::Livre    => 'text-bg-primary',
            self::Dvd      => 'text-bg-warning',
            self::JeuVideo => 'text-bg-info',
        };
    }

    /** Règle métier : les DVD ne se réservent pas. */
    public function estReservable(): bool
    {
        return match ($this) {
            self::Livre, self::JeuVideo => true,
            self::Dvd                   => false,
        };
    }

    /**
     * Déduit le type depuis un objet métier.
     * Alternative à un champ stocké : le type EST la classe.
     */
    public static function depuisObjet(Document $document): self
    {
        return match (true) {
            $document instanceof Livre    => self::Livre,
            $document instanceof Dvd      => self::Dvd,
            $document instanceof JeuVideo => self::JeuVideo,
            default => throw new RuntimeException(
                'Type inconnu : ' . get_class($document)
            ),
        };
    }
}

// USAGE
//   Depuis la base : TypeDocument::from($row['type'])       -> lève si inconnu
//                    TypeDocument::tryFrom($row['type'])    -> null si inconnu
//   Vers la base   : $stmt->execute(['type' => $type->value]);
//   Affichage      : echo $type->libelle();
//   Liste complète : foreach (TypeDocument::cases() as $t) { ... }
