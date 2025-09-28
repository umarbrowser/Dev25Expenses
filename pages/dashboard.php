<?php
if ($isLoggedIn) {
    $stats = getExpenseStats(getUserId());
    $recentExpenses = array_slice($stats['recent'] ?? [], 0, 5);
}
?>
<section id="dashboard">
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1 style="font-size: 3rem; font-weight: 800; background: linear-gradient(135deg, var(--primary), var(--secondary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 1rem;">
            Expense Dashboard
        </h1>
        <p style="font-size: 1.2rem; color: var(--gray); max-width: 600px; margin: 0 auto;">
            <?php echo $isLoggedIn ? 'Welcome back! Track your spending and manage your finances like a pro.' : 'Professional expense tracking made simple'; ?>
        </p>
    </div>
    
    <?php if ($isLoggedIn): ?>
    <div class="dashboard-grid">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Total Spent This Month</div>
                <i class="fas fa-wallet card-icon" style="color: var(--primary);"></i>
            </div>
            <div class="stat">$<?php echo number_format($stats['total'] ?? 0, 2); ?></div>
            <p>Your total spending this month</p>
            <div class="progress-bar">
                <div class="progress" style="width: <?php echo min(100, (($stats['total'] ?? 0) / 3000) * 100); ?>%;"></div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <div class="card-title">Monthly Budget</div>
                <i class="fas fa-chart-line card-icon" style="color: var(--success);"></i>
            </div>
            <div class="stat">$3,000.00</div>
            <p>$<?php echo number_format(max(0, 3000 - ($stats['total'] ?? 0)), 2); ?> remaining</p>
            <div class="progress-bar">
                <div class="progress" style="width: <?php echo min(100, (($stats['total'] ?? 0) / 3000) * 100); ?>%; background: var(--warning);"></div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <div class="card-title">Top Category</div>
                <i class="fas fa-tags card-icon" style="color: var(--secondary);"></i>
            </div>
            <?php if (!empty($stats['byCategory'])): 
                $topCategory = $stats['byCategory'][0]; 
            ?>
                <div class="stat"><?php echo htmlspecialchars($topCategory['category']); ?></div>
                <p>$<?php echo number_format($topCategory['total'], 2); ?> spent</p>
            <?php else: ?>
                <div class="stat" style="-webkit-text-fill-color: var(--gray);">No data</div>
                <p>Start adding expenses to see insights</p>
            <?php endif; ?>
            <div class="progress-bar">
                <div class="progress" style="width: 65%; background: var(--secondary);"></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 style="margin: 0; color: var(--dark);">
                <i class="fas fa-clock"></i> Recent Expenses
            </h2>
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
            
            <?php if (!empty($recentExpenses)): ?>
                <?php foreach ($recentExpenses as $expense): ?>
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
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn delete" 
                                    data-id="<?php echo $expense['id']; ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="expense-item" style="text-align: center; padding: 3rem; grid-column: 1 / -1;">
                    <i class="fas fa-receipt" style="font-size: 3rem; color: var(--light-gray); margin-bottom: 1rem;"></i>
                    <h3 style="color: var(--gray); margin-bottom: 1rem;">No expenses yet</h3>
                    <p style="color: var(--gray); margin-bottom: 2rem;">Start tracking your expenses to see them here</p>
                    <button class="btn btn-primary" id="addFirstExpense">
                        <i class="fas fa-plus"></i> Add Your First Expense
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php else: ?>
    <div class="card" style="text-align: center; padding: 4rem 2rem;">
        <i class="fas fa-chart-pie" style="font-size: 4rem; color: var(--primary); margin-bottom: 2rem;"></i>
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem; color: var(--dark);">Welcome to Dev25Expenses</h2>
        <p style="font-size: 1.2rem; color: var(--gray); margin-bottom: 3rem; max-width: 500px; margin-left: auto; margin-right: auto;">
            The professional expense tracking solution that helps you manage your finances with style and efficiency.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="index.php?page=login" class="btn btn-primary" style="padding: 1rem 2rem;">
                <i class="fas fa-sign-in-alt"></i> Login to Your Account
            </a>
            <a href="index.php?page=register" class="btn" style="padding: 1rem 2rem; background: var(--dark); color: white;">
                <i class="fas fa-user-plus"></i> Create New Account
            </a>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-top: 4rem;">
            <div style="text-align: center;">
                <i class="fas fa-rocket" style="font-size: 2rem; color: var(--primary); margin-bottom: 1rem;"></i>
                <h3 style="color: var(--dark);">Fast & Easy</h3>
                <p style="color: var(--gray);">Quick expense entry with intelligent categorization</p>
            </div>
            <div style="text-align: center;">
                <i class="fas fa-chart-bar" style="font-size: 2rem; color: var(--success); margin-bottom: 1rem;"></i>
                <h3 style="color: var(--dark);">Smart Insights</h3>
                <p style="color: var(--gray);">Visual reports and spending patterns analysis</p>
            </div>
            <div style="text-align: center;">
                <i class="fas fa-file-csv" style="font-size: 2rem; color: var(--warning); margin-bottom: 1rem;"></i>
                <h3 style="color: var(--dark);">CSV Import/Export</h3>
                <p style="color: var(--gray);">Seamless data migration and backup</p>
            </div>
        </div>
    </div>
    <?php endif; ?>
</section>