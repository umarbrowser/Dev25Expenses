<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Database connection
$host = 'localhost';
$dbname = 'dev25expenses';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Database connected successfully<br>";
} catch(PDOException $e) {
    die("✗ Database connection failed: " . $e->getMessage());
}

// Check if users table exists and has data
$stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
$userCount = $stmt->fetch()['count'];
echo "✓ Users in database: " . $userCount . "<br>";

// Show all users (for debugging)
$stmt = $pdo->query("SELECT id, username, email, LENGTH(password) as pass_length FROM users");
$users = $stmt->fetchAll();

echo "<h3>Users in database:</h3>";
echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Password Length</th></tr>";
foreach ($users as $user) {
    echo "<tr>";
    echo "<td>" . $user['id'] . "</td>";
    echo "<td>" . $user['username'] . "</td>";
    echo "<td>" . $user['email'] . "</td>";
    echo "<td>" . $user['pass_length'] . "</td>";
    echo "</tr>";
}
echo "</table>";

// Test password verification
echo "<h3>Password Verification Test:</h3>";
$testPassword = 'admin123';
$stmt = $pdo->prepare("SELECT password FROM users WHERE username = 'admin'");
$stmt->execute();
$dbPassword = $stmt->fetchColumn();

if ($dbPassword) {
    echo "Stored hash: " . substr($dbPassword, 0, 20) . "...<br>";
    echo "Test password: 'admin123'<br>";
    
    if (password_verify($testPassword, $dbPassword)) {
        echo "✓ Password verification SUCCESSFUL!<br>";
    } else {
        echo "✗ Password verification FAILED!<br>";
        echo "Possible issues:<br>";
        echo "- Password wasn't hashed properly during setup<br>";
        echo "- Different password used<br>";
    }
} else {
    echo "✗ No admin user found!<br>";
}

// Test session
echo "<h3>Session Test:</h3>";
echo "Session ID: " . session_id() . "<br>";
echo "Session data: " . print_r($_SESSION, true) . "<br>";

// Test login function
echo "<h3>Login Function Test:</h3>";
function testLogin($username, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "User found: " . $user['username'] . "<br>";
        echo "Password verify result: " . (password_verify($password, $user['password']) ? 'TRUE' : 'FALSE') . "<br>";
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            echo "✓ Login successful! Session set.<br>";
            return true;
        }
    }
    echo "✗ Login failed!<br>";
    return false;
}

testLogin('admin', 'admin123');

echo "<h3>Final Session Status:</h3>";
echo "Logged in: " . (isset($_SESSION['user_id']) ? 'YES (User ID: ' . $_SESSION['user_id'] . ')' : 'NO') . "<br>";
?>