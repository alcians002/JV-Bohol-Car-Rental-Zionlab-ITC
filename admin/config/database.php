<?php
/**
 * Database Configuration — PDO Singleton
 * Provides a single reusable PDO connection to fleet_db.
 */

class Database
{
    private static ?PDO $instance = null;

    private const HOST = 'sql204.infinityfree.com';
    private const DB = 'if0_41224611_fleet_db';
    private const USER = 'if0_41224611';
    private const PASS = 'ALCago186282';
    private const PORT = 3306;

    /** Prevent direct instantiation */
    private function __construct()
    {
    }

    /**
     * Get the shared PDO connection.
     */
    public static function connect(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
                self::HOST,
                self::PORT,
                self::DB
            );

            self::$instance = new PDO($dsn, self::USER, self::PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }

        return self::$instance;
    }
}
