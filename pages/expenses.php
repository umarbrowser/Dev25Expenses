<?php
if (!$isLoggedIn) {
    header("Location: index.php?page=login");
    exit();
}

$expenses = getExpenses(getUserId());
$totalExpenses = array_sum(array_column($expenses, 'amount'));
?>
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="margin: 0; color: var(--dark);">
            <i class="fas fa-receipt"></i> Manage Expenses
        </h2>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <span style="font-weight: 600; color: var(--primary);">
                Total: $<?php echo number_format($totalExpenses, 2); ?>
            </span>
            <button class="btn btn-primary" id="addExpenseBtn">
                <i class="fas fa-plus"></i> Add Expense
            </button>
        </div>
    </div>
    
    <?php if (!empty($expenses)): ?>
    <div class="expense-list">
        <div class="table-header">
            <div>Description</div>
            <div>Amount</div>
            <div>Category</div>
            <div>Date</div>
            <div>Actions</div>
        </div>
        
        <?php foreach ($expenses as $expense): ?>
            <div class="expense-item">
                <div>
                    <i class="fas fa-receipt" style="color: var(--primary-light); margin-right: 10px;"></i>
                    <?php echo htmlspecialchars($expense['description']); ?>
                </div>
                <div style="font-weight: 600; color: var(--danger);">
                    $<?php echo number_format($expense['amount'], 2); ?>
                </div>
                <div>
                    <span class="category-badge category-<?php echo htmlspecialchars($expense['category']); ?>">
                        <?php echo htmlspecialchars($expense['category']); ?>
                    </span>
                </div>
                <div style="color: var(--gray);">
                    <?php echo date('M j, Y', strtotime($expense['date'])); ?>
                </div>
                <div class="expense-actions">
                    <button class="action-btn edit" 
                            data-id="<?php echo $expense['id']; ?>"
                            data-description="<?php echo htmlspecialchars($expense['description']); ?>"
                            data-amount="<?php echo $expense['amount']; ?>"
                            data-category="<?php echo htmlspecialchars($expense['category']); ?>"
                            data-date="<?php echo $expense['date']; ?>">
                        <i class="fas fa-edit" title="Edit"></i>
                    </button>
                    <button class="action-btn delete" 
                            data-id="<?php echo $expense['id']; ?>">
                        <i class="fas fa-trash" title="Delete"></i>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div style="text-align: center; padding: 3rem;">
        <i class="fas fa-receipt" style="font-size: 4rem; color: var(--light-gray); margin-bottom: 1rem;"></i>
        <h3 style="color: var(--gray); margin-bottom: 1rem;">No expenses yet</h3>
        <p style="color: var(--gray); margin-bottom: 2rem;">Start tracking your expenses to see them here</p>
        <button class="btn btn-primary" id="addFirstExpense">
            <i class="fas fa-plus"></i> Add Your First Expense
        </button>
    </div>
    <?php endif; ?>
</div>