-- ============================================================
-- JV Bohol Car Rental — Bookings Migration
-- ============================================================

-- USE `fleet_db`;

-- ------------------------------------------------------------
-- Bookings Table
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `bookings` (
  `id`              INT UNSIGNED      NOT NULL AUTO_INCREMENT,
  `booking_ref`     VARCHAR(20)       NOT NULL UNIQUE COMMENT 'e.g. BHL-1042',
  `vehicle_id`      INT UNSIGNED      NOT NULL,
  `customer_name`   VARCHAR(255)      NOT NULL,
  `customer_email`  VARCHAR(255)      NOT NULL,
  `customer_phone`  VARCHAR(50)       NOT NULL,
  `pickup_date`     DATETIME          NOT NULL,
  `return_date`     DATETIME          NOT NULL,
  `total_price`     DECIMAL(10,2)     NOT NULL DEFAULT 0.00,
  `booking_status`  ENUM('Pending','Confirmed','Active','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
  `payment_status`  ENUM('Unpaid','Deposit Paid','Fully Paid') NOT NULL DEFAULT 'Unpaid',
  `notes`           TEXT              DEFAULT NULL,
  `created_at`      DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Sample Data
-- ------------------------------------------------------------
-- Assuming vehicles 1, 2, and 4 exist from the schema script.
INSERT IGNORE INTO `bookings`
  (`booking_ref`, `vehicle_id`, `customer_name`, `customer_email`, `customer_phone`, `pickup_date`, `return_date`, `total_price`, `booking_status`, `payment_status`, `notes`)
VALUES
  ('BHL-1001', 1, 'John Doe', 'john.doe@example.com', '+63 917 123 4567', DATE_ADD(NOW(), INTERVAL 1 DAY), DATE_ADD(NOW(), INTERVAL 4 DAY), 4500.00, 'Pending', 'Unpaid', 'Flight PR2775 at 9:00 AM. Needs child seat.'),
  ('BHL-1002', 4, 'Jane Smith', 'jane.smith@example.com', '+63 918 987 6543', DATE_ADD(NOW(), INTERVAL 2 DAY), DATE_ADD(NOW(), INTERVAL 5 DAY), 7500.00, 'Confirmed', 'Deposit Paid', 'Hotel dropoff at Henann Resort Alona Beach.'),
  ('BHL-1003', 2, 'Mike Johnson', 'mike.j@example.com', '+63 919 111 2222', DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_ADD(NOW(), INTERVAL 1 DAY), 3000.00, 'Active', 'Fully Paid', 'Standard pickup at seaport.');
