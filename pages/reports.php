<?php
if (!$isLoggedIn) {
    header("Location: index.php?page=login");
    exit();
}

$stats = getExpenseStats(getUserId());
?>
<div class="card">
    <div class="card-header">
        <h2 style="margin: 0; color: var(--dark);">
            <i class="fas fa-chart-bar"></i> Expense Reports
        </h2>
    </div>

    <div class="dashboard-grid">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Spending by Category</div>
                <i class="fas fa-chart-pie card-icon" style="color: var(--primary);"></i>
            </div>
            <div class="chart-container">
                <canvas id="expenseChart" width="300" height="300"></canvas>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <div class="card-title">Category Breakdown</div>
                <i class="fas fa-list card-icon" style="color: var(--success);"></i>
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
                    <p style="text-align: center; padding: 20px; color: var(--gray);">No expense data available</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 style="margin: 0; color: var(--dark);">
                <i class="fas fa-calendar"></i> Monthly Summary
            </h3>
        </div>
        <div style="padding: 20px;">
            <p style="color: var(--gray); text-align: center;">Monthly reports and analytics coming soon...</p>
        </div>
    </div>
</div>