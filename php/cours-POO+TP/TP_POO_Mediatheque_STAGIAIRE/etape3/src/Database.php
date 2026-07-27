<?php
declare(strict_types=1);

/**
 * TP POO — ÉTAPE 3 — NIVEAU 1
 *
 * Classe Database : FOURNIE INTÉGRALEMENT.
 *
 * VOTRE TRAVAIL SUR CE FICHIER :
 * ne pas le réécrire, mais être capable de l'EXPLIQUER LIGNE À LIGNE.
 * Le formateur vous interrogera dessus pendant la restitution, et le
 * jury le fera le jour de la certification.
 *
 * Questions auxquelles vous devez savoir répondre :
 *   1. Que signifie `static` sur la propriété $pdo ?
 *   2. Pourquoi `self::$pdo` et non `$this->pdo` ?
 *   3. À quoi sert le test `if (self::$pdo === null)` ?
 *   4. Que change PDO::ERRMODE_EXCEPTION ?
 *   5. Pourquoi PDO::ATTR_EMULATE_PREPARES à false ?
 */
class Database
{
    /**
     * Propriété STATIQUE : elle appartient à la CLASSE, pas à une instance.
     * Le `?PDO` signifie « un objet PDO OU null » (type nullable).
     */
    private static ?PDO $pdo = null;

    /**
     * Retourne la connexion PDO, en la créant au premier appel seulement.
     * Ce mécanisme d'instance unique partagée est une forme simplifiée
     * du pattern SINGLETON.
     */
    public static function getConnection(): PDO
    {
        if (self::$pdo === null) {

            $config = require __DIR__ . '/config.php';

            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                $config['host'],
                $config['port'],
                $config['dbname'],
                $config['charset']
            );

            try {
                self::$pdo = new PDO($dsn, $config['user'], $config['password'], [

                    // Toute erreur SQL lève une PDOException au lieu d'échouer
                    // silencieusement. Indispensable pour un code robuste.
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,

                    // Les résultats sont retournés en tableaux associatifs
                    // par défaut (au lieu du double format num + assoc).
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

                    // Les requêtes sont réellement préparées par MySQL et non
                    // simulées par PDO : protection maximale contre l'injection.
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                // On ne renvoie JAMAIS $e->getMessage() à l'écran :
                // il contient host, user et structure de la base.
                error_log('Connexion BDD échouée : ' . $e->getMessage());
                throw new RuntimeException('Connexion à la base de données impossible.');
            }
        }

        return self::$pdo;
    }
}
