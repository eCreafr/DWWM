-- Créer la base de données
CREATE DATABASE IF NOT EXISTS sport_2000;
USE sport_2000;


-- Table des résultats sportifs
CREATE TABLE resultats_sportifs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    equipe1 VARCHAR(100) NOT NULL,
    equipe2 VARCHAR(100) NOT NULL,
    score VARCHAR(10) NOT NULL,
    resume TEXT NOT NULL,
    lieu VARCHAR(100) NOT NULL,
    date_match DATE NOT NULL
);

-- Table des articles de presse sur des matchs
CREATE TABLE articles_presse (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    auteur VARCHAR(100) NOT NULL,
    date_publication DATE NOT NULL,
    match_id INT, -- Lien vers un match
    FOREIGN KEY (match_id) REFERENCES resultats_sportifs(id)
);

-- Table des utilisateurs administrateurs
CREATE TABLE administrateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    droits ENUM('lecteur', 'editeur', 'admin') DEFAULT 'lecteur'
);

-- Table des abonnés lecteurs
CREATE TABLE abonnes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    mail VARCHAR(100) NOT NULL UNIQUE,
    age INT NOT NULL,
    genre ENUM('Homme', 'Femme', 'Autre') NOT NULL,
    pays VARCHAR(100) NOT NULL,
    date_abonnement DATE NOT NULL,
    date_fin_abonnement DATE NOT NULL
);



-- Insertion des résultats sportifs
INSERT INTO resultats_sportifs (equipe1, equipe2, score, resume, lieu, date_match) VALUES
('Paris Saint-Germain', 'Olympique de Marseille', '3-1', 'Match intense avec des buts spectaculaires et une domination globale du PSG.', 'Paris', '2024-11-01'),
('AS Monaco', 'Olympique Lyonnais', '2-2', 'Un match équilibré avec deux égalisations spectaculaires.', 'Monaco', '2024-10-20'),
('RC Lens', 'Lille OSC', '1-0', 'Un but décisif à la 89e minute offre une victoire au RC Lens.', 'Lens', '2024-10-15'),
('Stade Rennais', 'FC Nantes', '4-3', 'Un thriller à Rennes avec des retournements de situation constants.', 'Rennes', '2024-09-25');

-- Insertion des articles de presse
INSERT INTO articles_presse (titre, contenu, auteur, date_publication, match_id) VALUES
('Le PSG triomphe à domicile', 'Le Paris Saint-Germain a offert une performance dominante avec un score de 3-1 contre l\'OM. Les supporters étaient en liesse.', 'Jean Dupont', '2024-11-02', 1),
('Monaco et Lyon se neutralisent', 'Un match captivant entre l\'AS Monaco et l\'Olympique Lyonnais, se soldant par un score de 2-2. Deux égalisations incroyables ont marqué ce duel.', 'Alice Martin', '2024-10-21', 2),
('RC Lens brille à la dernière minute', 'Dans un match tendu, le RC Lens a marqué un but crucial à la 89e minute pour s\'imposer face au Lille OSC.', 'Paul Girard', '2024-10-16', 3),
('Un thriller à Rennes', 'Le Stade Rennais a battu le FC Nantes dans un match palpitant qui a vu sept buts marqués et des retournements spectaculaires.', 'Julie Bernard', '2024-09-26', 4);

-- Insertion des administrateurs
INSERT INTO administrateurs (login, password_hash, droits) VALUES
('admin1', MD5('admin123'), 'admin'),
('editeur1', MD5('editeur123'), 'editeur'),
('lecteur1', MD5('lecteur123'), 'lecteur');

-- Insertion des abonnés
INSERT INTO abonnes (nom, prenom, mail, age, genre, pays, date_abonnement, date_fin_abonnement) VALUES
('Dupont', 'Jean', 'jean.dupont@example.com', 25, 'Homme', 'France', '2024-01-01', '2024-12-31'),
('Martin', 'Julie', 'julie.martin@example.com', 30, 'Femme', 'Canada', '2024-03-01', '2024-12-31'),
('Doe', 'John', 'john.doe@example.com', 35, 'Homme', 'États-Unis', '2024-05-01', '2025-05-01');
