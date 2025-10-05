<?php
require 'db.php';
ensure_logged_in();

$user_id = $_SESSION['user_id'];

// Overall total this month
$stmt = $pdo->prepare("SELECT SUM(amount) AS total FROM expenses
                       WHERE user_id = :uid AND YEAR(date) = YEAR(CURDATE())
                         AND MONTH(date) = MONTH(CURDATE())");
$stmt->execute([':uid' => $user_id]);
$row = $stmt->fetch();
$total_month = $row['total'] ?? 0.00;

// By category this month
$stmt = $pdo->prepare("SELECT c.name, SUM(e.amount) AS sumamt
                       FROM expenses e
                       JOIN categories c ON e.category_id = c.id
                       WHERE e.user_id = :uid
                         AND YEAR(e.date) = YEAR(CURDATE())
                         AND MONTH(e.date) = MONTH(CURDATE())
                       GROUP BY c.id ORDER BY sumamt DESC");
$stmt->execute([':uid' => $user_id]);
$bycat = $stmt->fetchAll();

include 'templates/header.php';
?>

<h2>Statistics (This Month)</h2>

<div class="mb-4">
  <h4>Total spending: ₦<?= number_format($total_month, 2) ?></h4>
</div>

<h5>By Category</h5>
<table class="table">
  <thead><tr><th>Category</th><th>Amount</th></tr></thead>
  <tbody>
  <?php foreach ($bycat as $b): ?>
    <tr>
      <td><?= htmlspecialchars($b['name']) ?></td>
      <td><?= number_format($b['sumamt'], 2) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<?php include 'templates/footer.php'; ?>
