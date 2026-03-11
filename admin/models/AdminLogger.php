<?php
require_once __DIR__ . '/../config/database.php';

class AdminLogger
{
    /**
     * Ensure the log table exists.
     */
    private static function ensureTableExists(PDO $db): void
    {
        $db->exec("
            CREATE TABLE IF NOT EXISTS `admin_logs` (
                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `admin_username` VARCHAR(50) NOT NULL,
                `action` VARCHAR(100) NOT NULL,
                `details` TEXT DEFAULT NULL,
                `ip_address` VARCHAR(45) DEFAULT NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    /**
     * Log an action to the database immutably.
     */
    public static function log(string $action, string $details = ''): void
    {
        try {
            $db = Database::connect();
            self::ensureTableExists($db);

            // Get username from session if available, else 'System'/'Unknown'
            $username = $_SESSION['admin_username'] ?? 'System';
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;

            $stmt = $db->prepare("
                INSERT INTO `admin_logs` (`admin_username`, `action`, `details`, `ip_address`) 
                VALUES (:usr, :act, :det, :ip)
            ");

            $stmt->execute([
                ':usr' => $username,
                ':act' => $action,
                ':det' => $details,
                ':ip' => $ipAddress
            ]);
        } catch (Exception $e) {
            // Failsafe: if DB is down, write to a local log file as fallback so we never lose logs
            $fallbackMessage = sprintf(
                "[%s] %s | %s | %s | %s\n",
                date('Y-m-d H:i:s'),
                $_SESSION['admin_username'] ?? 'System',
                $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN',
                $action,
                $details
            );
            @file_put_contents(__DIR__ . '/../../admin_fallback_activity.log', $fallbackMessage, FILE_APPEND);
        }
    }

    /**
     * Fetch logs for viewing. 
     * No delete or update methods exist ensuring immutability from runtime.
     */
    public static function getLogs(int $limit = 100): array
    {
        try {
            $db = Database::connect();
            self::ensureTableExists($db);

            $stmt = $db->prepare("SELECT * FROM `admin_logs` ORDER BY `id` DESC LIMIT :limit");
            // Binding params for LIMIT needs INT type
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
}
