<?php
declare(strict_types=1);

/**
 * ===================================================================
 * CORRIGÉ NIVEAU 3 — ÉTAPE 3 — Transaction
 * ===================================================================
 * Défi : « écrire une transaction : emprunter un document ET insérer
 * une ligne dans une table emprunts, avec rollback en cas d'échec ».
 *
 * POURQUOI C'EST INDISPENSABLE
 * Un emprunt fait DEUX écritures indissociables :
 *   1. passer le document en indisponible
 *   2. créer la ligne de traçabilité dans `emprunts`
 *
 * Si la seconde échoue sans rollback, la base garde un document marqué
 * emprunté que personne n'a emprunté. C'est le critère REAC CP6
 * « l'intégrité des données est maintenue » dans son sens littéral.
 *
 * PRÉREQUIS : moteur InnoDB. MyISAM ignore silencieusement les
 * transactions — le code s'exécuterait sans erreur et sans rollback.
 * Le mediatheque.sql fourni utilise bien InnoDB.
 */

require_once __DIR__ . '/../../etape3/src/Database.php';

class EmpruntService
{
    /**
     * @throws RuntimeException si l'emprunt ne peut pas aboutir
     */
    public function emprunter(int $documentId, string $emprunteur): void
    {
        $pdo = Database::getConnection();

        // À partir d'ici, rien n'est réellement écrit tant que commit()
        // n'a pas été appelé.
        $pdo->beginTransaction();

        try {
            // --- 1. Verrouiller et vérifier la disponibilité ---
            // FOR UPDATE pose un verrou sur la ligne jusqu'à la fin de
            // la transaction : deux adhérents ne peuvent pas emprunter
            // le même document simultanément (condition de concurrence).
            //
            // Ce raffinement va au-delà de l'attendu du cours. Ne pas
            // l'imposer — mais c'est la réponse à « et si deux personnes
            // cliquent en même temps ? ».
            $stmt = $pdo->prepare(
                'SELECT disponible FROM documents WHERE id = :id FOR UPDATE'
            );
            $stmt->execute(['id' => $documentId]);
            $document = $stmt->fetch();

            if ($document === false) {
                throw new RuntimeException('Document introuvable.');
            }

            if (!(bool) $document['disponible']) {
                throw new RuntimeException('Document déjà emprunté.');
            }

            // --- 2. Marquer indisponible ---
            $stmt = $pdo->prepare(
                'UPDATE documents SET disponible = 0 WHERE id = :id'
            );
            $stmt->execute(['id' => $documentId]);

            // --- 3. Tracer l'emprunt ---
            $stmt = $pdo->prepare(
                'INSERT INTO emprunts (document_id, emprunteur, date_emprunt)
                 VALUES (:doc, :emprunteur, CURDATE())'
            );
            $stmt->execute([
                'doc'        => $documentId,
                'emprunteur' => $emprunteur,
            ]);

            // Tout s'est bien passé : on valide l'ensemble d'un bloc.
            $pdo->commit();

        } catch (Throwable $e) {
            // Une seule opération a échoué -> on annule TOUT.
            $pdo->rollBack();

            error_log('Emprunt échoué : ' . $e->getMessage());
            throw new RuntimeException("L'emprunt n'a pas pu être enregistré.");
        }
    }

    /** Retour d'un document : même logique en sens inverse. */
    public function rendre(int $documentId): void
    {
        $pdo = Database::getConnection();
        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare(
                'UPDATE documents SET disponible = 1 WHERE id = :id'
            );
            $stmt->execute(['id' => $documentId]);

            // On clôture le dernier emprunt ouvert de ce document.
            $stmt = $pdo->prepare(
                'UPDATE emprunts
                    SET date_retour = CURDATE()
                  WHERE document_id = :id
                    AND date_retour IS NULL'
            );
            $stmt->execute(['id' => $documentId]);

            $pdo->commit();

        } catch (Throwable $e) {
            $pdo->rollBack();
            error_log('Retour échoué : ' . $e->getMessage());
            throw new RuntimeException("Le retour n'a pas pu être enregistré.");
        }
    }
}

/**
 * =====================================================================
 * TEST À IMPOSER AU STAGIAIRE — c'est le seul qui prouve quelque chose
 * =====================================================================
 *
 * 1. Faire fonctionner un emprunt normal, vérifier les DEUX tables.
 *
 * 2. Renommer volontairement la table `emprunts` :
 *      RENAME TABLE emprunts TO emprunts_x;
 *
 * 3. Relancer un emprunt. Il doit échouer ET le document doit
 *    RESTER DISPONIBLE.
 *
 * 4. Si le document est passé à indisponible, le rollback est mal placé
 *    ou absent. C'est exactement le bug que la transaction existe pour
 *    empêcher.
 *
 * 5. Remettre la table :
 *      RENAME TABLE emprunts_x TO emprunts;
 *
 * Un stagiaire qui n'a pas fait ce test n'a pas démontré que sa
 * transaction fonctionne — il a seulement démontré que le cas nominal
 * passe, ce qui serait aussi vrai sans transaction.
 */
