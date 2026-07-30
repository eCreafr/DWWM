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

echo "=== ÉTAPE 2 — Hiérarchie de classes ===<br>";

// =====================================================================
// NIVEAU 1 — Document abstraite, Empruntable, Livre migré
// =====================================================================

echo "<br>########## NIVEAU 1 ##########<br><br>";

// --- Test 1 : Document doit être abstraite ---
echo "--- 1. Document est-elle abstraite ? ---<br>";
try {
    $reflexion = new ReflectionClass('Document');
    if ($reflexion->isAbstract()) {
        echo "OK — Document est bien abstraite.<br>";
    } else {
        echo "ÉCHEC — il manque le mot-clé abstract devant class Document.<br>";
    }
} catch (ReflectionException $e) {
    echo "ÉCHEC — classe Document introuvable.<br>";
}

// --- Test 2 : la méthode abstraite est déclarée ---
echo "<br>--- 2. getDescription() est-elle déclarée abstraite ? ---<br>";
$reflexion = new ReflectionClass('Document');
if ($reflexion->hasMethod('getDescription')) {
    echo "OK — la déclaration abstraite est présente (TODO 1 de Document.php).<br>";
} else {
    echo "ÉCHEC — TODO 1 de Document.php non fait : la méthode abstraite manque.<br>";
}

// --- Test 3 : l'interface impose bien deux méthodes ---
echo "<br>--- 3. L'interface Empruntable est-elle complète ? ---<br>";
$methodes = (new ReflectionClass('Empruntable'))->getMethods();
$noms = array_map(fn($m) => $m->getName(), $methodes);
sort($noms);
if ($noms === ['emprunter', 'rendre']) {
    echo "OK — emprunter() et rendre() sont déclarées.<br>";
} else {
    echo "ÉCHEC — méthodes trouvées : "
        . ($noms === [] ? '(aucune)' : implode(', ', $noms))
        . ". Attendu : emprunter, rendre.<br>";
}

// --- Test 4 : Livre hérite et implémente ---
echo "<br>--- 4. Livre est-elle correctement raccordée ? ---<br>";
$livreOk = false;
try {
    $livre = new Livre('Dune', 1965, 'Frank Herbert');

    $heriteBien  = $livre instanceof Document;
    $implementeBien = $livre instanceof Empruntable;

    echo 'Hérite de Document      : ' . ($heriteBien ? 'oui' : 'NON') . "<br>";
    echo 'Implémente Empruntable  : ' . ($implementeBien ? 'oui' : 'NON') . "<br>";

    // getTitre() n'est PAS écrite dans Livre : elle vient du parent.
    echo 'getTitre() héritée      : ' . $livre->getTitre() . "<br>";
    echo 'getAnnee() héritée      : ' . $livre->getAnnee() . "<br>";
    echo 'getAuteur() propre      : ' . $livre->getAuteur() . "<br>";
    echo 'getDescription()        : ' . $livre->getDescription() . "<br>";
    echo "Attendu ci-dessus       : Dune (1965), de Frank Herbert<br>";

    echo "<br>emprunter() / rendre() :<br>";
    echo '  état initial  : ' . ($livre->estDisponible() ? 'disponible' : 'emprunté') . "<br>";
    $livre->emprunter();
    echo '  après emprunt : ' . ($livre->estDisponible() ? 'disponible' : 'emprunté') . "<br>";
    $livre->rendre();
    echo '  après retour  : ' . ($livre->estDisponible() ? 'disponible' : 'emprunté') . "<br>";
    echo "  attendu       : disponible / emprunté / disponible<br>";

    $livreOk = $heriteBien && $implementeBien;

} catch (Throwable $e) {
    echo 'ÉCHEC — ' . get_class($e) . ' : ' . $e->getMessage() . "<br>";
    echo "Reprenez les TODO de Livre.php dans l'ordre.<br>";
}

if (!$livreOk) {
    echo "<br>>>> Niveau 1 non terminé. Corrigez avant de passer au niveau 2. <<<<br>";
    exit;
}

echo "<br>>>> NIVEAU 1 VALIDÉ. Passez à Dvd.php et JeuVideo.php. <<<<br>";

// =====================================================================
// NIVEAU 2 — Dvd, JeuVideo, polymorphisme
// =====================================================================

echo "<br>########## NIVEAU 2 ##########<br><br>";

$manquantes = array_filter(
    ['Dvd', 'JeuVideo'],
    fn(string $c): bool => !class_exists($c)
);

if ($manquantes !== []) {
    echo 'Classes pas encore écrites : ' . implode(', ', $manquantes) . ".<br>";
    echo "C'est normal si vous démarrez le niveau 2. Relancez ce test<br>";
    echo "au fur et à mesure : il reprendra ici.<br>";
    exit;
}

echo "--- 5. Polymorphisme sur 6 documents ---<br>";
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
        echo '- ' . $document->getDescription() . "<br>";
    }

    echo "<br>Attendu : 6 lignes, 3 formats différents.<br>";

    echo "<br>--- 6. Vérification des contrats ---<br>";
    $tousOk = true;
    foreach ($catalogue as $document) {
        $d = $document instanceof Document;
        $e = $document instanceof Empruntable;
        $tousOk = $tousOk && $d && $e;
        printf(
            "%-12s Document:%-4s Empruntable:%s<br>",
            get_class($document),
            $d ? 'oui' : 'NON',
            $e ? 'oui' : 'NON'
        );
    }

    echo "<br>" . ($tousOk
        ? ">>> NIVEAU 2 VALIDÉ. Passez au niveau 3 ou aidez un binôme. <<<<br>"
        : ">>> Un extends ou un implements manque. Reprenez. <<<<br>");

} catch (Throwable $e) {
    echo 'ÉCHEC — ' . get_class($e) . ' : ' . $e->getMessage() . "<br>";
}

echo "<br>=== Fin des tests ===<br>";
