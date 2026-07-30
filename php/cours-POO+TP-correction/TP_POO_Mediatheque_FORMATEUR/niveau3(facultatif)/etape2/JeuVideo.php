<?php
declare(strict_types=1);

/**
 * CORRIGÉ NIVEAU 3 — ÉTAPE 2 — JeuVideo
 * Réservable, comme Livre — et sans aucun lien de parenté avec lui
 * autre que Document. C'est ce que l'interface permet.
 */
class JeuVideo extends Document implements Empruntable, Reservable
{
    use TraitReservation;

    private const PEGI_VALIDES = [3, 7, 12, 16, 18];

    public function __construct(
        string $titre,
        int $annee,
        private string $plateforme,
        private int $pegi
    ) {
        parent::__construct($titre, $annee);
        $this->setPegi($pegi);
    }

    public function getPlateforme(): string { return $this->plateforme; }
    public function getPegi(): int          { return $this->pegi;       }

    public function setPegi(int $pegi): void
    {
        if (!in_array($pegi, self::PEGI_VALIDES, true)) {
            throw new InvalidArgumentException(sprintf(
                'PEGI invalide : %d. Valeurs admises : %s.',
                $pegi, implode(', ', self::PEGI_VALIDES)
            ));
        }

        $this->pegi = $pegi;
    }

    public function getDescription(): string
    {
        return sprintf(
            '%s (%d) — %s — PEGI %d',
            $this->titre, $this->annee, $this->plateforme, $this->pegi
        );
    }

    public function emprunter(): void { $this->disponible = false; }
    public function rendre(): void    { $this->disponible = true;  }
}
