<?php
declare(strict_types=1);

/**
 * ===================================================================
 * EXTENSION NIVEAU 3 — ÉTAPE 1 — ISBN, __toString() et jeu d'essai
 * ===================================================================
 */

class LivreAvance
{
    private ?string $isbn = null;

    public function __construct(
        private string $titre,
        private string $auteur,
        private int $annee
    ) {}

    public function getTitre(): string { return $this->titre; }

    /**
     * Validation ISBN-13 avec vérification de la CLÉ DE CONTRÔLE.
     *
     * Beaucoup de stagiaires s'arrêtent à « 13 chiffres ». C'est
     * insuffisant : 1111111111111 passerait. Le 13e chiffre est une
     * clé calculée à partir des 12 premiers (norme ISO 2108).
     *
     * Bon exemple pour faire comprendre la différence entre
     * validation de FORMAT et validation de VALIDITÉ.
     */
    public function setIsbn(string $isbn): void
    {
        // 1. Nettoyage : les ISBN sont souvent écrits avec des tirets.
        $isbn = str_replace(['-', ' '], '', $isbn);

        // 2. Format : exactement 13 chiffres.
        if (preg_match('/^\d{13}$/', $isbn) !== 1) {
            throw new InvalidArgumentException(
                'ISBN invalide : 13 chiffres attendus.'
            );
        }

        // 3. Clé de contrôle : somme pondérée 1,3,1,3... sur les 12
        //    premiers chiffres ; le total + clé doit être multiple de 10.
        $somme = 0;
        for ($i = 0; $i < 12; $i++) {
            $somme += (int) $isbn[$i] * ($i % 2 === 0 ? 1 : 3);
        }

        $cleAttendue = (10 - ($somme % 10)) % 10;

        if ((int) $isbn[12] !== $cleAttendue) {
            throw new InvalidArgumentException(
                'ISBN invalide : clé de contrôle incorrecte.'
            );
        }

        $this->isbn = $isbn;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function afficher(): string
    {
        return sprintf('%s (%d), de %s', $this->titre, $this->annee, $this->auteur);
    }

    /**
     * MÉTHODE MAGIQUE __toString()
     *
     * Différence avec afficher(), à faire verbaliser au stagiaire :
     *
     *   - afficher() est une méthode MÉTIER, appelée explicitement.
     *     Son nom documente l'intention. On peut en avoir plusieurs
     *     (afficherCourt(), afficherComplet()...).
     *
     *   - __toString() est un CONTRAT DE CONVERSION. PHP l'appelle
     *     automatiquement dès que l'objet est utilisé dans un contexte
     *     de chaîne : echo $livre, "Titre : $livre", implode() sur un
     *     tableau d'objets...
     *
     * Piège classique : __toString() ne doit JAMAIS lever d'exception
     * dans les vieilles versions de PHP. Depuis PHP 7.4 c'est permis,
     * mais reste une mauvaise pratique — on ne s'attend pas à ce qu'un
     * echo puisse planter.
     */
    public function __toString(): string
    {
        return $this->afficher();
    }
}

// =====================================================================
// JEU D'ESSAI — 3 cas passants, 2 cas d'erreur
// =====================================================================
// C'est le livrable attendu du niveau 3 de l'étape 1. Format simple,
// mais qui prépare directement à PHPUnit.

$tests = 0;
$reussis = 0;

function verifier(string $intitule, callable $test): void
{
    global $tests, $reussis;
    $tests++;

    try {
        $test();
        $reussis++;
        echo "  [OK]     $intitule\n";
    } catch (Throwable $e) {
        echo "  [ÉCHEC]  $intitule — " . $e->getMessage() . "\n";
    }
}

echo "=== JEU D'ESSAI — LivreAvance ===\n\n";
echo "Cas passants :\n";

verifier('Instanciation et getTitre()', function () {
    $l = new LivreAvance('Dune', 'Frank Herbert', 1965);
    if ($l->getTitre() !== 'Dune') {
        throw new RuntimeException('titre incorrect');
    }
});

verifier('ISBN valide accepté (avec tirets)', function () {
    $l = new LivreAvance('Dune', 'Frank Herbert', 1965);
    $l->setIsbn('978-2-266-32048-1');
    if ($l->getIsbn() !== '9782266320481') {
        throw new RuntimeException('ISBN non normalisé');
    }
});

verifier('__toString() dans un contexte de chaîne', function () {
    $l = new LivreAvance('Dune', 'Frank Herbert', 1965);
    $rendu = "$l";
    if ($rendu !== 'Dune (1965), de Frank Herbert') {
        throw new RuntimeException("rendu obtenu : $rendu");
    }
});

echo "\nCas d'erreur attendus :\n";

verifier('ISBN trop court rejeté', function () {
    $l = new LivreAvance('Dune', 'Frank Herbert', 1965);
    try {
        $l->setIsbn('12345');
        throw new RuntimeException('aucune exception levée');
    } catch (InvalidArgumentException) {
        // comportement attendu
    }
});

verifier('ISBN 13 chiffres mais clé fausse rejeté', function () {
    $l = new LivreAvance('Dune', 'Frank Herbert', 1965);
    try {
        $l->setIsbn('9782266320489');   // dernier chiffre volontairement faux
        throw new RuntimeException('aucune exception levée');
    } catch (InvalidArgumentException) {
        // comportement attendu
    }
});

echo "\n--- Résultat : $reussis/$tests tests réussis ---\n";
