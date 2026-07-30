<?php
declare(strict_types=1);

/**
 * ===================================================================
 * EXTENSION NIVEAU 3 — ÉTAPE 3 — TRANSACTION
 * ===================================================================
 * Objectif : un emprunt doit faire DEUX choses de façon indissociable :
 *   1. passer le document en indisponible
 *   2. créer une ligne dans la table `emprunts`
 *
 * Si la seconde échoue, la première doit être annulée. Sinon la base
 * se retrouve avec un document marqué emprunté que personne n'a
 * emprunté : incohérence de données.
 *
 * C'est le critère REAC CP6 « l'intégrité des données est maintenue »
 * dans son sens le plus littéral.
 *
 * PRÉREQUIS : moteur InnoDB (MyISAM ne gère pas les transactions).
 * Le mediatheque.sql fourni utilise bien InnoDB.
 */

require_once __DIR__ . '/../etape3/src/Database.php';

class EmpruntService
{
    /**
     * @throws RuntimeException si l'emprunt ne peut pas aboutir
     */
    public function emprunter(int $documentId, string $emprunteur): void
    {
        $pdo = Database::getConnection();

        // Ouverture de la transaction : à partir d'ici, rien n'est
        // réellement écrit tant que commit() n'a pas été appelé.
        $pdo->beginTransaction();

        try {
            // --- Opération 1 : vérrouiller et vérifier la disponibilité ---
            // FOR UPDATE pose un verrou sur la ligne jusqu'à la fin de la
            // transaction : deux adhérents ne peuvent pas emprunter le
            // même document simultanément (condition de concurrence).
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

            // --- Opération 2 : marquer indisponible ---
            $stmt = $pdo->prepare(
                'UPDATE documents SET disponible = 0 WHERE id = :id'
            );
            $stmt->execute(['id' => $documentId]);

            // --- Opération 3 : tracer l'emprunt ---
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
            // Une seule opération a échoué → on annule TOUT.
            // Sans ce rollback, la base resterait dans un état incohérent.
            $pdo->rollBack();

            error_log('Emprunt échoué : ' . $e->getMessage());
            throw new RuntimeException("L'emprunt n'a pas pu être enregistré.");
        }
    }

    /**
     * Retour d'un document : même logique en sens inverse.
     */
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

// ---------------------------------------------------------------
// TEST À FAIRE FAIRE AUX STAGIAIRES
// ---------------------------------------------------------------
// 1. Faire fonctionner un emprunt normal, vérifier les deux tables.
// 2. Renommer volontairement la table `emprunts` en `emprunts_x`.
// 3. Relancer un emprunt : il doit échouer ET le document doit
//    rester disponible. Si le document est passé à 0, le rollback
//    n'est pas correctement placé.
// ---------------------------------------------------------------
