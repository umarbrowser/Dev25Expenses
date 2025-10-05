<?php
require 'db.php';
ensure_logged_in();

$user_id = $_SESSION['user_id'];
$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM expenses WHERE id = :id AND user_id = :uid");
    $stmt->execute([':id' => $id, ':uid' => $user_id]);
}

header('Location: expenses.php');
exit;
