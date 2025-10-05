<?php
require 'db.php';
ensure_logged_in();

// Fetch filters
$user_id = $_SESSION['user_id'];
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';
$category_id = $_GET['category_id'] ?? '';

// Build query
$sql = "SELECT e.*, c.name AS category_name
        FROM expenses e
        LEFT JOIN categories c ON e.category_id = c.id
        WHERE e.user_id = :uid";
$params = [':uid' => $user_id];

if ($date_from) {
    $sql .= " AND e.date >= :df";
    $params[':df'] = $date_from;
}
if ($date_to) {
    $sql .= " AND e.date <= :dt";
    $params[':dt'] = $date_to;
}
if ($category_id) {
    $sql .= " AND e.category_id = :cid";
    $params[':cid'] = $category_id;
}

$sql .= " ORDER BY e.date DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$expenses = $stmt->fetchAll();

// Fetch categories for filter dropdown
$cats = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

include 'templates/header.php';
?>
<h2>Your Expenses</h2>

<form class="row g-3 mb-3" method="get">
  <div class="col-auto">
    <input type="date" name="date_from" class="form-control" placeholder="From" value="<?= htmlspecialchars($date_from) ?>">
  </div>
  <div class="col-auto">
    <input type="date" name="date_to" class="form-control" placeholder="To" value="<?= htmlspecialchars($date_to) ?>">
  </div>
  <div class="col-auto">
    <select name="category_id" class="form-select">
      <option value="">All categories</option>
      <?php foreach ($cats as $c): ?>
        <option value="<?= $c['id'] ?>" <?= ($category_id == $c['id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($c['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-auto">
    <button class="btn btn-primary">Filter</button>
  </div>
</form>

<a href="add_expense.php" class="btn btn-success mb-3">Add Expense</a>

<table class="table table-striped">
  <thead>
    <tr>
      <th>Date</th>
      <th>Description</th>
      <th>Category</th>
      <th>Amount</th>
      <th class="table-actions">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($expenses as $e): ?>
    <tr>
      <td><?= htmlspecialchars($e['date']) ?></td>
      <td><?= htmlspecialchars($e['description']) ?></td>
      <td><?= htmlspecialchars($e['category_name'] ?? '—') ?></td>
      <td><?= number_format($e['amount'], 2) ?></td>
      <td>
        <a href="edit_expense.php?id=<?= $e['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
        <a href="delete_expense.php?id=<?= $e['id'] ?>" class="btn btn-sm btn-danger"
           onclick="return confirm('Are you sure?')">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php include 'templates/footer.php'; ?>
