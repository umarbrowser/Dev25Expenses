<?php
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dev25Expenses - Expense Tracker</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-chart-pie"></i>
                    Dev25Expenses
                </div>
                <nav>
                    <ul>
                        <li><a href="index.php">Dashboard</a></li>
                        <?php if (isLoggedIn()): ?>
                            <li><a href="index.php?page=expenses">Expenses</a></li>
                            <li><a href="index.php?page=reports">Reports</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <div class="auth-buttons">
                    <?php if (isLoggedIn()): ?>
                        <span>Welcome, <?php echo htmlspecialchars(getUsername()); ?></span>
                        <a href="index.php?logout=1" class="btn btn-outline">Logout</a>
                    <?php else: ?>
                        <a href="index.php?page=login" class="btn btn-outline">Login</a>
                        <a href="index.php?page=register" class="btn btn-primary">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <div class="container main-content">
        <?php if (isset($_GET['message'])): ?>
            <div class="notification show <?php echo isset($_GET['error']) ? 'error' : 'success'; ?>">
                <?php echo htmlspecialchars($_GET['message']); ?>
                <span class="close-notification">&times;</span>
            </div>
        <?php endif; ?>