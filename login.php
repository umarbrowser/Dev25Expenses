<?php
require 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email) $errors[] = 'Email is required';
    if (!$password) $errors[] = 'Password is required';

    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            // Login OK
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Invalid email or password';
        }
    }
}

include 'templates/header.php';
?>
<h2>Login</h2>
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
    <label class="form-label">Email</label>
    <input name="email" class="form-control" value="<?= htmlspecialchars($email ?? '') ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Password</label>
    <input name="password" type="password" class="form-control">
  </div>
  <button class="btn btn-primary">Login</button>
</form>

<?php include 'templates/footer.php'; ?>
