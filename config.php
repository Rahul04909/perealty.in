<?php
/**
 * Central Configuration and Environment Initializer
 * Prime Edge Realiity
 */

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
