<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = 'localhost';
$dbname = 'dev25expenses';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    $pdo->exec("USE $dbname");
    
    echo "Database connected successfully!<br>";
    
    // Create tables
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS expenses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        description VARCHAR(255) NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        category VARCHAR(50) NOT NULL,
        date DATE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    echo "Tables created successfully!<br>";
    
    // Create demo user
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    
    // Check if user already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = 'admin'");
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute(['admin', 'admin@dev25.com', $password]);
        echo "Demo user created successfully!<br>";
    } else {
        echo "Demo user already exists!<br>";
    }
    
    echo "<h3>Setup completed successfully!</h3>";
    echo "<p><strong>Demo credentials:</strong></p>";
    echo "<p>Username: <strong>admin</strong></p>";
    echo "<p>Password: <strong>admin123</strong></p>";
    echo "<p><a href='index.php' style='color: blue; text-decoration: underline;'>Go to Application</a></p>";
    
} catch (PDOException $e) {
    die("Setup failed: " . $e->getMessage());
}
?>