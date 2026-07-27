<?php
declare(strict_types=1);

/**
 * TP POO — ÉTAPE 2 — NIVEAU 2 — À ÉCRIRE ENTIÈREMENT.
 *
 * Modèle à suivre : Livre.php (fourni dans ce même dossier).
 *
 * CAHIER DES CHARGES
 * ------------------
 * 1. La classe JeuVideo hérite de Document et implémente Empruntable.
 *
 * 2. Propriétés spécifiques (privées) :
 *      - $plateforme  (string)
 *      - $pegi        (int)
 *
 * 3. Constructeur : reçoit titre, annee, plateforme, pegi.
 *    Il DOIT appeler parent::__construct() pour titre et annee.
 *
 * 4. Getters : getPlateforme() et getPegi().
 *
 * 5. getDescription() retourne EXACTEMENT ce format :
 *      Stardew Valley (2016) — PC / Switch — PEGI 7
 *
 * 6. emprunter() et rendre() : même logique que dans Livre.
 *
 * DÉFI SUPPLÉMENTAIRE (facultatif, vers le niveau 3)
 * --------------------------------------------------
 * Ajoutez un setter setPegi() qui refuse toute valeur en dehors
 * de la liste officielle PEGI : 3, 7, 12, 16, 18.
 * Levez une InvalidArgumentException sinon.
 */

// Votre code ici.
