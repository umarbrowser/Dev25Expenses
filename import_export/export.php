<?php
require '../db.php';
ensure_logged_in();

$user_id = $_SESSION['user_id'];

// Optional filters
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

// Build query
$sql = "SELECT e.date, e.description, c.name AS category, e.amount
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

$sql .= " ORDER BY e.date DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

// Send headers
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=expenses_export.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['date', 'description', 'category', 'amount']);

while ($row = $stmt->fetch()) {
    fputcsv($output, [
        $row['date'],
        $row['description'],
        $row['category'],
        $row['amount']
    ]);
}
fclose($output);
exit;
