-- ============================================================
-- Migration v2 — Safe ALTER TABLE for existing databases
-- Run this ONLY if you already created the v1 schema and have
-- existing data you want to keep. Skip if using schema.sql fresh.
-- ============================================================

-- USE `fleet_db`;

-- Add new columns (IF NOT EXISTS not supported for columns, so
-- wrap in a procedure to avoid errors on re-run)
DELIMITER //
CREATE PROCEDURE migrate_v2()
BEGIN
    -- category
    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
                   WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='vehicles' AND COLUMN_NAME='category') THEN
        ALTER TABLE `vehicles` ADD COLUMN `category` ENUM('hatchbacks','sedans','mpvs','pickup-trucks','vans') NOT NULL DEFAULT 'hatchbacks' AFTER `year`;
    END IF;

    -- color
    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
                   WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='vehicles' AND COLUMN_NAME='color') THEN
        ALTER TABLE `vehicles` ADD COLUMN `color` VARCHAR(50) NOT NULL DEFAULT '' AFTER `category`;
    END IF;

    -- seating
    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
                   WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='vehicles' AND COLUMN_NAME='seating') THEN
        ALTER TABLE `vehicles` ADD COLUMN `seating` VARCHAR(20) NOT NULL DEFAULT '5 Seater' AFTER `color`;
    END IF;

    -- transmission
    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
                   WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='vehicles' AND COLUMN_NAME='transmission') THEN
        ALTER TABLE `vehicles` ADD COLUMN `transmission` ENUM('Auto','Manual') NOT NULL DEFAULT 'Auto' AFTER `seating`;
    END IF;

    -- price_per_day
    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
                   WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='vehicles' AND COLUMN_NAME='price_per_day') THEN
        ALTER TABLE `vehicles` ADD COLUMN `price_per_day` DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER `transmission`;
    END IF;

    -- badge_label
    IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
                   WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='vehicles' AND COLUMN_NAME='badge_label') THEN
        ALTER TABLE `vehicles` ADD COLUMN `badge_label` VARCHAR(30) DEFAULT NULL AFTER `price_per_day`;
    END IF;
END //
DELIMITER ;

CALL migrate_v2();
DROP PROCEDURE IF EXISTS migrate_v2;

-- ============================================================
-- Seed / update the 11 canonical vehicles with full metadata
-- (uses INSERT ... ON DUPLICATE KEY so it's safe to re-run)
-- ============================================================
-- If vehicles already exist by name, update them:
UPDATE `vehicles` SET `category`='hatchbacks',    `color`='Black',           `seating`='5 Seater',   `transmission`='Auto', `price_per_day`=1500, `badge_label`='Economy' WHERE `model_name`='Toyota Wigo 2019';
UPDATE `vehicles` SET `category`='hatchbacks',    `color`='Silver',          `seating`='5 Seater',   `transmission`='Auto', `price_per_day`=1500, `badge_label`=NULL      WHERE `model_name`='Toyota Wigo 2021';
UPDATE `vehicles` SET `category`='hatchbacks',    `color`='Silver',          `seating`='5 Seater',   `transmission`='Auto', `price_per_day`=1800, `badge_label`=NULL      WHERE `model_name`='Toyota Wigo 2026';
UPDATE `vehicles` SET `category`='sedans',        `color`='Blackish',        `seating`='5 Seater',   `transmission`='Auto', `price_per_day`=2500, `badge_label`='Sedan'   WHERE `model_name`='Toyota Vios 2024';
UPDATE `vehicles` SET `category`='mpvs',          `color`='Red',             `seating`='7 Seater',   `transmission`='Auto', `price_per_day`=3500, `badge_label`='Family'  WHERE `model_name`='Toyota Innova 2024';
UPDATE `vehicles` SET `category`='mpvs',          `color`='Metallic Orange', `seating`='7 Seater',   `transmission`='Auto', `price_per_day`=3500, `badge_label`='Family'  WHERE `model_name`='Xpander Cross 2024';
UPDATE `vehicles` SET `category`='mpvs',          `color`='Red',             `seating`='7 Seater',   `transmission`='Auto', `price_per_day`=2500, `badge_label`=NULL      WHERE `model_name`='Toyota Avanza 2025';
UPDATE `vehicles` SET `category`='pickup-trucks', `color`='Black',           `seating`='5 Seater',   `transmission`='Auto', `price_per_day`=3500, `badge_label`='4x4'     WHERE `model_name`='Toyota Hilux 2024';
UPDATE `vehicles` SET `category`='pickup-trucks', `color`='Silver',          `seating`='5 Seater',   `transmission`='Auto', `price_per_day`=3500, `badge_label`='4x4'     WHERE `model_name`='Nissan Navara 2023';
UPDATE `vehicles` SET `category`='vans',          `color`='White',           `seating`='15+ Seater', `transmission`='Auto', `price_per_day`=4500, `badge_label`='Group'   WHERE `model_name`='Std Van 2023+';
UPDATE `vehicles` SET `category`='vans',          `color`='White',           `seating`='15+ Seater', `transmission`='Auto', `price_per_day`=5000, `badge_label`='Group'   WHERE `model_name`='High-Roof Van 2024';
