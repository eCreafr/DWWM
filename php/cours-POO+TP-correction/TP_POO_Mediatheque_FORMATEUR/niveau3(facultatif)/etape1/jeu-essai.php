<?php
declare(strict_types=1);

/**
 * ===================================================================
 * CORRIGÉ NIVEAU 3 — ÉTAPE 1 — Jeu d'essai
 * ===================================================================
 * Défi 3 du niveau 3 : « 3 cas passants, 2 cas d'erreur attendus,
 * résultats commentés ».
 *
 * La fonction verifier() est un micro-harnais de test volontairement
 * minimal : 15 lignes, aucune dépendance. Elle prépare le terrain pour
 * PHPUnit sans en imposer l'installation aujourd'hui.
 *
 * Lancement : php jeu-essai.php    → attendu 5/5
 */

require_once __DIR__ . '/Livre.php';

$total = 0;
$reussis = 0;

function verifier(string $intitule, callable $test): void
{
    global $total, $reussis;
    $total++;

    try {
        $test();
        $reussis++;
        echo "  [OK]     $intitule\n";
    } catch (Throwable $e) {
        echo "  [ÉCHEC]  $intitule — " . $e->getMessage() . "\n";
    }
}

echo "=== JEU D'ESSAI — classe Livre (niveau 3) ===\n\n";

// =====================================================================
// 3 CAS PASSANTS
// =====================================================================
echo "Cas passants :\n";

verifier('Instanciation et getters', function () {
    $l = new Livre('Dune', 'Frank Herbert', 1965);

    if ($l->getTitre() !== 'Dune') {
        throw new RuntimeException('getTitre() incorrect');
    }
    if ($l->getAnnee() !== 1965) {
        throw new RuntimeException('getAnnee() incorrect');
    }
});

verifier('ISBN valide accepté et normalisé', function () {
    $l = new Livre('Dune', 'Frank Herbert', 1965);
    $l->setIsbn('978-2-266-32048-1');   // saisi avec tirets

    if ($l->getIsbn() !== '9782266320481') {
        throw new RuntimeException('ISBN non normalisé : ' . var_export($l->getIsbn(), true));
    }
});

verifier('__toString() dans un contexte de chaîne', function () {
    $l = new Livre('Dune', 'Frank Herbert', 1965);

    // Aucun appel explicite : PHP invoque __toString() tout seul.
    $rendu = "$l";

    if ($rendu !== 'Dune (1965), de Frank Herbert') {
        throw new RuntimeException("rendu obtenu : $rendu");
    }
});

// =====================================================================
// 2 CAS D'ERREUR ATTENDUS
// =====================================================================
echo "\nCas d'erreur attendus :\n";

verifier('Titre vide refusé par setTitre()', function () {
    $l = new Livre('Dune', 'Frank Herbert', 1965);

    try {
        $l->setTitre('   ');
        throw new RuntimeException('aucune exception levée — la validation ne marche pas');
    } catch (InvalidArgumentException) {
        // Comportement attendu : le test réussit.
    }
});

verifier('ISBN à clé de contrôle fausse refusé', function () {
    $l = new Livre('Dune', 'Frank Herbert', 1965);

    try {
        // 13 chiffres, format valide, mais dernier chiffre faux.
        $l->setIsbn('9782266320489');
        throw new RuntimeException('aucune exception levée — seul le format est vérifié');
    } catch (InvalidArgumentException) {
        // Comportement attendu.
    }
});

echo "\n--- Résultat : $reussis/$total tests réussis ---\n";

/**
 * COMMENTAIRE DES RÉSULTATS (attendu du stagiaire dans son README) :
 *
 * Les 3 cas passants couvrent le chemin nominal : construction,
 * validation acceptante, conversion implicite en chaîne.
 *
 * Les 2 cas d'erreur vérifient que les GARDES fonctionnent. C'est le
 * point que les stagiaires oublient : un test qui ne vérifie que les
 * cas qui marchent ne prouve presque rien. Ce sont les cas rejetés qui
 * démontrent que la validation est réellement en place.
 *
 * Le second cas d'erreur est le plus instructif : il passe la
 * validation de FORMAT et échoue sur la validation de VALIDITÉ.
 */
