-- Aegis CA Database Migration V2: Aggiunta Estensioni X.509, Revoca e Seriali
USE `aegis_ca`;

-- 1. Aggiornamento Tabella CAS (Autorità d'Emissione)
ALTER TABLE `cas`
    ADD COLUMN `serial_number` VARCHAR(64) NULL AFTER `id`,
    ADD COLUMN `subject_key_identifier` VARCHAR(64) NULL AFTER `serial_number`,
    ADD COLUMN `fingerprint_sha256` CHAR(64) NULL AFTER `subject_key_identifier`,
    ADD COLUMN `signature_algorithm` VARCHAR(50) NULL AFTER `key_bits`,
    ADD COLUMN `ca_password` VARCHAR(255) NULL AFTER `signature_algorithm`,
    ADD COLUMN `is_intermediate` TINYINT(1) NOT NULL DEFAULT 0 AFTER `ca_password`,
    ADD COLUMN `path_len_constraint` INT NULL DEFAULT NULL AFTER `is_intermediate`,
    ADD COLUMN `next_serial` BIGINT UNSIGNED NOT NULL DEFAULT 1 AFTER `path_len_constraint`,
    ADD COLUMN `crl_number` BIGINT UNSIGNED NOT NULL DEFAULT 1 AFTER `next_serial`,
    ADD COLUMN `last_crl_update` DATETIME NULL DEFAULT NULL AFTER `crl_number`,
    ADD COLUMN `next_crl_update` DATETIME NULL DEFAULT NULL AFTER `last_crl_update`,
    ADD COLUMN `crl_distribution_points` TEXT DEFAULT NULL AFTER `next_crl_update`,
    ADD COLUMN `ocsp_server` VARCHAR(255) DEFAULT NULL AFTER `crl_distribution_points`,
    ADD COLUMN `description` TEXT DEFAULT NULL AFTER `ocsp_server`,
    ADD COLUMN `status` VARCHAR(20) DEFAULT 'active',
    ADD COLUMN `revoked_at` DATETIME NULL DEFAULT NULL,
    ADD INDEX `idx_ca_status` (`status`),
    ADD INDEX `idx_ca_ski` (`subject_key_identifier`),
    ADD INDEX `idx_ca_fingerprint` (`fingerprint_sha256`);

-- 2. Aggiornamento Tabella CERTIFICATES (Certificati Foglia)
ALTER TABLE `certificates`
    ADD COLUMN `serial_number` VARCHAR(64) NULL AFTER `ca_id`,
    ADD COLUMN `issuer_dn` VARCHAR(512) NULL AFTER `common_name`,
    ADD COLUMN `san_ip` TEXT DEFAULT NULL AFTER `san_dns`,
    ADD COLUMN `is_wildcard` TINYINT(1) NOT NULL DEFAULT 0 AFTER `san_ip`,
    ADD COLUMN `fingerprint_sha256` CHAR(64) NULL AFTER `is_wildcard`,
    ADD COLUMN `signature_algorithm` VARCHAR(50) NULL AFTER `key_bits`,
    ADD COLUMN `key_usage` VARCHAR(255) DEFAULT NULL AFTER `valid_to`,
    ADD COLUMN `extended_key_usage` VARCHAR(255) DEFAULT NULL AFTER `key_usage`,
    ADD COLUMN `basic_constraints` VARCHAR(100) DEFAULT NULL AFTER `extended_key_usage`,
    ADD COLUMN `crl_distribution_points` TEXT DEFAULT NULL AFTER `basic_constraints`,
    ADD COLUMN `ocsp_server` VARCHAR(255) DEFAULT NULL AFTER `crl_distribution_points`,
    ADD COLUMN `description` TEXT DEFAULT NULL AFTER `ocsp_server`,
    ADD COLUMN `status` VARCHAR(20) DEFAULT 'active',
    ADD COLUMN `revoked_at` DATETIME NULL DEFAULT NULL,
    ADD INDEX `idx_serial_number` (`serial_number`),
    ADD INDEX `idx_fingerprint` (`fingerprint_sha256`),
    ADD INDEX `idx_status` (`status`),
    ADD INDEX `idx_is_wildcard` (`is_wildcard`);