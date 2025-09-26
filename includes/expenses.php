<?php
// Add new expense
function addExpense($userId, $description, $amount, $category, $date) {
    global $pdo;
    
    $stmt = $pdo->prepare("INSERT INTO expenses (user_id, description, amount, category, date) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$userId, $description, $amount, $category, $date]);
}

// Get all expenses for a user
function getExpenses($userId, $limit = null) {
    global $pdo;
    
    $sql = "SELECT * FROM expenses WHERE user_id = ? ORDER BY date DESC";
    if ($limit) {
        $sql .= " LIMIT " . intval($limit);
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

// Get expense by ID
function getExpenseById($id, $userId) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM expenses WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $userId]);
    return $stmt->fetch();
}

// Update expense
function updateExpense($id, $userId, $description, $amount, $category, $date) {
    global $pdo;
    
    $stmt = $pdo->prepare("UPDATE expenses SET description = ?, amount = ?, category = ?, date = ? WHERE id = ? AND user_id = ?");
    return $stmt->execute([$description, $amount, $category, $date, $id, $userId]);
}

// Delete expense
function deleteExpense($id, $userId) {
    global $pdo;
    
    $stmt = $pdo->prepare("DELETE FROM expenses WHERE id = ? AND user_id = ?");
    return $stmt->execute([$id, $userId]);
}

// Get expense statistics
function getExpenseStats($userId) {
    global $pdo;
    
    // Total spent
    $stmt = $pdo->prepare("SELECT SUM(amount) as total FROM expenses WHERE user_id = ?");
    $stmt->execute([$userId]);
    $total = $stmt->fetch()['total'] ?? 0;
    
    // By category
    $stmt = $pdo->prepare("SELECT category, SUM(amount) as total FROM expenses WHERE user_id = ? GROUP BY category");
    $stmt->execute([$userId]);
    $byCategory = $stmt->fetchAll();
    
    // Recent expenses
    $recent = getExpenses($userId, 5);
    
    return [
        'total' => $total,
        'byCategory' => $byCategory,
        'recent' => $recent
    ];
}

// Export expenses to CSV
function exportExpensesToCSV($userId) {
    $expenses = getExpenses($userId);
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="expenses_export.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Description', 'Amount', 'Category', 'Date']);
    
    foreach ($expenses as $expense) {
        fputcsv($output, [
            $expense['id'],
            $expense['description'],
            $expense['amount'],
            $expense['category'],
            $expense['date']
        ]);
    }
    
    fclose($output);
    exit();
}

// Import expenses from CSV
function importExpensesFromCSV($userId, $filePath) {
    $imported = 0;
    $failed = 0;
    
    if (($handle = fopen($filePath, "r")) !== FALSE) {
        // Skip header row
        fgetcsv($handle);
        
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (count($data) < 4) continue;
            
            $description = $data[0] ?? '';
            $amount = floatval($data[1] ?? 0);
            $category = $data[2] ?? 'Other';
            $date = $data[3] ?? date('Y-m-d');
            
            if (!empty($description) && $amount > 0) {
                if (addExpense($userId, $description, $amount, $category, $date)) {
                    $imported++;
                } else {
                    $failed++;
                }
            } else {
                $failed++;
            }
        }
        fclose($handle);
    }
    
    return ['imported' => $imported, 'failed' => $failed];
}
?>