<?php
if (isLoggedIn()) {
    $stats = getExpenseStats(getUserId());
}
?>
<h1>Expense Dashboard</h1>
<p class="subtitle">Welcome back! Here's your spending summary</p>

<?php if (isLoggedIn()): ?>
<div class="dashboard">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Total Spent</div>
            <i class="fas fa-wallet fa-2x" style="color: var(--primary);"></i>
        </div>
        <div class="stat">$<?php echo number_format($stats['total'], 2); ?></div>
        <p>Your total spending to date</p>
        <div class="progress-bar">
            <div class="progress" style="width: <?php echo min(100, ($stats['total'] / 3000) * 100); ?>%;"></div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <div class="card-title">Monthly Budget</div>
            <i class="fas fa-chart-line fa-2x" style="color: var(--success);"></i>
        </div>
        <div class="stat">$3,000.00</div>
        <p>$<?php echo number_format(3000 - $stats['total'], 2); ?> remaining</p>
        <div class="progress-bar">
            <div class="progress" style="width: <?php echo min(100, ($stats['total'] / 3000) * 100); ?>%; background-color: var(--warning);"></div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <div class="card-title">Top Category</div>
            <i class="fas fa-utensils fa-2x" style="color: var(--danger);"></i>
        </div>
        <?php if (!empty($stats['byCategory'])): 
            $topCategory = $stats['byCategory'][0]; 
        ?>
            <div class="stat"><?php echo htmlspecialchars($topCategory['category']); ?></div>
            <p>$<?php echo number_format($topCategory['total'], 2); ?> spent</p>
        <?php else: ?>
            <div class="stat">No data</div>
            <p>No expenses recorded yet</p>
        <?php endif; ?>
        <div class="progress-bar">
            <div class="progress" style="width: 45%;"></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Recent Expenses</h2>
        <a href="index.php?page=expenses" class="btn btn-primary">View All</a>
    </div>
    
    <div class="expense-list">
        <div class="table-header">
            <div>Description</div>
            <div>Amount</div>
            <div>Category</div>
            <div>Date</div>
            <div>Actions</div>
        </div>
        
        <?php if (!empty($stats['recent'])): ?>
            <?php foreach ($stats['recent'] as $expense): ?>
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
                <div colspan="5" style="text-align: center; padding: 20px;">No expenses found. <a href="index.php?page=expenses">Add your first expense</a></div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php else: ?>
<div class="card">
    <div style="text-align: center; padding: 40px;">
        <h2>Welcome to Dev25Expenses</h2>
        <p>Track your expenses easily and efficiently</p>
        <div style="margin-top: 20px;">
            <a href="index.php?page=login" class="btn btn-primary">Login</a>
            <a href="index.php?page=register" class="btn btn-outline">Register</a>
        </div>
    </div>
</div>
<?php endif; ?>