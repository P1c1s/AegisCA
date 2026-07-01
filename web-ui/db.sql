-- Creazione dell'utente MySQL e assegnazione dei privilegi
CREATE USER IF NOT EXISTS 'lorenzo'@'localhost' IDENTIFIED BY 'qss-s3E-IH9_Khz';

CREATE DATABASE IF NOT EXISTS `ssl_manager` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON `ssl_manager`.* TO 'lorenzo'@'localhost';
FLUSH PRIVILEGES;

USE `ssl_manager`;

-- 1. Tabella Utenti
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tabella Certificate Authorities (CA)
CREATE TABLE IF NOT EXISTS `cas` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `common_name` VARCHAR(255) NOT NULL,
    `subject_country` CHAR(2) NOT NULL,
    `subject_state` VARCHAR(100) NOT NULL,
    `subject_locality` VARCHAR(100) NOT NULL,
    `subject_organization` VARCHAR(255) NOT NULL,
    `subject_org_unit` VARCHAR(255) NOT NULL,
    `cert_data` TEXT NOT NULL,
    `key_data` TEXT NOT NULL,
    `valid_from` DATETIME NOT NULL,
    `valid_to` DATETIME NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tabella Certificati SSL foglia
CREATE TABLE IF NOT EXISTS `certificates` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ca_id` INT NOT NULL,
    `common_name` VARCHAR(255) NOT NULL,
    `subject_country` CHAR(2) NOT NULL,
    `subject_state` VARCHAR(100) NOT NULL,
    `subject_locality` VARCHAR(100) NOT NULL,
    `subject_organization` VARCHAR(255) NOT NULL,
    `subject_org_unit` VARCHAR(255) NOT NULL,
    `san_dns` TEXT DEFAULT NULL,
    `cert_data` TEXT NOT NULL,
    `key_data` TEXT NOT NULL,
    `valid_from` DATETIME NOT NULL,
    `valid_to` DATETIME NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`ca_id`) REFERENCES `cas`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserimento utente amministratore di default (Username: admin, Password: admin)
INSERT INTO `users` (`username`, `password`) 
VALUES ('admin', '$2y$10$GTAKhQTJ9J13yi.zi7Cx9.oaJLqwaE4pt5s9e.D3uqMVI.jwInc66')
ON DUPLICATE KEY UPDATE `id`=`id`;