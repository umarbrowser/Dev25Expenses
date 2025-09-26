<?php
$stats = getExpenseStats(getUserId());
?>
<h2>Expense Reports</h2>
<p>Visualize your spending patterns</p>

<div class="dashboard">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Spending by Category</div>
            <i class="fas fa-chart-pie fa-2x" style="color: var(--primary);"></i>
        </div>
        <div class="chart-container">
            <canvas id="expenseChart" width="300" height="300"></canvas>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <div class="card-title">Category Breakdown</div>
            <i class="fas fa-list fa-2x" style="color: var(--success);"></i>
        </div>
        <div style="margin-top: 20px;">
            <?php if (!empty($stats['byCategory'])): ?>
                <?php foreach ($stats['byCategory'] as $category): ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #eee;">
                        <span><?php echo htmlspecialchars($category['category']); ?></span>
                        <strong>$<?php echo number_format($category['total'], 2); ?></strong>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; padding: 20px;">No expense data available</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Monthly Summary</h3>
    </div>
    <div style="padding: 20px;">
        <p>Monthly reports and analytics coming soon...</p>
    </div>
</div>