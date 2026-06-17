<?php
/**
 * Central Configuration and Environment Initializer
 * Prime Edge Realiity
 */

// Secure session configuration
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] == 443);
    if ($isSecure) {
        ini_set('session.cookie_secure', 1);
    }
    session_start();
}

// Load Composer Autoloader
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Load Environment Variables (.env)
if (file_exists(__DIR__ . '/.env')) {
    try {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    } catch (Exception $e) {
        // Fallback silently if .env is malformed in some environments
    }
}

// Define Global env() Helper Function
if (!function_exists('env')) {
    /**
     * Get the value of an environment variable or return default.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function env($key, $default = '') {
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }
        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        }
        $val = getenv($key);
        return $val !== false ? $val : $default;
    }
}

if (!function_exists('db')) {
    /**
     * Get the PDO database connection instance.
     *
     * @return PDO
     */
    function db() {
        static $pdo = null;
        if ($pdo === null) {
            $host = env('DB_HOST', 'localhost');
            $port = env('DB_PORT', '3306');
            $db   = env('DB_DATABASE', 'peprealty');
            $user = env('DB_USERNAME', 'root');
            $pass = env('DB_PASSWORD', '');
            $charset = 'utf8mb4';

            // Connect to MySQL server first to check/create database
            $dsnNoDb = "mysql:host=$host;port=$port;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            try {
                $tempPdo = new PDO($dsnNoDb, $user, $pass, $options);
                $tempPdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
                $tempPdo = null;
            } catch (\Exception $e) {
                // Ignore fallback to let the main DSN connection throw if privileges are insufficient
            }

            // Connect to database
            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
            try {
                $pdo = new PDO($dsn, $user, $pass, $options);
                
                // Create table
                $pdo->exec("CREATE TABLE IF NOT EXISTS `admins` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(100) NOT NULL,
                    `username` VARCHAR(50) NOT NULL UNIQUE,
                    `password` VARCHAR(255) NOT NULL,
                    `email` VARCHAR(100) NOT NULL UNIQUE,
                    `profile_pic` VARCHAR(255) DEFAULT NULL,
                    `mobile` VARCHAR(20) DEFAULT NULL,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

                // Check missing columns and add them dynamically (for pre-existing databases)
                $columns = $pdo->query("DESCRIBE `admins`")->fetchAll(PDO::FETCH_COLUMN);
                if (!in_array('name', $columns)) {
                    $pdo->exec("ALTER TABLE `admins` ADD `name` VARCHAR(100) NOT NULL AFTER `id`");
                }
                if (!in_array('profile_pic', $columns)) {
                    $pdo->exec("ALTER TABLE `admins` ADD `profile_pic` VARCHAR(255) DEFAULT NULL AFTER `email`");
                }
                if (!in_array('mobile', $columns)) {
                    $pdo->exec("ALTER TABLE `admins` ADD `mobile` VARCHAR(20) DEFAULT NULL AFTER `profile_pic`");
                }

                // Seed default admin
                $stmt = $pdo->query("SELECT COUNT(*) FROM `admins`");
                if ($stmt->fetchColumn() == 0) {
                    $defaultName = 'Administrator';
                    $defaultUser = 'admin';
                    $defaultHash = password_hash('admin123', PASSWORD_BCRYPT);
                    $defaultEmail = 'admin@peprealty.com';
                    $defaultPic = 'user-avtar.png';
                    $defaultMobile = '+919310104249';
                    
                    $insert = $pdo->prepare("INSERT INTO `admins` (`name`, `username`, `password`, `email`, `profile_pic`, `mobile`) VALUES (?, ?, ?, ?, ?, ?)");
                    $insert->execute([$defaultName, $defaultUser, $defaultHash, $defaultEmail, $defaultPic, $defaultMobile]);
                }
            } catch (\PDOException $e) {
                throw new \PDOException("Database connection error: " . $e->getMessage(), (int)$e->getCode());
            }
        }
        return $pdo;
    }
}

