<?php
declare(strict_types=1);

/**
 * TP POO — ÉTAPE 2 — NIVEAU 2 — À ÉCRIRE ENTIÈREMENT.
 *
 * Modèle à suivre : VOTRE Livre.php, que vous venez de compléter au niveau 1.
 * Si votre Livre fonctionne, Dvd suit exactement la même mécanique.
 *
 * CAHIER DES CHARGES
 * ------------------
 * 1. La classe Dvd hérite de Document et implémente Empruntable.
 *
 * 2. Propriétés spécifiques (privées) :
 *      - $realisateur  (string)
 *      - $duree        (int, en minutes)
 *
 * 3. Constructeur : reçoit titre, annee, realisateur, duree.
 *    Il DOIT appeler parent::__construct() pour titre et annee.
 *
 * 4. Getters : getRealisateur() et getDuree().
 *
 * 5. getDescription() retourne EXACTEMENT ce format :
 *      Intouchables (2011), réalisé par Toledano & Nakache — 112 min
 *
 * 6. emprunter() et rendre() : même logique que dans Livre.
 *
 * CONTRAINTES
 * -----------
 *   - Typage strict sur toutes les signatures.
 *   - Aucune propriété en public.
 *   - Aucun echo dans la classe.
 */

// Votre code ici.
