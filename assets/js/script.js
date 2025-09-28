class ExpenseTracker {
    constructor() {
        this.init();
    }

    init() {
        this.initializeModals();
        this.initializeNotifications();
        this.initializeForms();
        this.initializeCharts();
        this.initializeAnimations();
    }

    initializeModals() {
        const modal = document.getElementById('expenseModal');
        const addExpenseBtn = document.getElementById('addExpenseBtn');
        const closeBtn = document.querySelector('.close');
        const addFirstExpense = document.getElementById('addFirstExpense');

        // Show modal
        if (addExpenseBtn) {
            addExpenseBtn.addEventListener('click', () => this.showExpenseModal());
        }

        if (addFirstExpense) {
            addFirstExpense.addEventListener('click', (e) => {
                e.preventDefault();
                this.showExpenseModal();
            });
        }

        // Close modal
        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.hideExpenseModal());
        }

        // Close on backdrop click
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                this.hideExpenseModal();
            }
        });
    }

    showExpenseModal(expense = null) {
        const modal = document.getElementById('expenseModal');
        const form = modal.querySelector('form');
        
        if (expense) {
            // Edit mode
            document.getElementById('expenseId').value = expense.id;
            document.getElementById('modalDescription').value = expense.description;
            document.getElementById('modalAmount').value = expense.amount;
            document.getElementById('modalCategory').value = expense.category;
            document.getElementById('modalDate').value = expense.date;
            modal.querySelector('.modal-title').textContent = 'Edit Expense';
        } else {
            // Add mode
            form.reset();
            document.getElementById('expenseId').value = '';
            document.getElementById('modalDate').value = new Date().toISOString().split('T')[0];
            modal.querySelector('.modal-title').textContent = 'Add New Expense';
        }
        
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    hideExpenseModal() {
        const modal = document.getElementById('expenseModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    initializeNotifications() {
        const closeButtons = document.querySelectorAll('.close-notification');
        
        closeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.notification').style.display = 'none';
            });
        });

        // Auto-hide notifications after 5 seconds
        const notifications = document.querySelectorAll('.notification.show');
        notifications.forEach(notification => {
            setTimeout(() => {
                notification.style.display = 'none';
            }, 5000);
        });
    }

    initializeForms() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                // Show loading state
                submitBtn.innerHTML = '<span class="loading"></span> Processing...';
                submitBtn.disabled = true;
                
                // Simulate processing for demo
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 1000);
            });
        });

        // Edit expense buttons
        const editButtons = document.querySelectorAll('.action-btn.edit');
        editButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const expense = {
                    id: btn.dataset.id,
                    description: btn.dataset.description,
                    amount: btn.dataset.amount,
                    category: btn.dataset.category,
                    date: btn.dataset.date
                };
                this.showExpenseModal(expense);
            });
        });

        // Delete expense buttons
        const deleteButtons = document.querySelectorAll('.action-btn.delete');
        deleteButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                if (confirm('Are you sure you want to delete this expense? This action cannot be undone.')) {
                    window.location.href = btn.dataset.href;
                }
            });
        });
    }

    initializeCharts() {
        const chartCanvas = document.getElementById('expenseChart');
        if (!chartCanvas) return;

        const ctx = chartCanvas.getContext('2d');
        
        // Sample data - in real app, this would come from API
        const data = {
            'Food': { amount: 845, color: '#ff6384' },
            'Transportation': { amount: 320, color: '#36a2eb' },
            'Utilities': { amount: 240, color: '#ffcd56' },
            'Entertainment': { amount: 180, color: '#4bc0c0' },
            'Shopping': { amount: 450, color: '#9966ff' },
            'Health': { amount: 150, color: '#ff9f40' },
            'Other': { amount: 213, color: '#c9cbcf' }
        };

        const total = Object.values(data).reduce((sum, item) => sum + item.amount, 0);
        let startAngle = 0;

        // Draw pie chart
        Object.entries(data).forEach(([category, item]) => {
            const sliceAngle = (item.amount / total) * 2 * Math.PI;
            
            ctx.beginPath();
            ctx.moveTo(150, 150);
            ctx.arc(150, 150, 120, startAngle, startAngle + sliceAngle);
            ctx.closePath();
            ctx.fillStyle = item.color;
            ctx.fill();
            
            // Draw label
            const angle = startAngle + sliceAngle / 2;
            const x = 150 + Math.cos(angle) * 140;
            const y = 150 + Math.sin(angle) * 140;
            
            ctx.fillStyle = '#333';
            ctx.font = '12px Inter';
            ctx.textAlign = 'center';
            ctx.fillText(category, x, y);
            
            startAngle += sliceAngle;
        });
    }

    initializeAnimations() {
        // Animate progress bars
        const progressBars = document.querySelectorAll('.progress');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0';
            setTimeout(() => {
                bar.style.width = width;
            }, 500);
        });

        // Add hover effects to cards
        const cards = document.querySelectorAll('.card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Animate numbers
        this.animateNumbers();
    }

    animateNumbers() {
        const stats = document.querySelectorAll('.stat');
        stats.forEach(stat => {
            const text = stat.textContent;
            if (text.includes('$')) {
                const number = parseFloat(text.replace('$', '').replace(',', ''));
                this.animateValue(stat, 0, number, 2000);
            }
        });
    }

    animateValue(element, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const value = Math.floor(progress * (end - start) + start);
            element.textContent = '$' + value.toLocaleString();
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }

    showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification show ${type}`;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
            <span>${message}</span>
            <button class="close-notification">&times;</button>
        `;
        
        document.body.appendChild(notification);
        
        // Add close functionality
        notification.querySelector('.close-notification').addEventListener('click', () => {
            notification.remove();
        });
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }
}

// Initialize the application when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.expenseTracker = new ExpenseTracker();
});
document.addEventListener('DOMContentLoaded', function() {
    // Modal functionality
    const modal = document.getElementById('expenseModal');
    const closeBtn = document.querySelector('.close');
    
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    }
    
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
    
    // Close notifications
    const closeNotifications = document.querySelectorAll('.close-notification');
    closeNotifications.forEach(btn => {
        btn.addEventListener('click', function() {
            this.parentElement.style.display = 'none';
        });
    });
    
    // Auto-hide notifications after 5 seconds
    const notifications = document.querySelectorAll('.notification');
    notifications.forEach(notification => {
        setTimeout(() => {
            notification.style.display = 'none';
        }, 5000);
    });
    
    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            submitBtn.disabled = true;
            
            // Re-enable after 2 seconds (in case of error)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
        });
    });
});

// Global function to show expense modal
function showExpenseModal(expense = null) {
    const modal = document.getElementById('expenseModal');
    const form = document.getElementById('expenseForm');
    
    if (expense) {
        // Edit mode
        document.getElementById('expenseId').value = expense.id;
        document.getElementById('modalDescription').value = expense.description;
        document.getElementById('modalAmount').value = expense.amount;
        document.getElementById('modalCategory').value = expense.category;
        document.getElementById('modalDate').value = expense.date;
        document.getElementById('modalTitle').textContent = 'Edit Expense';
        document.getElementById('submitButton').innerHTML = '<i class="fas fa-save"></i> Update Expense';
        document.getElementById('submitButton').name = 'update_expense';
    } else {
        // Add mode
        form.reset();
        document.getElementById('expenseId').value = '';
        document.getElementById('modalDate').value = new Date().toISOString().split('T')[0];
        document.getElementById('modalTitle').textContent = 'Add New Expense';
        document.getElementById('submitButton').innerHTML = '<i class="fas fa-plus"></i> Add Expense';
        document.getElementById('submitButton').name = 'add_expense';
    }
    
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
// Add some interactive effects
document.addEventListener('mousemove', (e) => {
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        card.style.setProperty('--mouse-x', `${x}px`);
        card.style.setProperty('--mouse-y', `${y}px`);
    });
});