<?php
declare(strict_types=1);

/**
 * ===================================================================
 * CORRIGÉ NIVEAU 3 — ÉTAPE 2 — Trait TraitReservation
 * ===================================================================
 * BONUS non demandé par le cours, à ne montrer qu'aux stagiaires qui
 * posent la bonne question :
 *
 *   « Livre et JeuVideo vont avoir exactement le même code de
 *     réservation. On ne peut pas le factoriser ? »
 *
 * Réponse : pas par héritage (une seule classe parente, et Dvd ne doit
 * pas l'avoir). C'est le cas d'usage exact du TRAIT.
 *
 * DISTINCTION À FAIRE VERBALISER — les trois mécanismes :
 *
 *   extends    → « EST UN »      : hérite d'un type ET du code
 *   implements → « SAIT FAIRE »  : impose un contrat, sans code
 *   use (trait)→ « PARTAGE »     : injecte du code, sans créer de type
 *
 * Un trait n'est PAS un type : `$objet instanceof TraitReservation`
 * est une erreur de syntaxe. C'est pour ça qu'on garde l'interface
 * Reservable À CÔTÉ du trait — l'interface donne le type, le trait
 * donne l'implémentation.
 *
 * [PIÈGE] Le trait est souvent présenté comme « du multi-héritage en
 * PHP ». C'est faux et le jury peut relever la formulation. Un trait
 * est de la copie de code à la compilation, rien de plus.
 */
trait TraitReservation
{
    private ?string $reservePar = null;

    public function reserver(string $adherent): void
    {
        if (trim($adherent) === '') {
            throw new InvalidArgumentException("Nom d'adhérent vide.");
        }

        if ($this->reservePar !== null) {
            throw new RuntimeException(
                'Document déjà réservé par ' . $this->reservePar . '.'
            );
        }

        $this->reservePar = trim($adherent);
    }

    public function annulerReservation(): void
    {
        $this->reservePar = null;
    }

    public function estReserve(): bool
    {
        return $this->reservePar !== null;
    }

    public function getReservePar(): ?string
    {
        return $this->reservePar;
    }
}
