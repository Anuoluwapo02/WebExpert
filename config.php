<?php
// config.php: Database Configuration (Shared File)
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Change to your DB username
define('DB_PASSWORD', ''); // Change to your DB password
define('DB_NAME', 'portfolio_db');

try {
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>