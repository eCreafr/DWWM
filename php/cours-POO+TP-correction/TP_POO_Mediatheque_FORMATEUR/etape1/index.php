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

echo "=== ÉTAPE 1 — Test des classes ===<br><br>";

// ---------- NIVEAU 1 ----------
echo "--- Niveau 1 : instanciation et getters ---<br>";

$livres = [
    new Livre('Dune', 'Frank Herbert', 1965),
    new Livre('Le Comte de Monte-Cristo', 'Alexandre Dumas', 1844),
    new Livre('La Horde du Contrevent', 'Alain Damasio', 2004),
];

foreach ($livres as $livre) {
    echo '- ' . $livre->getTitre()
        . ' | ' . $livre->getAuteur()
        . ' | ' . $livre->getAnnee() . "<br>";
}

echo "<br>Attendu : les 3 livres avec titre, auteur et année.<br><br>";

// ---------- NIVEAU 2 ----------
echo "--- Niveau 2 : méthode afficher() ---<br>";
echo $livres[0]->afficher() . "<br>";
echo "Attendu : Dune (1965), de Frank Herbert<br><br>";

echo "--- Niveau 2 : setter valide ---<br>";
$livres[0]->setTitre('Dune — Le cycle');
echo $livres[0]->getTitre() . "<br>";
echo "Attendu : Dune — Le cycle<br><br>";

echo "--- Niveau 2 : setter invalide (doit lever une exception) ---<br>";
try {
    $livres[0]->setTitre('   ');
    echo "ÉCHEC : aucune exception levée, la validation ne fonctionne pas.<br>";
} catch (InvalidArgumentException $e) {
    echo 'OK — exception interceptée : ' . $e->getMessage() . "<br>";
}

echo "<br>=== Fin des tests ===<br>";
