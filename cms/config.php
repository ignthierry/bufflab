<?php
/**
 * Database Configuration & Connection Helper
 */

define('DB_HOST', '100.90.187.128');
define('DB_USER', 'luna');
define('DB_PASS', 'N2145tb@');
define('DB_NAME', 'bufflabs');

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Throw exception or handle gracefully
            throw new PDOException("Database connection failed: " . $e->getMessage(), (int)$e->getCode());
        }
    }
    return $pdo;
}
