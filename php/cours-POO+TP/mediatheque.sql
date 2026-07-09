-- =====================================================
-- TP POO — Médiathèque « La Grande Ourse »
-- Base de données et jeu d'essai (12 documents)
-- Formation DWWM — AFPA Nouvelle-Aquitaine
-- =====================================================

CREATE DATABASE IF NOT EXISTS mediatheque
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE mediatheque;

DROP TABLE IF EXISTS documents;

CREATE TABLE documents (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titre VARCHAR(150) NOT NULL,
  type ENUM('livre', 'dvd', 'jeu_video') NOT NULL,
  annee SMALLINT UNSIGNED NOT NULL,
  auteur_ou_realisateur VARCHAR(100) NOT NULL,
  disponible TINYINT(1) NOT NULL DEFAULT 1
) ENGINE = InnoDB;

INSERT INTO documents (titre, type, annee, auteur_ou_realisateur, disponible) VALUES
('Dune',                          'livre',     1965, 'Frank Herbert',        1),
('Le Comte de Monte-Cristo',      'livre',     1844, 'Alexandre Dumas',      1),
('La Horde du Contrevent',        'livre',     2004, 'Alain Damasio',        0),
('Sapiens',                       'livre',     2011, 'Yuval Noah Harari',    1),
('Le Fabuleux Destin d''Amélie Poulain', 'dvd', 2001, 'Jean-Pierre Jeunet',  1),
('Intouchables',                  'dvd',       2011, 'Toledano & Nakache',   0),
('Le Voyage de Chihiro',          'dvd',       2001, 'Hayao Miyazaki',       1),
('Les Enfants du Temps',          'dvd',       2019, 'Makoto Shinkai',       1),
('Zelda : Tears of the Kingdom',  'jeu_video', 2023, 'Nintendo',             1),
('It Takes Two',                  'jeu_video', 2021, 'Hazelight Studios',    0),
('Stardew Valley',                'jeu_video', 2016, 'ConcernedApe',         1),
('Hollow Knight',                 'jeu_video', 2017, 'Team Cherry',          1);

-- Extension niveau 3 (transaction) : table emprunts
-- Décommentez pour l'extension de l'Étape 3.
--
-- CREATE TABLE emprunts (
--   id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
--   document_id INT UNSIGNED NOT NULL,
--   date_emprunt DATE NOT NULL,
--   date_retour DATE NULL,
--   CONSTRAINT fk_emprunt_document
--     FOREIGN KEY (document_id) REFERENCES documents(id)
-- ) ENGINE = InnoDB;
