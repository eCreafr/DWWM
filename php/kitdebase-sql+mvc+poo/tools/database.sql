-- ============================================
-- Fichier SQL d'initialisation de la base de données
-- Base : kitdebase
-- ============================================

-- Création de la base de données (si elle n'existe pas)
CREATE DATABASE IF NOT EXISTS kitdebase CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Sélection de la base
USE kitdebase;

-- ============================================
-- Table : users
-- ============================================
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table : admins
-- ============================================
DROP TABLE IF EXISTS admins;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL UNIQUE,
    motDePasse VARCHAR(255) NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_login (login)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Données de test
-- ============================================
INSERT INTO users (nom, email) VALUES
('Jean Dupont', 'jean.dupont@example.com'),
('Marie Martin', 'marie.martin@example.com'),
('Pierre Durand', 'pierre.durand@example.com'),
('Sophie Bernard', 'sophie.bernard@example.com');

-- Admin par défaut : login = admin / mot de passe = admin123
-- Hash généré avec password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO admins (login, motDePasse) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Vérification
SELECT * FROM users;
SELECT id, login, date_creation FROM admins;