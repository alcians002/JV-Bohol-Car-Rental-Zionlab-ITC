<?php
require_once __DIR__ . '/../config/database.php';

class Admin
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
        // Automatically ensure the admins table exists
        $this->ensureTableExists();
    }

    private function ensureTableExists(): void
    {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS `admins` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `username` VARCHAR(50) NOT NULL UNIQUE,
                `password_hash` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // Seed a default admin if table is completely empty
        $stmt = $this->db->query("SELECT COUNT(*) FROM `admins`");
        if ((int) $stmt->fetchColumn() === 0) {
            // Default credentials: admin / admin123  (Encrypted/Hashed)
            $hash = password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 12]);
            $insert = $this->db->prepare("INSERT INTO `admins` (`username`, `password_hash`) VALUES (:usr, :hash)");
            $insert->execute([':usr' => 'admin', ':hash' => $hash]);
        }
    }

    /**
     * Verify credentials.
     * Uses password_verify against the stored BCRYPT hash.
     */
    public function verify(string $username, string $password): bool
    {
        $stmt = $this->db->prepare("SELECT `password_hash` FROM `admins` WHERE `username` = :usr LIMIT 1");
        $stmt->execute([':usr' => $username]);
        $row = $stmt->fetch();

        if ($row && password_verify($password, $row['password_hash'])) {
            return true;
        }
        return false;
    }
}
