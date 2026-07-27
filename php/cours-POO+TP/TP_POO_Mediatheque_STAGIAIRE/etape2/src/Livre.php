<?php
declare(strict_types=1);

/**
 * TP POO — ÉTAPE 2 — Classe Livre, version héritée.
 *
 * FOURNIE INTÉGRALEMENT : elle vous sert de MODÈLE pour écrire
 * Dvd.php et JeuVideo.php par vous-même.
 *
 * Comparez-la avec votre Livre.php de l'étape 1 : le titre et l'année
 * ont disparu des propriétés — ils sont maintenant portés par le parent.
 * C'est ça, factoriser.
 */
class Livre extends Document implements Empruntable
{
    public function __construct(
        string $titre,
        int $annee,
        private string $auteur      // propriété SPÉCIFIQUE au livre
    ) {
        // Délégation au constructeur du parent pour les propriétés communes.
        // Oublier cette ligne = titre et annee jamais initialisés = erreur fatale.
        parent::__construct($titre, $annee);
    }

    public function getAuteur(): string
    {
        return $this->auteur;
    }

    /**
     * Implémentation OBLIGATOIRE de la méthode abstraite du parent.
     * Chaque type de document a sa propre façon de se décrire :
     * c'est le POLYMORPHISME.
     */
    public function getDescription(): string
    {
        return sprintf(
            '%s (%d), de %s',
            $this->titre,       // accessible car protected dans le parent
            $this->annee,
            $this->auteur
        );
    }

    // --- Contrat Empruntable ---

    public function emprunter(): void
    {
        $this->disponible = false;
    }

    public function rendre(): void
    {
        $this->disponible = true;
    }
}
