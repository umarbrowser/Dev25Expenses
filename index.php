<?php
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
include 'templates/header.php';
?>
<h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></h1>
<p>This is your dashboard. Use the navigation above to manage expenses.</p>
<?php include 'templates/footer.php'; ?>
