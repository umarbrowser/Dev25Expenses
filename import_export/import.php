<?php
require '../db.php';
ensure_logged_in();

$user_id = $_SESSION['user_id'];
$errors = [];
$success_count = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['csvfile']) || $_FILES['csvfile']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'CSV file upload failed';
    } else {
        $path = $_FILES['csvfile']['tmp_name'];
        $fp = fopen($path, 'r');
        if ($fp === false) {
            $errors[] = 'Cannot open file';
        } else {
            // Assume first row is header
            $header = fgetcsv($fp);
            // Expected: date, description, category, amount
            while (($row = fgetcsv($fp)) !== false) {
                // Map columns (simple approach)
                $date = $row[0] ?? '';
                $desc = $row[1] ?? '';
                $catname = $row[2] ?? '';
                $amt = $row[3] ?? '';

                if (!$date || !is_numeric($amt)) {
                    continue; // skip invalid row
                }
                // find category
                $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = ?");
                $stmt->execute([$catname]);
                $c = $stmt->fetch();
                $catid = $c ? $c['id'] : null;

                $ins = $pdo->prepare("INSERT INTO expenses (user_id, category_id, date, description, amount)
                                      VALUES (:uid, :cid, :dt, :desc, :amt)");
                $ins->execute([
                    ':uid' => $user_id,
                    ':cid' => $catid,
                    ':dt' => $date,
                    ':desc' => $desc,
                    ':amt' => $amt
                ]);
                $success_count++;
            }
            fclose($fp);
        }
    }
}

include '../templates/header.php';
?>
<h2>Import CSV</h2>
<?php if ($errors): ?>
  <div class="alert alert-danger"><ul><?php foreach ($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>"; ?></ul></div>
<?php endif; ?>
<?php if ($success_count > 0): ?>
  <div class="alert alert-success"><?= $success_count ?> records imported.</div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
  <div class="mb-3">
    <label class="form-label">CSV File (columns: date, description, category, amount)</label>
    <input type="file" name="csvfile" accept=".csv" class="form-control">
  </div>
  <button class="btn btn-primary">Import</button>
</form>

<?php include '../templates/footer.php'; ?>
