<?php
declare(strict_types=1);

/**
 * TP POO — ÉTAPE 2 — Fichier de test. FOURNI, ne pas modifier.
 *
 * Ce test est PROGRESSIF : il s'adapte à votre avancement.
 * Vous pouvez le lancer dès le niveau 1, sans avoir écrit Dvd ni JeuVideo.
 *
 * Lancement : php index.php
 */

require_once __DIR__ . '/src/Document.php';
require_once __DIR__ . '/src/Empruntable.php';
require_once __DIR__ . '/src/Livre.php';
require_once __DIR__ . '/src/Dvd.php';
require_once __DIR__ . '/src/JeuVideo.php';

echo "=== ÉTAPE 2 — Hiérarchie de classes ===\n";

// =====================================================================
// NIVEAU 1 — Document abstraite, Empruntable, Livre migré
// =====================================================================

echo "\n########## NIVEAU 1 ##########\n\n";

// --- Test 1 : Document doit être abstraite ---
echo "--- 1. Document est-elle abstraite ? ---\n";
try {
    $reflexion = new ReflectionClass('Document');
    if ($reflexion->isAbstract()) {
        echo "OK — Document est bien abstraite.\n";
    } else {
        echo "ÉCHEC — il manque le mot-clé abstract devant class Document.\n";
    }
} catch (ReflectionException $e) {
    echo "ÉCHEC — classe Document introuvable.\n";
}

// --- Test 2 : la méthode abstraite est déclarée ---
echo "\n--- 2. getDescription() est-elle déclarée abstraite ? ---\n";
$reflexion = new ReflectionClass('Document');
if ($reflexion->hasMethod('getDescription')) {
    echo "OK — la déclaration abstraite est présente (TODO 1 de Document.php).\n";
} else {
    echo "ÉCHEC — TODO 1 de Document.php non fait : la méthode abstraite manque.\n";
}

// --- Test 3 : l'interface impose bien deux méthodes ---
echo "\n--- 3. L'interface Empruntable est-elle complète ? ---\n";
$methodes = (new ReflectionClass('Empruntable'))->getMethods();
$noms = array_map(fn($m) => $m->getName(), $methodes);
sort($noms);
if ($noms === ['emprunter', 'rendre']) {
    echo "OK — emprunter() et rendre() sont déclarées.\n";
} else {
    echo "ÉCHEC — méthodes trouvées : "
        . ($noms === [] ? '(aucune)' : implode(', ', $noms))
        . ". Attendu : emprunter, rendre.\n";
}

// --- Test 4 : Livre hérite et implémente ---
echo "\n--- 4. Livre est-elle correctement raccordée ? ---\n";
$livreOk = false;
try {
    $livre = new Livre('Dune', 1965, 'Frank Herbert');

    $heriteBien  = $livre instanceof Document;
    $implementeBien = $livre instanceof Empruntable;

    echo 'Hérite de Document      : ' . ($heriteBien ? 'oui' : 'NON') . "\n";
    echo 'Implémente Empruntable  : ' . ($implementeBien ? 'oui' : 'NON') . "\n";

    // getTitre() n'est PAS écrite dans Livre : elle vient du parent.
    echo 'getTitre() héritée      : ' . $livre->getTitre() . "\n";
    echo 'getAnnee() héritée      : ' . $livre->getAnnee() . "\n";
    echo 'getAuteur() propre      : ' . $livre->getAuteur() . "\n";
    echo 'getDescription()        : ' . $livre->getDescription() . "\n";
    echo "Attendu ci-dessus       : Dune (1965), de Frank Herbert\n";

    echo "\nemprunter() / rendre() :\n";
    echo '  état initial  : ' . ($livre->estDisponible() ? 'disponible' : 'emprunté') . "\n";
    $livre->emprunter();
    echo '  après emprunt : ' . ($livre->estDisponible() ? 'disponible' : 'emprunté') . "\n";
    $livre->rendre();
    echo '  après retour  : ' . ($livre->estDisponible() ? 'disponible' : 'emprunté') . "\n";
    echo "  attendu       : disponible / emprunté / disponible\n";

    $livreOk = $heriteBien && $implementeBien;

} catch (Throwable $e) {
    echo 'ÉCHEC — ' . get_class($e) . ' : ' . $e->getMessage() . "\n";
    echo "Reprenez les TODO de Livre.php dans l'ordre.\n";
}

if (!$livreOk) {
    echo "\n>>> Niveau 1 non terminé. Corrigez avant de passer au niveau 2. <<<\n";
    exit;
}

echo "\n>>> NIVEAU 1 VALIDÉ. Passez à Dvd.php et JeuVideo.php. <<<\n";

// =====================================================================
// NIVEAU 2 — Dvd, JeuVideo, polymorphisme
// =====================================================================

echo "\n########## NIVEAU 2 ##########\n\n";

$manquantes = array_filter(
    ['Dvd', 'JeuVideo'],
    fn(string $c): bool => !class_exists($c)
);

if ($manquantes !== []) {
    echo 'Classes pas encore écrites : ' . implode(', ', $manquantes) . ".\n";
    echo "C'est normal si vous démarrez le niveau 2. Relancez ce test\n";
    echo "au fur et à mesure : il reprendra ici.\n";
    exit;
}

echo "--- 5. Polymorphisme sur 6 documents ---\n";
try {
    $catalogue = [
        new Livre('Dune', 1965, 'Frank Herbert'),
        new Livre('Sapiens', 2011, 'Yuval Noah Harari'),
        new Dvd('Intouchables', 2011, 'Toledano & Nakache', 112),
        new Dvd('Le Voyage de Chihiro', 2001, 'Hayao Miyazaki', 125),
        new JeuVideo('Stardew Valley', 2016, 'PC / Switch', 7),
        new JeuVideo('Hollow Knight', 2017, 'PC / Switch', 7),
    ];

    // UNE SEULE BOUCLE, TROIS FORMATS DE SORTIE.
    foreach ($catalogue as $document) {
        echo '- ' . $document->getDescription() . "\n";
    }

    echo "\nAttendu : 6 lignes, 3 formats différents.\n";

    echo "\n--- 6. Vérification des contrats ---\n";
    $tousOk = true;
    foreach ($catalogue as $document) {
        $d = $document instanceof Document;
        $e = $document instanceof Empruntable;
        $tousOk = $tousOk && $d && $e;
        printf(
            "%-12s Document:%-4s Empruntable:%s\n",
            get_class($document),
            $d ? 'oui' : 'NON',
            $e ? 'oui' : 'NON'
        );
    }

    echo "\n" . ($tousOk
        ? ">>> NIVEAU 2 VALIDÉ. Passez au niveau 3 ou aidez un binôme. <<<\n"
        : ">>> Un extends ou un implements manque. Reprenez. <<<\n");

} catch (Throwable $e) {
    echo 'ÉCHEC — ' . get_class($e) . ' : ' . $e->getMessage() . "\n";
}

echo "\n=== Fin des tests ===\n";
