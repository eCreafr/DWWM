<?php
declare(strict_types=1);

/**
 * TP POO — ÉTAPE 2 — Interface Empruntable
 *
 * Une INTERFACE déclare des signatures de méthodes SANS aucun code.
 * Toute classe qui l'implémente s'engage à fournir ces méthodes.
 *
 * Différence avec la classe abstraite Document :
 *   - Document exprime « EST UN »    : un Livre EST UN Document.
 *   - Empruntable exprime « SAIT FAIRE » : un Livre SAIT être emprunté.
 *
 * Une classe n'a qu'UN SEUL parent, mais peut implémenter
 * AUTANT d'interfaces que nécessaire.
 */
interface Empruntable
{
    /**
     * TODO 1 — Déclarez la signature de la méthode emprunter().
     * Elle ne retourne rien : type de retour `void`.
     *
     * Rappel : dans une interface, pas de corps, pas d'accolades,
     * pas de mot-clé `abstract`. Juste la signature + point-virgule.
     */

    // TODO 1 : votre déclaration ici

    /**
     * TODO 2 — Déclarez de même la signature de la méthode rendre().
     */

    // TODO 2 : votre déclaration ici
}
