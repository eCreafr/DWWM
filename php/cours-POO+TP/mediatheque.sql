-- =====================================================
-- TP POO — Médiathèque « La Grande Ourse »
-- Base de données et jeu d'essai (12 documents)
-- Formation DWWM — AFPA Nouvelle-Aquitaine — Raphaël Lang
-- =====================================================
--
-- Import :  mysql -u root -p < mediatheque.sql
-- ou via phpMyAdmin : onglet "Importer"
-- =====================================================

CREATE DATABASE IF NOT EXISTS mediatheque
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE mediatheque;

DROP TABLE IF EXISTS emprunts;
DROP TABLE IF EXISTS documents;

-- -----------------------------------------------------
-- Table documents
--
-- Choix d'architecture : héritage sur table unique
-- (single table inheritance). Les colonnes spécifiques
-- à un type sont NULL pour les autres types.
--
-- Ce choix est assumé et défendable devant le jury :
--   + une seule requête, pas de jointure
--   + hydratation simple vers Livre / Dvd / JeuVideo
--   - colonnes NULL (dénormalisation partielle)
-- L'alternative (une table par type + table mère) est
-- traitée dans les extensions de niveau 3.
-- -----------------------------------------------------

CREATE TABLE documents (
  id                    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titre                 VARCHAR(150) NOT NULL,
  type                  ENUM('livre', 'dvd', 'jeu_video') NOT NULL,
  annee                 SMALLINT UNSIGNED NOT NULL,

  -- Commun aux trois types : auteur, réalisateur ou studio
  auteur_ou_realisateur VARCHAR(100) NOT NULL,

  -- Spécifique livre
  isbn                  CHAR(13) NULL,

  -- Spécifique DVD
  duree_minutes         SMALLINT UNSIGNED NULL,

  -- Spécifique jeu vidéo
  plateforme            VARCHAR(50) NULL,
  pegi                  TINYINT UNSIGNED NULL,

  disponible            TINYINT(1) NOT NULL DEFAULT 1,

  INDEX idx_type (type),
  INDEX idx_disponible (disponible)
) ENGINE = InnoDB;

INSERT INTO documents
  (titre, type, annee, auteur_ou_realisateur, isbn, duree_minutes, plateforme, pegi, disponible)
VALUES
  ('Dune',                             'livre',     1965, 'Frank Herbert',      '9782266320481', NULL, NULL,             NULL, 1),
  ('Le Comte de Monte-Cristo',         'livre',     1844, 'Alexandre Dumas',    '9782070409228', NULL, NULL,             NULL, 1),
  ('La Horde du Contrevent',           'livre',     2004, 'Alain Damasio',      '9782070348909', NULL, NULL,             NULL, 0),
  ('Sapiens',                          'livre',     2011, 'Yuval Noah Harari',  '9782226257017', NULL, NULL,             NULL, 1),
  ('Le Fabuleux Destin d''Amélie Poulain', 'dvd',   2001, 'Jean-Pierre Jeunet', NULL,            122,  NULL,             NULL, 1),
  ('Intouchables',                     'dvd',       2011, 'Toledano & Nakache', NULL,            112,  NULL,             NULL, 0),
  ('Le Voyage de Chihiro',             'dvd',       2001, 'Hayao Miyazaki',     NULL,            125,  NULL,             NULL, 1),
  ('Les Enfants du Temps',             'dvd',       2019, 'Makoto Shinkai',     NULL,            114,  NULL,             NULL, 1),
  ('Zelda : Tears of the Kingdom',     'jeu_video', 2023, 'Nintendo',           NULL,            NULL, 'Nintendo Switch', 12,  1),
  ('It Takes Two',                     'jeu_video', 2021, 'Hazelight Studios',  NULL,            NULL, 'PC / PS5',        12,  0),
  ('Stardew Valley',                   'jeu_video', 2016, 'ConcernedApe',       NULL,            NULL, 'PC / Switch',      7,  1),
  ('Hollow Knight',                    'jeu_video', 2017, 'Team Cherry',        NULL,            NULL, 'PC / Switch',      7,  1);

-- -----------------------------------------------------
-- Table emprunts — nécessaire UNIQUEMENT pour
-- l'extension "transaction" de l'Étape 3 niveau 3.
-- Elle est créée d'office : elle ne gêne pas les
-- niveaux 1 et 2.
-- -----------------------------------------------------

CREATE TABLE emprunts (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  document_id  INT UNSIGNED NOT NULL,
  emprunteur   VARCHAR(100) NOT NULL,
  date_emprunt DATE NOT NULL,
  date_retour  DATE NULL,
  CONSTRAINT fk_emprunt_document
    FOREIGN KEY (document_id) REFERENCES documents(id)
    ON DELETE CASCADE
) ENGINE = InnoDB;

-- Vérification rapide après import :
-- SELECT type, COUNT(*) AS nb FROM documents GROUP BY type;
-- Résultat attendu : livre 4, dvd 4, jeu_video 4
