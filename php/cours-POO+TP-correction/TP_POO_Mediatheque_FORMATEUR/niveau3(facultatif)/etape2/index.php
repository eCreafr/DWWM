<?php
declare(strict_types=1);

/**
 * ===================================================================
 * CORRIGÉ NIVEAU 3 — ÉTAPE 2 — Démonstration instanceof
 * ===================================================================
 * Défi : « utiliser instanceof pour n'afficher le bouton "réserver"
 * que sur les documents réservables ».
 *
 * Lancement : php index.php    (sortie console, pas besoin de base)
 */

require_once __DIR__ . '/Document.php';
require_once __DIR__ . '/Empruntable.php';
require_once __DIR__ . '/Reservable.php';
require_once __DIR__ . '/TraitReservation.php';
require_once __DIR__ . '/Livre.php';
require_once __DIR__ . '/Dvd.php';
require_once __DIR__ . '/JeuVideo.php';
require_once __DIR__ . '/TypeDocument.php';

$catalogue = [
    new Livre('Dune', 1965, 'Frank Herbert'),
    new Dvd('Intouchables', 2011, 'Toledano & Nakache', 112),
    new JeuVideo('Stardew Valley', 2016, 'PC / Switch', 7),
];

echo "=== NIVEAU 3 — Multi-contrat et instanceof ===\n\n";

// =====================================================================
// 1. Qui implémente quoi ?
// =====================================================================
echo "--- 1. Contrats portés par chaque classe ---\n";
printf("%-12s %-12s %-12s %s\n", 'Classe', 'Document', 'Empruntable', 'Reservable');
foreach ($catalogue as $doc) {
    printf(
        "%-12s %-12s %-12s %s\n",
        get_class($doc),
        $doc instanceof Document    ? 'oui' : 'non',
        $doc instanceof Empruntable ? 'oui' : 'non',
        $doc instanceof Reservable  ? 'oui' : 'NON'
    );
}
echo "\nLe 'NON' sur Dvd/Reservable est le résultat attendu :\n";
echo "c'est toute la raison d'être de cette extension.\n\n";

// =====================================================================
// 2. Le rendu conditionnel — équivalent console du bouton
// =====================================================================
echo "--- 2. Actions proposées à l'utilisateur ---\n";
foreach ($catalogue as $doc) {

    $actions = [];

    if ($doc instanceof Empruntable && $doc->estDisponible()) {
        $actions[] = 'Emprunter';
    }

    // C'EST LA LIGNE CLÉ DE L'EXTENSION.
    // Le bouton n'est pas piloté par un champ 'type' en base ni par un
    // if sur get_class() : il est piloté par le CONTRAT que la classe
    // porte. Ajouter demain un type Periodique réservable ne demandera
    // aucune modification ici.
    if ($doc instanceof Reservable && !$doc->estReserve()) {
        $actions[] = 'Réserver';
    }

    printf("  %-45s [ %s ]\n", $doc->getDescription(), implode(' | ', $actions));
}

echo "\nAttendu : 'Réserver' absent sur Intouchables uniquement.\n\n";

// =====================================================================
// 3. Le comportement de réservation
// =====================================================================
echo "--- 3. Mécanique de réservation ---\n";

$dune = $catalogue[0];
$dune->reserver('Karim');
echo 'Dune réservé par : ' . $dune->getReservePar() . "\n";

try {
    $dune->reserver('Claire');
    echo "ÉCHEC : la double réservation aurait dû être refusée.\n";
} catch (RuntimeException $e) {
    echo 'OK — double réservation refusée : ' . $e->getMessage() . "\n";
}

$dune->annulerReservation();
echo 'Après annulation, réservé : ' . ($dune->estReserve() ? 'oui' : 'non') . "\n\n";

// =====================================================================
// 4. Ce que PHP interdit sur un Dvd
// =====================================================================
echo "--- 4. Tentative de réservation d'un DVD ---\n";
$dvd = $catalogue[1];

if ($dvd instanceof Reservable) {
    echo "ÉCHEC : Dvd ne devrait pas implémenter Reservable.\n";
} else {
    echo "OK — Dvd n'implémente pas Reservable, le test instanceof le filtre.\n";
}

try {
    // @phpstan-ignore-next-line — appel volontairement invalide
    $dvd->reserver('Karim');
    echo "ÉCHEC : l'appel aurait dû échouer.\n";
} catch (Error $e) {
    echo 'OK — appel impossible : ' . $e->getMessage() . "\n";
}

// =====================================================================
// 5. Cohérence avec l'enum
// =====================================================================
echo "\n--- 5. Cohérence enum / interface ---\n";
foreach ($catalogue as $doc) {
    $type = TypeDocument::depuisObjet($doc);
    $viaEnum      = $type->estReservable();
    $viaInterface = $doc instanceof Reservable;

    printf(
        "  %-10s enum:%-5s interface:%-5s %s\n",
        $type->libelle(),
        $viaEnum ? 'oui' : 'non',
        $viaInterface ? 'oui' : 'non',
        $viaEnum === $viaInterface ? 'cohérent' : 'INCOHÉRENT'
    );
}

echo "\n[POINT DE DISCUSSION À OUVRIR EN CLASSE]\n";
echo "Les deux sources disent la même chose — donc l'une des deux est\n";
echo "redondante. Laquelle garder ? L'interface : elle est vérifiée par\n";
echo "PHP au chargement de la classe, alors que la méthode de l'enum est\n";
echo "une liste à maintenir à la main, qui peut diverger silencieusement.\n";

echo "\n=== Fin ===\n";
