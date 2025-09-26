<?php
require_once 'config/database.php';
require_once 'includes/auth.php';
require_once 'includes/expenses.php';

// Handle authentication actions
if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($password !== $confirm_password) {
        header("Location: index.php?page=register&error=1&message=Passwords do not match");
        exit();
    }
    
    $result = registerUser($username, $email, $password);
    if ($result === true) {
        header("Location: index.php?page=login&message=Registration successful. Please login.");
        exit();
    } else {
        header("Location: index.php?page=register&error=1&message=" . urlencode($result));
        exit();
    }
}

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    $result = loginUser($username, $password);
    if ($result === true) {
        header("Location: index.php?message=Login successful");
        exit();
    } else {
        header("Location: index.php?page=login&error=1&message=" . urlencode($result));
        exit();
    }
}

if (isset($_GET['logout'])) {
    logoutUser();
}

// Handle expense actions
if (isLoggedIn()) {
    $userId = getUserId();
    
    // Add/Edit expense
    if (isset($_POST['save_expense'])) {
        $description = trim($_POST['description']);
        $amount = floatval($_POST['amount']);
        $category = $_POST['category'];
        $date = $_POST['date'];
        $expenseId = $_POST['expense_id'] ?? null;
        
        if ($expenseId) {
            // Update existing expense
            if (updateExpense($expenseId, $userId, $description, $amount, $category, $date)) {
                header("Location: index.php?page=expenses&message=Expense updated successfully");
            } else {
                header("Location: index.php?page=expenses&error=1&message=Failed to update expense");
            }
        } else {
            // Add new expense
            if (addExpense($userId, $description, $amount, $category, $date)) {
                header("Location: index.php?page=expenses&message=Expense added successfully");
            } else {
                header("Location: index.php?page=expenses&error=1&message=Failed to add expense");
            }
        }
        exit();
    }
    
    // Delete expense
    if (isset($_GET['delete_expense'])) {
        $expenseId = intval($_GET['delete_expense']);
        if (deleteExpense($expenseId, $userId)) {
            header("Location: index.php?page=expenses&message=Expense deleted successfully");
        } else {
            header("Location: index.php?page=expenses&error=1&message=Failed to delete expense");
        }
        exit();
    }
    
    // Export expenses
    if (isset($_GET['export'])) {
        exportExpensesToCSV($userId);
    }
    
    // Import expenses
    if (isset($_POST['import'])) {
        if ($_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['csv_file']['tmp_name'];
            $result = importExpensesFromCSV($userId, $tmpName);
            header("Location: index.php?page=expenses&message=Imported " . $result['imported'] . " expenses, " . $result['failed'] . " failed");
        } else {
            header("Location: index.php?page=expenses&error=1&message=File upload failed");
        }
        exit();
    }
}

// Determine which page to show
$page = 'dashboard';
if (isset($_GET['page'])) {
    $allowedPages = ['dashboard', 'expenses', 'reports', 'login', 'register'];
    if (in_array($_GET['page'], $allowedPages)) {
        $page = $_GET['page'];
    }
}

// Redirect to login if not authenticated and trying to access protected pages
if (!isLoggedIn() && !in_array($page, ['login', 'register'])) {
    header("Location: index.php?page=login");
    exit();
}

// Redirect to dashboard if authenticated and trying to access login/register
if (isLoggedIn() && in_array($page, ['login', 'register'])) {
    header("Location: index.php");
    exit();
}

// Include header
require_once 'includes/header.php';

// Include the requested page
switch ($page) {
    case 'dashboard':
        include 'pages/dashboard.php';
        break;
    case 'expenses':
        include 'pages/expenses.php';
        break;
    case 'reports':
        include 'pages/reports.php';
        break;
    case 'login':
        include 'pages/login.php';
        break;
    case 'register':
        include 'pages/register.php';
        break;
    default:
        include 'pages/dashboard.php';
}

// Include footer
require_once 'includes/footer.php';
?>