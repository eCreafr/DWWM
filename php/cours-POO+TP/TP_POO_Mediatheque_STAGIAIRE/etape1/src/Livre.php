<?php
declare(strict_types=1);

/**
 * TP POO — Médiathèque « La Grande Ourse »
 * ÉTAPE 1 — Premières classes
 *
 * Squelette à compléter. Remplacez chaque zone « TODO » par votre code.
 * Ne modifiez PAS les signatures de méthodes déjà écrites : les fichiers
 * de test s'appuient dessus.
 *
 * NIVEAU 1 : TODO 1 à 3
 * NIVEAU 2 : TODO 4 et 5 (+ retirer les valeurs par défaut, voir consigne)
 */
class Livre
{
    /**
     * Constructeur — FOURNI, ne pas modifier.
     *
     * Il utilise la « promotion de propriétés » de PHP 8 : chaque paramètre
     * précédé d'un mot-clé de visibilité (ici private) déclare ET initialise
     * automatiquement une propriété du même nom.
     *
     * Écrire ceci :          revient à écrire ceci :
     *   private string $titre    private string $titre;
     *                            public function __construct(string $titre) {
     *                                $this->titre = $titre;
     *                            }
     */
    public function __construct(
        private string $titre,
        private string $auteur,
        private int $annee
    ) {}

    // =================================================================
    // NIVEAU 1 — Les accesseurs (getters)
    // =================================================================

    /**
     * TODO 1 — Retournez la valeur de la propriété $titre.
     *
     * Rappel : à l'intérieur d'une méthode, on accède à une propriété
     * de l'objet courant avec $this->nomDeLaPropriete
     */
    public function getTitre(): string
    {
        // TODO 1 : votre code ici
    }

    /**
     * TODO 2 — Sur le même modèle, retournez l'auteur.
     */
    public function getAuteur(): string
    {
        // TODO 2 : votre code ici
    }

    /**
     * TODO 3 — Sur le même modèle, retournez l'année.
     * Attention au type de retour déclaré dans la signature.
     */
    public function getAnnee(): int
    {
        // TODO 3 : votre code ici
    }

    // =================================================================
    // NIVEAU 2 — Mutateur avec validation + méthode métier
    // =================================================================

    /**
     * TODO 4 — Mutateur (setter) du titre AVEC VALIDATION.
     *
     * Comportement attendu :
     *   - si le titre reçu est vide ou ne contient que des espaces,
     *     lever une InvalidArgumentException avec le message
     *     « Le titre ne peut pas être vide. »
     *   - sinon, affecter la valeur à la propriété.
     *
     * Fonctions utiles : trim(), throw new InvalidArgumentException(...)
     *
     * C'est CE type de contrôle que le REAC appelle
     * « composant métier sécurisé » (CP7).
     */
    public function setTitre(string $titre): void
    {
        // TODO 4 : votre code ici
    }

    /**
     * TODO 5 — Retournez une description formatée du livre, au format :
     *   Dune (1965), de Frank Herbert
     *
     * RÈGLE IMPORTANTE : cette méthode RETOURNE une chaîne.
     * Elle ne doit contenir AUCUN echo. Une classe métier ne doit
     * jamais afficher : elle fournit des données, c'est l'appelant
     * qui décide de l'affichage. (Ce principe prépare le MVC.)
     */
    public function afficher(): string
    {
        // TODO 5 : votre code ici
    }
}
