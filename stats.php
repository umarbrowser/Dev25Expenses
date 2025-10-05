<?php
require 'db.php';
ensure_logged_in();

$user_id = $_SESSION['user_id'];

// Total spending for the current month
$stmt = $pdo->prepare("SELECT SUM(amount) AS total FROM expenses
                       WHERE user_id = :uid AND YEAR(date) = YEAR(CURDATE())
                         AND MONTH(date) = MONTH(CURDATE())");
$stmt->execute([':uid' => $user_id]);
$row = $stmt->fetch();
$total_month = $row['total'] ?? 0.00;

// Category-wise spending for the current month
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

<div class="d-flex justify-content-center">
  <canvas id="categoryChart" style="max-width: 350px; max-height: 350px;"></canvas>
</div>


<table class="table mt-4">
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

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const ctx = document.getElementById('categoryChart').getContext('2d');
  const categoryChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: <?= json_encode(array_column($bycat, 'name')) ?>,
      datasets: [{
        data: <?= json_encode(array_map('floatval', array_column($bycat, 'sumamt'))) ?>,
        backgroundColor: [
          '#007bff', '#28a745', '#dc3545', '#ffc107', '#17a2b8',
          '#6f42c1', '#fd7e14', '#20c997', '#6610f2', '#e83e8c'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom'
        },
        title: {
          display: true,
          text: 'Spending by Category (Pie Chart)'
        }
      }
    }
  });
</script>

<?php include 'templates/footer.php'; ?>
