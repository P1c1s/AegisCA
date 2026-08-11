-- -- Creazione dell'utente MySQL e assegnazione dei privilegi sul nuovo DB
-- CREATE USER IF NOT EXISTS 'lorenzo'@'localhost' IDENTIFIED BY 'qss-s3E-IH9_Khz';

-- -- Database rinominato in aegis_ca
-- CREATE DATABASE IF NOT EXISTS `aegis_ca` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- GRANT ALL PRIVILEGES ON `aegis_ca`.* TO 'lorenzo'@'localhost';
-- FLUSH PRIVILEGES;

-- Creazione DB
CREATE DATABASE IF NOT EXISTS `aegis_ca` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `aegis_ca`;

-- 1. Tabella Utenti
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tabella Certificate Authorities (CA)
CREATE TABLE IF NOT EXISTS `cas` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `common_name` VARCHAR(255) NOT NULL UNIQUE,
    `subject_country` CHAR(2) NOT NULL,
    `subject_state` VARCHAR(100) NOT NULL,
    `subject_locality` VARCHAR(100) NOT NULL,
    `subject_organization` VARCHAR(255) NOT NULL,
    `subject_org_unit` VARCHAR(255) NOT NULL,
    `cert_data` TEXT NOT NULL,
    `key_data` TEXT NOT NULL,
    `key_type` VARCHAR(10) NOT NULL DEFAULT 'rsa',
    `key_bits` INT NOT NULL,
    `valid_from` DATETIME NOT NULL,
    `valid_to` DATETIME NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tabella Certificati SSL foglia
CREATE TABLE IF NOT EXISTS `certificates` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ca_id` INT NOT NULL,
    `common_name` VARCHAR(255) NOT NULL UNIQUE,
    `subject_country` CHAR(2) NOT NULL,
    `subject_state` VARCHAR(100) NOT NULL,
    `subject_locality` VARCHAR(100) NOT NULL,
    `subject_organization` VARCHAR(255) NOT NULL,
    `subject_org_unit` VARCHAR(255) NOT NULL,
    `san_dns` TEXT DEFAULT NULL,
    `cert_data` TEXT NOT NULL,
    `key_data` TEXT NOT NULL,
    `key_type` VARCHAR(10) NOT NULL DEFAULT 'rsa',
    `key_bits` INT NOT NULL,
    `valid_from` DATETIME NOT NULL,
    `valid_to` DATETIME NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`ca_id`) REFERENCES `cas`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserimento utente amministratore di default (Username: admin, Password: admin)
INSERT INTO `users` (`username`, `password`) 
VALUES ('admin', '$2y$12$9AY7p5IRP8RgNjGbbvO1rO5AuZ70gd.QNPqgsuOUD96TFsKVh2m0e');
