<?php
declare(strict_types=1);

/**
 * ===================================================================
 * CORRIGÉ FORMATEUR — ÉTAPE 2 — Classe JeuVideo
 * ===================================================================
 * Inclut le défi facultatif : validation PEGI par liste blanche.
 */
class JeuVideo extends Document implements Empruntable
{
    /** Valeurs officielles de la classification PEGI. */
    private const PEGI_VALIDES = [3, 7, 12, 16, 18];

    public function __construct(
        string $titre,
        int $annee,
        private string $plateforme,
        private int $pegi
    ) {
        parent::__construct($titre, $annee);

        // Validation dès la construction : un objet ne doit jamais
        // pouvoir exister dans un état invalide.
        $this->setPegi($pegi);
    }

    public function getPlateforme(): string
    {
        return $this->plateforme;
    }

    public function getPegi(): int
    {
        return $this->pegi;
    }

    /** Défi facultatif — liste blanche. */
    public function setPegi(int $pegi): void
    {
        if (!in_array($pegi, self::PEGI_VALIDES, true)) {
            throw new InvalidArgumentException(
                sprintf('PEGI invalide : %d. Valeurs admises : %s.',
                    $pegi,
                    implode(', ', self::PEGI_VALIDES)
                )
            );
        }

        $this->pegi = $pegi;
    }

    public function getDescription(): string
    {
        return sprintf(
            '%s (%d) — %s — PEGI %d',
            $this->titre,
            $this->annee,
            $this->plateforme,
            $this->pegi
        );
    }

    public function emprunter(): void
    {
        $this->disponible = false;
    }

    public function rendre(): void
    {
        $this->disponible = true;
    }
}
