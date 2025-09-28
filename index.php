
<?php
ini_set('session.cookie_lifetime', 0); // until browser closes
ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', 'localhost'); // or your dev domain
ini_set('session.cookie_secure', 0); // allow HTTP
ini_set('session.cookie_httponly', 1);

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session at the VERY TOP
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include required files
require_once 'config/database.php';
require_once 'includes/auth.php';
require_once 'includes/expenses.php';

// Initialize variables
$error = '';
$message = '';
$isLoggedIn = isLoggedIn();
$username = getUsername();

error_log("=== PAGE LOAD ===");
error_log("Session ID: " . session_id());
error_log("Logged in: " . ($isLoggedIn ? 'YES' : 'NO'));
error_log("User ID: " . getUserId());
error_log("Username: " . getUsername());

// Handle login
if (isset($_POST['login'])) {
    error_log("Login form submitted");
    $username_input = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if (loginUser($username_input, $password)) {
        error_log("Login successful, redirecting...");
        $_SESSION['message'] = "Login successful! Welcome back, " . $username_input . "!";
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password";
        error_log("Login failed for: " . $username_input);
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    error_log("Logout requested");
    logoutUser();
    header("Location: index.php");
    exit();
}

// Handle registration
if (isset($_POST['register'])) {
    $username_input = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    if ($password === $confirm_password) {
        if (registerUser($username_input, $email, $password)) {
            $_SESSION['message'] = "Registration successful! Please login.";
            header("Location: index.php?page=login");
            exit();
        } else {
            $error = "Registration failed. Username or email may already exist.";
        }
    } else {
        $error = "Passwords do not match";
    }
}

// Handle expense operations (only if logged in)
if ($isLoggedIn) {
    // Add expense
    if (isset($_POST['add_expense'])) {
        $description = trim($_POST['description']);
        $amount = floatval($_POST['amount']);
        $category = $_POST['category'];
        $date = $_POST['date'];
        
        error_log("Expense form submitted: $description, $amount, $category, $date");
        
        if (!empty($description) && $amount > 0 && !empty($category) && !empty($date)) {
            if (addExpense(getUserId(), $description, $amount, $category, $date)) {
                $_SESSION['message'] = "Expense added successfully!";
                header("Location: index.php?page=expenses");
                exit();
            } else {
                $error = "Failed to add expense. Please try again.";
            }
        } else {
            $error = "Please fill in all required fields correctly.";
        }
    }
    
    // Update expense
    if (isset($_POST['update_expense'])) {
        $expense_id = intval($_POST['expense_id']);
        $description = trim($_POST['description']);
        $amount = floatval($_POST['amount']);
        $category = $_POST['category'];
        $date = $_POST['date'];
        
        if (updateExpense($expense_id, getUserId(), $description, $amount, $category, $date)) {
            $_SESSION['message'] = "Expense updated successfully!";
            header("Location: index.php?page=expenses");
            exit();
        } else {
            $error = "Failed to update expense.";
        }
    }
    
    // Delete expense
    if (isset($_GET['delete_expense'])) {
        $expense_id = intval($_GET['delete_expense']);
        
        if (deleteExpense($expense_id, getUserId())) {
            $_SESSION['message'] = "Expense deleted successfully!";
            header("Location: index.php?page=expenses");
            exit();
        } else {
            $error = "Failed to delete expense.";
        }
    }
}

// Get current page
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Simple navigation - show different links based on login status
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dev25Expenses - Professional Expense Tracker</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
                        <li><a href="index.php" class="<?php echo $page === 'dashboard' ? 'active' : ''; ?>">
                            <i class="fas fa-home"></i> Dashboard
                        </a></li>
                        <?php if ($isLoggedIn): ?>
                            <li><a href="index.php?page=expenses" class="<?php echo $page === 'expenses' ? 'active' : ''; ?>">
                                <i class="fas fa-receipt"></i> Expenses
                            </a></li>
                            <li><a href="index.php?page=reports" class="<?php echo $page === 'reports' ? 'active' : ''; ?>">
                                <i class="fas fa-chart-bar"></i> Reports
                            </a></li>
                        <?php else: ?>
                            <li><a href="index.php?page=login" class="<?php echo $page === 'login' ? 'active' : ''; ?>">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a></li>
                            <li><a href="index.php?page=register" class="<?php echo $page === 'register' ? 'active' : ''; ?>">
                                <i class="fas fa-user-plus"></i> Register
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
                        <a href="index.php?page=login" class="btn btn-outline">Login</a>
                        <a href="index.php?page=register" class="btn btn-primary">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <div class="container main-content">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="notification show success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($_SESSION['message']); ?>
                <button class="close-notification">&times;</button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="notification show error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error); ?>
                <button class="close-notification">&times;</button>
            </div>
        <?php endif; ?>

        <?php
        // Debug info
        if (isset($_GET['debug'])) {
            echo "<div class='debug-info'>";
            echo "<strong>Debug Information:</strong><br>";
            echo "Session ID: " . session_id() . "<br>";
            echo "Logged in: " . ($isLoggedIn ? 'YES' : 'NO') . "<br>";
            echo "User ID: " . (getUserId() ?: 'NULL') . "<br>";
            echo "Username: " . (getUsername() ?: 'NULL') . "<br>";
            echo "Current page: " . $page . "<br>";
            echo "</div>";
        }
        ?>

        <?php
        // Include the appropriate page
        switch ($page) {
            case 'login':
                include 'pages/login.php';
                break;
            case 'register':
                include 'pages/register.php';
                break;
            case 'expenses':
                if ($isLoggedIn) {
                    include 'pages/expenses.php';
                } else {
                    include 'pages/login.php';
                }
                break;
            case 'reports':
                if ($isLoggedIn) {
                    include 'pages/reports.php';
                } else {
                    include 'pages/login.php';
                }
                break;
            default:
                include 'pages/dashboard.php';
                break;
        }
        ?>
    </div>

    <!-- Expense Modal -->
    <?php if ($isLoggedIn): ?>
    <div class="modal" id="expenseModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Add New Expense</h3>
                <span class="close">&times;</span>
            </div>
            <form method="POST" action="index.php?page=expenses" id="expenseForm">
                <input type="hidden" name="expense_id" id="expenseId">
                <div class="form-group">
                    <label for="modalDescription">Description *</label>
                    <input type="text" id="modalDescription" name="description" class="form-control" required 
                           placeholder="What did you spend on?">
                </div>
                
                <div class="form-group">
                    <label for="modalAmount">Amount *</label>
                    <input type="number" id="modalAmount" name="amount" class="form-control" 
                           step="0.01" min="0.01" required placeholder="0.00">
                </div>
                
                <div class="form-group">
                    <label for="modalCategory">Category *</label>
                    <select id="modalCategory" name="category" class="form-control" required>
                        <option value="">Select a category</option>
                        <option value="Food">Food & Dining</option>
                        <option value="Transportation">Transportation</option>
                        <option value="Utilities">Utilities</option>
                        <option value="Entertainment">Entertainment</option>
                        <option value="Shopping">Shopping</option>
                        <option value="Health">Health</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="modalDate">Date *</label>
                    <input type="date" id="modalDate" name="date" class="form-control" required 
                           max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>">
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" name="add_expense" class="btn btn-primary" id="submitButton">
                        <i class="fas fa-plus"></i> Add Expense
                    </button>
                    <button type="button" class="btn btn-outline" onclick="document.getElementById('expenseModal').style.display='none'">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <script src="assets/js/script.js"></script>
    
    <script>
    // Expense modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('expenseModal');
        const closeBtn = document.querySelector('.close');
        
        // Show modal when Add Expense button is clicked
        const addExpenseBtn = document.getElementById('addExpenseBtn');
        const addFirstExpense = document.getElementById('addFirstExpense');
        
        if (addExpenseBtn) {
            addExpenseBtn.addEventListener('click', function() {
                resetExpenseForm();
                modal.style.display = 'flex';
            });
        }
        
        if (addFirstExpense) {
            addFirstExpense.addEventListener('click', function(e) {
                e.preventDefault();
                resetExpenseForm();
                modal.style.display = 'flex';
            });
        }
        
        // Close modal
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });
        }
        
        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
        
        // Edit expense buttons
        const editButtons = document.querySelectorAll('.action-btn.edit');
        editButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const expense = {
                    id: this.dataset.id,
                    description: this.dataset.description,
                    amount: this.dataset.amount,
                    category: this.dataset.category,
                    date: this.dataset.date
                };
                
                // Fill the form with expense data
                document.getElementById('expenseId').value = expense.id;
                document.getElementById('modalDescription').value = expense.description;
                document.getElementById('modalAmount').value = expense.amount;
                document.getElementById('modalCategory').value = expense.category;
                document.getElementById('modalDate').value = expense.date;
                
                // Update modal title and button
                document.getElementById('modalTitle').textContent = 'Edit Expense';
                document.getElementById('submitButton').innerHTML = '<i class="fas fa-save"></i> Update Expense';
                document.getElementById('submitButton').name = 'update_expense';
                
                // Show the modal
                modal.style.display = 'flex';
            });
        });
        
        // Delete expense confirmation
        const deleteButtons = document.querySelectorAll('.action-btn.delete');
        deleteButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const expenseId = this.dataset.id;
                if (confirm('Are you sure you want to delete this expense? This action cannot be undone.')) {
                    window.location.href = 'index.php?page=expenses&delete_expense=' + expenseId;
                }
            });
        });
        
        function resetExpenseForm() {
            document.getElementById('expenseForm').reset();
            document.getElementById('expenseId').value = '';
            document.getElementById('modalDate').value = '<?php echo date('Y-m-d'); ?>';
            document.getElementById('modalTitle').textContent = 'Add New Expense';
            document.getElementById('submitButton').innerHTML = '<i class="fas fa-plus"></i> Add Expense';
            document.getElementById('submitButton').name = 'add_expense';
        }
        
        // Close notifications
        const closeNotifications = document.querySelectorAll('.close-notification');
        closeNotifications.forEach(btn => {
            btn.addEventListener('click', function() {
                this.parentElement.style.display = 'none';
            });
        });
        
        // Auto-hide notifications after 5 seconds
        const notifications = document.querySelectorAll('.notification.show');
        notifications.forEach(notification => {
            setTimeout(() => {
                notification.style.display = 'none';
            }, 5000);
        });
    });
    
    function confirmDelete(expenseId) {
        if (confirm('Are you sure you want to delete this expense? This action cannot be undone.')) {
            window.location.href = 'index.php?page=expenses&delete_expense=' + expenseId;
        }
    }
    </script>
</body>
</html>