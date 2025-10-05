<?php
require 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    
    if (!$username) $errors[] = 'Username is required';
    if (!$email) $errors[] = 'Email is required';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email';
    if (!$password) $errors[] = 'Password is required';
    if ($password !== $password2) $errors[] = 'Passwords do not match';

    if (empty($errors)) {
        // Check uniqueness
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errors[] = 'Username or email already taken';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)');
            $stmt->execute([$username, $email, $hash]);
            header('Location: login.php');
            exit;
        }
    }
}

include 'templates/header.php';
?>
<h2>Register</h2>
<?php if ($errors): ?>
  <div class="alert alert-danger">
    <ul>
    <?php foreach ($errors as $e): ?>
      <li><?= htmlspecialchars($e) ?></li>
    <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post">
  <div class="mb-3">
    <label class="form-label">Username</label>
    <input name="username" class="form-control" value="<?= htmlspecialchars($username ?? '') ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input name="email" class="form-control" value="<?= htmlspecialchars($email ?? '') ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Password</label>
    <input name="password" type="password" class="form-control">
  </div>
  <div class="mb-3">
    <label class="form-label">Confirm Password</label>
    <input name="password2" type="password" class="form-control">
  </div>
  <button class="btn btn-primary">Register</button>
</form>

<?php include 'templates/footer.php'; ?>
