-- ============================================================
-- JV Bohol Car Rental — Fleet Admin Database Schema (v2)
-- Run this in phpMyAdmin or MySQL CLI to bootstrap the DB.
-- ============================================================

-- CREATE DATABASE IF NOT EXISTS `fleet_db`
--   DEFAULT CHARACTER SET utf8mb4
--   COLLATE utf8mb4_unicode_ci;

-- USE `fleet_db`;

-- ------------------------------------------------------------
-- Vehicles Table (expanded with display metadata)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `vehicles`;

CREATE TABLE `vehicles` (
  `id`            INT UNSIGNED      NOT NULL AUTO_INCREMENT,
  `model_name`    VARCHAR(120)      NOT NULL,
  `year`          SMALLINT UNSIGNED NOT NULL,
  `category`      ENUM('hatchbacks','sedans','mpvs','pickup-trucks','vans') NOT NULL DEFAULT 'hatchbacks',
  `color`         VARCHAR(50)       NOT NULL DEFAULT '',
  `seating`       VARCHAR(20)       NOT NULL DEFAULT '5 Seater',
  `transmission`  ENUM('Auto','Manual') NOT NULL DEFAULT 'Auto',
  `price_per_day` DECIMAL(10,2)     NOT NULL DEFAULT 0.00,
  `badge_label`   VARCHAR(30)       DEFAULT NULL,
  `status`        ENUM('Available','Maintenance','Out') NOT NULL DEFAULT 'Available',
  `image_path`    VARCHAR(255)      DEFAULT NULL,
  `created_at`    DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Full Sample Data — 11 canonical vehicles
-- ------------------------------------------------------------
INSERT INTO `vehicles`
  (`model_name`, `year`, `category`, `color`, `seating`, `transmission`, `price_per_day`, `badge_label`, `status`, `image_path`)
VALUES
  ('Toyota Wigo 2019',     2019, 'hatchbacks',    'Black',           '5 Seater',   'Auto', 1500.00, 'Economy',   'Available', NULL),
  ('Toyota Wigo 2021',     2021, 'hatchbacks',    'Silver',          '5 Seater',   'Auto', 1500.00, NULL,        'Available', NULL),
  ('Toyota Wigo 2026',     2026, 'hatchbacks',    'Silver',          '5 Seater',   'Auto', 1800.00, NULL,        'Available', NULL),
  ('Toyota Vios 2024',     2024, 'sedans',        'Blackish',        '5 Seater',   'Auto', 2500.00, 'Sedan',     'Available', NULL),
  ('Toyota Innova 2024',   2024, 'mpvs',          'Red',             '7 Seater',   'Auto', 3500.00, 'Family',    'Available', NULL),
  ('Xpander Cross 2024',   2024, 'mpvs',          'Metallic Orange', '7 Seater',   'Auto', 3500.00, 'Family',    'Available', NULL),
  ('Toyota Avanza 2025',   2025, 'mpvs',          'Red',             '7 Seater',   'Auto', 2500.00, NULL,        'Available', NULL),
  ('Toyota Hilux 2024',    2024, 'pickup-trucks', 'Black',           '5 Seater',   'Auto', 3500.00, '4x4',       'Available', NULL),
  ('Nissan Navara 2023',   2023, 'pickup-trucks', 'Silver',          '5 Seater',   'Auto', 3500.00, '4x4',       'Available', NULL),
  ('Std Van 2023+',        2023, 'vans',          'White',           '15+ Seater', 'Auto', 4500.00, 'Group',     'Available', NULL),
  ('High-Roof Van 2024',   2024, 'vans',          'White',           '15+ Seater', 'Auto', 5000.00, 'Group',     'Available', NULL);
