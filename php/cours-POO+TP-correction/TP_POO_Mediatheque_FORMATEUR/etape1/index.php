<?php
declare(strict_types=1);

/**
 * TP POO — ÉTAPE 1 — Fichier de test.
 * FOURNI : ne le modifiez pas tant que les TODO de Livre.php
 * ne passent pas tous au vert.
 *
 * Lancement : php index.php   (ou via le navigateur)
 */

require_once __DIR__ . '/src/Livre.php';

echo "=== ÉTAPE 1 — Test des classes ===\n\n";

// ---------- NIVEAU 1 ----------
echo "--- Niveau 1 : instanciation et getters ---\n";

$livres = [
    new Livre('Dune', 'Frank Herbert', 1965),
    new Livre('Le Comte de Monte-Cristo', 'Alexandre Dumas', 1844),
    new Livre('La Horde du Contrevent', 'Alain Damasio', 2004),
];

foreach ($livres as $livre) {
    echo '- ' . $livre->getTitre()
        . ' | ' . $livre->getAuteur()
        . ' | ' . $livre->getAnnee() . "\n";
}

echo "\nAttendu : les 3 livres avec titre, auteur et année.\n\n";

// ---------- NIVEAU 2 ----------
echo "--- Niveau 2 : méthode afficher() ---\n";
echo $livres[0]->afficher() . "\n";
echo "Attendu : Dune (1965), de Frank Herbert\n\n";

echo "--- Niveau 2 : setter valide ---\n";
$livres[0]->setTitre('Dune — Le cycle');
echo $livres[0]->getTitre() . "\n";
echo "Attendu : Dune — Le cycle\n\n";

echo "--- Niveau 2 : setter invalide (doit lever une exception) ---\n";
try {
    $livres[0]->setTitre('   ');
    echo "ÉCHEC : aucune exception levée, la validation ne fonctionne pas.\n";
} catch (InvalidArgumentException $e) {
    echo 'OK — exception interceptée : ' . $e->getMessage() . "\n";
}

echo "\n=== Fin des tests ===\n";
