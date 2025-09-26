<?php
$expenses = getExpenses(getUserId());
?>
<div class="card-header">
    <h2>Manage Expenses</h2>
    <button class="btn btn-primary" id="addExpenseBtn">
        <i class="fas fa-plus"></i> Add Expense
    </button>
</div>

<div class="expense-list">
    <div class="table-header">
        <div>Description</div>
        <div>Amount</div>
        <div>Category</div>
        <div>Date</div>
        <div>Actions</div>
    </div>
    
    <?php if (!empty($expenses)): ?>
        <?php foreach ($expenses as $expense): ?>
            <div class="expense-item">
                <div><?php echo htmlspecialchars($expense['description']); ?></div>
                <div>$<?php echo number_format($expense['amount'], 2); ?></div>
                <div><span class="category-badge category-<?php echo htmlspecialchars($expense['category']); ?>"><?php echo htmlspecialchars($expense['category']); ?></span></div>
                <div><?php echo date('M j, Y', strtotime($expense['date'])); ?></div>
                <div class="expense-actions">
                    <button class="action-btn edit" data-id="<?php echo $expense['id']; ?>" data-description="<?php echo htmlspecialchars($expense['description']); ?>" data-amount="<?php echo $expense['amount']; ?>" data-category="<?php echo htmlspecialchars($expense['category']); ?>" data-date="<?php echo $expense['date']; ?>">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn delete" data-href="index.php?page=expenses&delete_expense=<?php echo $expense['id']; ?>">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="expense-item">
            <div colspan="5" style="text-align: center; padding: 20px;">No expenses found. <a href="#" id="addFirstExpense">Add your first expense</a></div>
        </div>
    <?php endif; ?>
</div>

<div class="csv-section">
    <div class="form-section">
        <h3 class="form-title">Import Expenses</h3>
        <form method="POST" action="index.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="csvFile">Select CSV File</label>
                <input type="file" id="csvFile" name="csv_file" class="form-control" accept=".csv" required>
                <small>CSV format: Description,Amount,Category,Date</small>
            </div>
            <button type="submit" name="import" class="btn btn-success">
                <i class="fas fa-file-import"></i> Import CSV
            </button>
        </form>
    </div>
    
    <div class="form-section">
        <h3 class="form-title">Export Expenses</h3>
        <div class="form-group">
            <label for="dateRange">Export Format</label>
            <select id="dateRange" class="form-control">
                <option>All Expenses</option>
                <option>Last 7 days</option>
                <option>Last 30 days</option>
                <option>Last 3 months</option>
            </select>
        </div>
        <a href="index.php?export=1" class="btn btn-primary">
            <i class="fas fa-file-export"></i> Export CSV
        </a>
    </div>
</div>

<script>
document.getElementById('addFirstExpense')?.addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('addExpenseBtn').click();
});
</script>