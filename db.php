<?php
session_start();

// Database config
$host = 'localhost';
$db   = 'expense_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// DSN
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Helper: require login
function ensure_logged_in() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}
?>
