<?php
function addExpense($userId, $description, $amount, $category, $date) {
    global $pdo;
    
    error_log("Adding expense for user $userId: $description, $amount, $category, $date");
    
    try {
        $stmt = $pdo->prepare("INSERT INTO expenses (user_id, description, amount, category, date) VALUES (?, ?, ?, ?, ?)");
        $result = $stmt->execute([$userId, $description, $amount, $category, $date]);
        
        if ($result) {
            error_log("Expense added successfully! ID: " . $pdo->lastInsertId());
            return true;
        } else {
            error_log("Failed to add expense");
            return false;
        }
    } catch (PDOException $e) {
        error_log("Database error adding expense: " . $e->getMessage());
        return false;
    }
}

function getExpenses($userId, $limit = null) {
    global $pdo;
    
    error_log("Getting expenses for user: $userId");
    
    $sql = "SELECT * FROM expenses WHERE user_id = ? ORDER BY date DESC, id DESC";
    if ($limit) {
        $sql .= " LIMIT " . intval($limit);
    }
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
        $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        error_log("Found " . count($expenses) . " expenses for user $userId");
        return $expenses;
    } catch (PDOException $e) {
        error_log("Database error getting expenses: " . $e->getMessage());
        return [];
    }
}

function deleteExpense($id, $userId) {
    global $pdo;
    
    error_log("Deleting expense $id for user $userId");
    
    try {
        $stmt = $pdo->prepare("DELETE FROM expenses WHERE id = ? AND user_id = ?");
        $result = $stmt->execute([$id, $userId]);
        
        if ($result && $stmt->rowCount() > 0) {
            error_log("Expense $id deleted successfully");
            return true;
        } else {
            error_log("Failed to delete expense $id - no rows affected");
            return false;
        }
    } catch (PDOException $e) {
        error_log("Database error deleting expense: " . $e->getMessage());
        return false;
    }
}

function getExpenseStats($userId) {
    global $pdo;
    
    error_log("Getting expense stats for user: $userId");
    
    $stats = [
        'total' => 0,
        'byCategory' => [],
        'recent' => []
    ];
    
    try {
        // Total spent this month
        $stmt = $pdo->prepare("SELECT SUM(amount) as total FROM expenses WHERE user_id = ? AND MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE())");
        $stmt->execute([$userId]);
        $total = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['total'] = $total['total'] ?? 0;
        
        // By category
        $stmt = $pdo->prepare("SELECT category, SUM(amount) as total FROM expenses WHERE user_id = ? GROUP BY category ORDER BY total DESC");
        $stmt->execute([$userId]);
        $stats['byCategory'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Recent expenses (last 5)
        $stats['recent'] = getExpenses($userId, 5);
        
        error_log("Stats - Total: $" . $stats['total'] . ", Categories: " . count($stats['byCategory']) . ", Recent: " . count($stats['recent']));
        
    } catch (PDOException $e) {
        error_log("Database error getting stats: " . $e->getMessage());
    }
    
    return $stats;
}

function updateExpense($id, $userId, $description, $amount, $category, $date) {
    global $pdo;
    
    error_log("Updating expense $id for user $userId");
    
    try {
        $stmt = $pdo->prepare("UPDATE expenses SET description = ?, amount = ?, category = ?, date = ? WHERE id = ? AND user_id = ?");
        $result = $stmt->execute([$description, $amount, $category, $date, $id, $userId]);
        
        if ($result && $stmt->rowCount() > 0) {
            error_log("Expense $id updated successfully");
            return true;
        } else {
            error_log("Failed to update expense $id - no rows affected");
            return false;
        }
    } catch (PDOException $e) {
        error_log("Database error updating expense: " . $e->getMessage());
        return false;
    }
}

function getExpenseById($id, $userId) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM expenses WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error getting expense: " . $e->getMessage());
        return false;
    }
}
?>