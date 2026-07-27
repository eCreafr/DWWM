<?php
declare(strict_types=1);

/**
 * TP POO — ÉTAPE 2 — Fichier de test. FOURNI, ne pas modifier.
 *
 * Lancement : php index.php
 */

require_once __DIR__ . '/src/Document.php';
require_once __DIR__ . '/src/Empruntable.php';
require_once __DIR__ . '/src/Livre.php';
require_once __DIR__ . '/src/Dvd.php';
require_once __DIR__ . '/src/JeuVideo.php';

echo "=== ÉTAPE 2 — Hiérarchie de classes ===\n\n";

// ---------- NIVEAU 1 : la classe abstraite n'est pas instanciable ----------
echo "--- Niveau 1 : Document est abstraite ---\n";
try {
    // @phpstan-ignore-next-line — erreur volontaire, c'est le test
    $doc = new Document('Test', 2024);
    echo "ÉCHEC : Document a pu être instanciée, le mot-clé abstract manque.\n";
} catch (Error $e) {
    echo "OK — instanciation refusée : " . $e->getMessage() . "\n";
}
echo "\n";

// ---------- NIVEAU 2 : le tableau mixte ----------
echo "--- Niveau 2 : polymorphisme sur 6 documents ---\n";

$catalogue = [
    new Livre('Dune', 1965, 'Frank Herbert'),
    new Livre('Sapiens', 2011, 'Yuval Noah Harari'),
    new Dvd('Intouchables', 2011, 'Toledano & Nakache', 112),
    new Dvd('Le Voyage de Chihiro', 2001, 'Hayao Miyazaki', 125),
    new JeuVideo('Stardew Valley', 2016, 'PC / Switch', 7),
    new JeuVideo('Hollow Knight', 2017, 'PC / Switch', 7),
];

// UNE SEULE BOUCLE, TROIS FORMATS DE SORTIE.
// PHP choisit la bonne implémentation selon le type réel de l'objet.
foreach ($catalogue as $document) {
    echo '- ' . $document->getDescription() . "\n";
}

echo "\nAttendu : 6 lignes, 3 formats différents.\n\n";

// ---------- NIVEAU 2 : le contrat Empruntable ----------
echo "--- Niveau 2 : emprunter / rendre ---\n";

$cible = $catalogue[0];
echo 'État initial  : ' . ($cible->estDisponible() ? 'disponible' : 'emprunté') . "\n";

$cible->emprunter();
echo 'Après emprunt : ' . ($cible->estDisponible() ? 'disponible' : 'emprunté') . "\n";

$cible->rendre();
echo 'Après retour  : ' . ($cible->estDisponible() ? 'disponible' : 'emprunté') . "\n";

echo "\nAttendu : disponible / emprunté / disponible\n\n";

// ---------- Vérification des contrats ----------
echo "--- Vérification des types ---\n";
foreach ($catalogue as $document) {
    printf(
        "%-24s Document:%s  Empruntable:%s\n",
        get_class($document),
        $document instanceof Document ? 'oui' : 'NON',
        $document instanceof Empruntable ? 'oui' : 'NON'
    );
}

echo "\nAttendu : oui / oui sur les 6 lignes.\n";
echo "\n=== Fin des tests ===\n";
