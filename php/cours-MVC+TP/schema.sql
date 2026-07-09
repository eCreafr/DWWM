-- ============================================================
-- Ludotheque du Bourg -- Base de donnees de TP (MVC PHP natif)
-- A executer dans phpMyAdmin / MySQL avant de commencer le TP
-- ============================================================

CREATE DATABASE IF NOT EXISTS ludotheque_bourg
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE ludotheque_bourg;

DROP TABLE IF EXISTS emprunts;
DROP TABLE IF EXISTS jeux;
DROP TABLE IF EXISTS adherents;

-- ------------------------------------------------------------
-- Table : jeux (utilisee des le Niveau 2 du TP)
-- ------------------------------------------------------------
CREATE TABLE jeux (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titre           VARCHAR(100)        NOT NULL,
    editeur         VARCHAR(100)        DEFAULT NULL,
    nb_joueurs_min  TINYINT UNSIGNED    NOT NULL DEFAULT 1,
    nb_joueurs_max  TINYINT UNSIGNED    NOT NULL DEFAULT 1,
    duree_minutes   SMALLINT UNSIGNED   DEFAULT NULL,
    disponible      TINYINT(1)          NOT NULL DEFAULT 1
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Table : adherents (necessaire pour le Niveau 3 - emprunts)
-- ------------------------------------------------------------
CREATE TABLE adherents (
    id      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom     VARCHAR(100)  NOT NULL,
    prenom  VARCHAR(100)  NOT NULL,
    email   VARCHAR(150)  NOT NULL UNIQUE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Table : emprunts (Niveau 3 - extension)
-- ------------------------------------------------------------
CREATE TABLE emprunts (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    jeu_id        INT UNSIGNED NOT NULL,
    adherent_id   INT UNSIGNED NOT NULL,
    date_emprunt  DATE NOT NULL,
    date_retour   DATE DEFAULT NULL,
    CONSTRAINT fk_emprunt_jeu      FOREIGN KEY (jeu_id)      REFERENCES jeux(id),
    CONSTRAINT fk_emprunt_adherent FOREIGN KEY (adherent_id) REFERENCES adherents(id)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Jeu de donnees de test
-- ------------------------------------------------------------
INSERT INTO jeux (titre, editeur, nb_joueurs_min, nb_joueurs_max, duree_minutes, disponible) VALUES
('Catan',       'Kosmos',           3, 4, 90, 1),
('7 Wonders',   'Repos Production', 3, 7, 30, 1),
('Splendor',    'Space Cowboys',    2, 4, 30, 0),
('Azul',        'Plan B Games',     2, 4, 45, 1),
('Carcassonne', 'Hans im Gluck',    2, 5, 35, 1),
('Dixit',       'Libellud',         3, 6, 30, 1),
('Pandemic',    'Z-Man Games',      2, 4, 45, 0);

INSERT INTO adherents (nom, prenom, email) VALUES
('Martin', 'Julie',  'julie.martin@example.fr'),
('Dubois', 'Karim',  'karim.dubois@example.fr'),
('Petit',  'Sophie', 'sophie.petit@example.fr');

-- Splendor et Pandemic sont livres "empruntes" (disponible = 0) :
-- a associer a un emprunt actif si vous travaillez le Niveau 3.
INSERT INTO emprunts (jeu_id, adherent_id, date_emprunt, date_retour) VALUES
(3, 1, CURDATE() - INTERVAL 4 DAY, NULL),
(7, 2, CURDATE() - INTERVAL 2 DAY, NULL);
