<?php
// Always start session before using $_SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Get logged-in user's ID
function getUserId(): ?int {
    return $_SESSION['user_id'] ?? null;
}

// Get logged-in username or "Guest"
function getUsername(): string {
    return $_SESSION['username'] ?? 'Guest';
}

// Login user
function loginUser(string $username, string $password): bool {
    global $pdo;

    error_log("Login attempt for username: " . $username);

    $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
    if (!$stmt->execute([$username])) {
        error_log("Database query failed");
        return false;
    }

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        error_log("User not found: " . $username);
        return false;
    }

    error_log("User found: " . $user['username']);

    // Verify password using password_verify()
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['username'] = $user['username'];
        error_log("Login successful! User ID: " . $_SESSION['user_id']);
        return true;
    }

    error_log("Password verification failed for user: " . $username);
    return false;
}

// Register user
function registerUser(string $username, string $email, string $password): bool {
    global $pdo;

    // Check if user already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);

    if ($stmt->rowCount() > 0) {
        return false;
    }

    // Hash the password before saving
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    return $stmt->execute([$username, $email, $hashedPassword]);
}

// Logout user
function logoutUser(): void {
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();
}
?>
