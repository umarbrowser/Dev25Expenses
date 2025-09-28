<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';

// Database connection
try {
    require_once 'config/database.php';
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dev25Expenses - Professional Expense Tracker</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>💰</text></svg>">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-chart-pie logo-icon"></i>
                    Dev25Expenses
                </div>
                <nav>
                    <ul>
                        <li><a href="index.php" class="<?php echo (!isset($_GET['page']) || $_GET['page'] === 'dashboard') ? 'active' : ''; ?>">
                            <i class="fas fa-home"></i> Dashboard
                        </a></li>
                        <?php if ($isLoggedIn): ?>
                            <li><a href="index.php?page=expenses" class="<?php echo (isset($_GET['page']) && $_GET['page'] === 'expenses') ? 'active' : ''; ?>">
                                <i class="fas fa-receipt"></i> Expenses
                            </a></li>
                            <li><a href="index.php?page=reports" class="<?php echo (isset($_GET['page']) && $_GET['page'] === 'reports') ? 'active' : ''; ?>">
                                <i class="fas fa-chart-bar"></i> Reports
                            </a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <div class="auth-buttons">
                    <?php if ($isLoggedIn): ?>
                        <span class="user-welcome">
                            <i class="fas fa-user-circle"></i> Welcome, <?php echo htmlspecialchars($username); ?>
                        </span>
                        <a href="index.php?logout=1" class="btn btn-outline">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    <?php else: ?>
                        <a href="index.php?page=login" class="btn btn-outline">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                        <a href="index.php?page=register" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Register
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <div class="container main-content">
        <?php if (isset($_GET['message'])): ?>
            <div class="notification show <?php echo isset($_GET['error']) ? 'error' : 'success'; ?>">
                <i class="fas fa-<?php echo isset($_GET['error']) ? 'exclamation' : 'check'; ?>-circle"></i>
                <?php echo htmlspecialchars(urldecode($_GET['message'])); ?>
                <button class="close-notification">&times;</button>
            </div>
        <?php endif; ?>