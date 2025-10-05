<?php
require 'db.php';
ensure_logged_in();

$user_id = $_SESSION['user_id'];
$errors = [];

// Fetch categories
$cats = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'] ?? '';
    $description = trim($_POST['description'] ?? '');
    $amount = $_POST['amount'] ?? '';
    $category_id = $_POST['category_id'] ?: null;

    if (!$date) $errors[] = 'Date is required';
    if (!$amount || !is_numeric($amount)) $errors[] = 'Valid amount is required';

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO expenses (user_id, category_id, date, description, amount)
                               VALUES (:uid, :cid, :dt, :desc, :amt)");
        $stmt->execute([
            ':uid' => $user_id,
            ':cid' => $category_id,
            ':dt' => $date,
            ':desc' => $description,
            ':amt' => $amount
        ]);
        header('Location: expenses.php');
        exit;
    }
}

include 'templates/header.php';
?>
<h2>Add Expense</h2>
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
    <label class="form-label">Date</label>
    <input name="date" type="date" class="form-control" value="<?= htmlspecialchars($date ?? '') ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Description</label>
    <input name="description" class="form-control" value="<?= htmlspecialchars($description ?? '') ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Category</label>
    <select name="category_id" class="form-select">
      <option value="">Select category</option>
      <?php foreach ($cats as $c): ?>
        <option value="<?= $c['id'] ?>" <?= (isset($category_id) && $category_id == $c['id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($c['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">Amount</label>
    <input name="amount" class="form-control" value="<?= htmlspecialchars($amount ?? '') ?>">
  </div>
  <button class="btn btn-success">Save</button>
</form>

<?php include 'templates/footer.php'; ?>
