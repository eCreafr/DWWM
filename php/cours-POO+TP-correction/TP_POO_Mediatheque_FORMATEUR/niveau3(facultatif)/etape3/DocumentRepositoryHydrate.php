<?php
declare(strict_types=1);

/**
 * ===================================================================
 * CORRIGÉ NIVEAU 3 — ÉTAPE 3 — Hydratation + agrégations
 * ===================================================================
 * Deux défis du cours traités ici :
 *   - « faire retourner au Repository des objets Livre/Dvd/JeuVideo
 *     via une méthode privée hydrate(array $row): Document »
 *   - « ajouter findDisponibles(): array et countByType(): array »
 *
 * C'EST L'EXTENSION LA PLUS RENTABLE DE LA JOURNÉE.
 * Elle referme la boucle : jusqu'ici, l'étape 3 manipulait des tableaux
 * associatifs, et les classes de l'étape 2 pouvaient sembler un exercice
 * gratuit. Ici elles deviennent la sortie du Repository.
 *
 * Argument jury à faire préparer :
 *   « J'ai écrit une méthode d'hydratation qui transforme une ligne SQL
 *     en objet métier. C'est, en simplifié, ce que fait un ORM comme
 *     Doctrine ou Eloquent. »
 *
 * PRÉREQUIS : base importée. Lancement depuis ce dossier.
 */

require_once __DIR__ . '/../../etape2/src/Document.php';
require_once __DIR__ . '/../../etape2/src/Empruntable.php';
require_once __DIR__ . '/../../etape2/src/Livre.php';
require_once __DIR__ . '/../../etape2/src/Dvd.php';
require_once __DIR__ . '/../../etape2/src/JeuVideo.php';
require_once __DIR__ . '/../../etape3/src/Database.php';

class DocumentRepositoryHydrate
{
    /**
     * Transforme une ligne de la base en objet métier du bon type.
     *
     * PRIVÉE volontairement : c'est un détail d'implémentation du
     * Repository. L'appelant n'a pas à savoir comment les objets sont
     * fabriqués, seulement qu'il en reçoit.
     *
     * @param array<string, mixed> $row
     */
    private function hydrate(array $row): Document
    {
        return match ($row['type']) {

            'livre' => new Livre(
                (string) $row['titre'],
                (int) $row['annee'],
                (string) $row['auteur_ou_realisateur']
            ),

            'dvd' => new Dvd(
                (string) $row['titre'],
                (int) $row['annee'],
                (string) $row['auteur_ou_realisateur'],
                (int) ($row['duree_minutes'] ?? 0)
            ),

            'jeu_video' => new JeuVideo(
                (string) $row['titre'],
                (int) $row['annee'],
                (string) ($row['plateforme'] ?? 'Inconnue'),
                (int) ($row['pegi'] ?? 3)
            ),

            // [POINT DE CORRECTION] Ce bras par défaut n'est PAS optionnel.
            // Un stagiaire qui écrit `default => new Livre(...)` masque un
            // futur bug : si un type est ajouté en base sans mise à jour du
            // code, on veut une erreur explicite, pas un objet silencieusement
            // faux. À reprendre systématiquement.
            default => throw new RuntimeException(
                'Type de document inconnu : ' . (string) $row['type']
            ),
        };
    }

    /** @return array<int, Document> */
    public function findAll(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT * FROM documents ORDER BY type, titre'
        );

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function findById(int $id): ?Document
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT * FROM documents WHERE id = :id'
        );
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch();

        return $row ? $this->hydrate($row) : null;
    }

    /** @return array<int, Document> */
    public function findDisponibles(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT * FROM documents WHERE disponible = 1 ORDER BY titre'
        );

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    /**
     * Agrégation SQL — compte les documents par type.
     * Le GROUP BY est fait par MySQL, pas en PHP : c'est le point à
     * défendre. Compter en PHP obligerait à charger toute la table.
     *
     * @return array<string, int>
     */
    public function countByType(): array
    {
        $stmt = Database::getConnection()->query(
            'SELECT type, COUNT(*) AS nb FROM documents GROUP BY type ORDER BY type'
        );

        $resultat = [];
        foreach ($stmt->fetchAll() as $ligne) {
            $resultat[(string) $ligne['type']] = (int) $ligne['nb'];
        }

        return $resultat;
    }
}

// ---------------------------------------------------------------------
// DÉMONSTRATION
// ---------------------------------------------------------------------
// La boucle d'affichage est IDENTIQUE à celle de l'étape 2. C'est tout
// l'intérêt : le code d'affichage ne change pas selon que les objets
// viennent d'un tableau en dur ou de la base de données.
// ---------------------------------------------------------------------

if (PHP_SAPI === 'cli' && basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    $repo = new DocumentRepositoryHydrate();

    echo "=== Catalogue hydraté en objets ===\n";
    foreach ($repo->findAll() as $document) {
        printf("  %-10s %s\n", get_class($document), $document->getDescription());
    }

    echo "\n=== Disponibles uniquement ===\n";
    foreach ($repo->findDisponibles() as $document) {
        echo '  ' . $document->getTitre() . "\n";
    }

    echo "\n=== Comptage par type (agrégation SQL) ===\n";
    foreach ($repo->countByType() as $type => $nb) {
        printf("  %-12s %d\n", $type, $nb);
    }
}
