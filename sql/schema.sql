-- Bee Happy — schéma dashboard ruchers / ruches / mesures
-- Préfixe bee_ pour éviter les collisions avec d'autres tables du même serveur.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS bee_mesures;
DROP TABLE IF EXISTS bee_ruches;
DROP TABLE IF EXISTS bee_ruchers;
DROP TABLE IF EXISTS bee_users;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE bee_users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(64) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_bee_users_login (login)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE bee_ruchers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(120) NOT NULL,
    latitude DECIMAL(10, 7) NOT NULL,
    longitude DECIMAL(10, 7) NOT NULL,
    description VARCHAR(500) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE bee_ruches (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    rucher_id INT UNSIGNED NOT NULL,
    nom VARCHAR(120) NOT NULL,
    latitude DECIMAL(10, 7) NOT NULL,
    longitude DECIMAL(10, 7) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_ruche_rucher FOREIGN KEY (rucher_id) REFERENCES bee_ruchers (id) ON DELETE CASCADE,
    INDEX idx_ruches_rucher (rucher_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE bee_mesures (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ruche_id INT UNSIGNED NOT NULL,
    poids_kg DECIMAL(8, 3) NOT NULL,
    temperature_c DECIMAL(5, 2) DEFAULT NULL,
    mesure_at DATETIME NOT NULL,
    CONSTRAINT fk_mesure_ruche FOREIGN KEY (ruche_id) REFERENCES bee_ruches (id) ON DELETE CASCADE,
    INDEX idx_mesures_ruche_date (ruche_id, mesure_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Compte démo : login admin / mot de passe password (à changer en production)
INSERT INTO bee_users (login, password_hash) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Données de démonstration : 2 ruchers, ruches proches (zoom regroupé)
INSERT INTO bee_ruchers (nom, latitude, longitude, description) VALUES
('Rucher des Collines', 45.8355000, 1.2645000, 'Site principal — exposition sud.'),
('Rucher du Verger', 45.8768000, 1.1360000, 'Proximité pommiers, miellée printanière.');

INSERT INTO bee_ruches (rucher_id, nom, latitude, longitude) VALUES
(1, 'Ruche Alpha',   45.8354700, 1.2645200),
(1, 'Ruche Beta',    45.8353800, 1.2644000),
(1, 'Ruche Gamma',   45.8355500, 1.2643500),
(1, 'Ruche Delta',   45.8356000, 1.2645800),
(2, 'Ruche Est',     45.8767500, 1.1359500),
(2, 'Ruche Ouest',   45.8768500, 1.1360500),
(2, 'Ruche Nord',    45.8768200, 1.1358800);
