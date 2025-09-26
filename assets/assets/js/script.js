document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('expenseModal');
    const addExpenseBtn = document.getElementById('addExpenseBtn');
    const closeBtn = document.querySelector('.close');
    const notifications = document.querySelectorAll('.notification');
    const closeNotificationBtns = document.querySelectorAll('.close-notification');
    
    // Show modal when Add Expense button is clicked
    if (addExpenseBtn) {
        addExpenseBtn.addEventListener('click', function() {
            // Reset form
            document.getElementById('expenseId').value = '';
            document.getElementById('modalDescription').value = '';
            document.getElementById('modalAmount').value = '';
            document.getElementById('modalCategory').value = 'Food';
            document.getElementById('modalDate').value = new Date().toISOString().split('T')[0];
            
            modal.style.display = 'flex';
        });
    }
    
    // Close modal when X is clicked
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    }
    
    // Close modal when clicking outside the modal content
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
    
    // Close notifications
    closeNotificationBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            this.parentElement.style.display = 'none';
        });
    });
    
    // Auto-hide notifications after 5 seconds
    notifications.forEach(notification => {
        if (notification.classList.contains('show')) {
            setTimeout(() => {
                notification.style.display = 'none';
            }, 5000);
        }
    });
    
    // Edit expense buttons
    const editButtons = document.querySelectorAll('.action-btn.edit');
    editButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const expenseId = this.dataset.id;
            const description = this.dataset.description;
            const amount = this.dataset.amount;
            const category = this.dataset.category;
            const date = this.dataset.date;
            
            // Fill the form with existing data
            document.getElementById('expenseId').value = expenseId;
            document.getElementById('modalDescription').value = description;
            document.getElementById('modalAmount').value = amount;
            document.getElementById('modalCategory').value = category;
            document.getElementById('modalDate').value = date;
            
            // Show the modal
            modal.style.display = 'flex';
        });
    });
    
    // Confirm delete action
    const deleteButtons = document.querySelectorAll('.action-btn.delete');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this expense?')) {
                window.location.href = this.dataset.href;
            }
        });
    });
    
    // Simple chart for reports page
    const chartCanvas = document.getElementById('expenseChart');
    if (chartCanvas) {
        const ctx = chartCanvas.getContext('2d');
        
        // Sample data - in a real app, this would come from the server
        const data = {
            'Food': 845,
            'Transportation': 320,
            'Utilities': 240,
            'Entertainment': 180,
            'Shopping': 450,
            'Health': 150,
            'Other': 213
        };
        
        const colors = [
            '#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0', 
            '#9966ff', '#ff9f40', '#c9cbcf'
        ];
        
        let startAngle = 0;
        for (const [category, amount] of Object.entries(data)) {
            const sliceAngle = (amount / 2400) * 2 * Math.PI;
            
            ctx.beginPath();
            ctx.moveTo(150, 150);
            ctx.arc(150, 150, 120, startAngle, startAngle + sliceAngle);
            ctx.closePath();
            
            const color = colors[Object.keys(data).indexOf(category) % colors.length];
            ctx.fillStyle = color;
            ctx.fill();
            
            startAngle += sliceAngle;
        }
    }
});