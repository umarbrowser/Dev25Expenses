        </div> <!-- Close main-content -->

        <div class="modal" id="expenseModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Add New Expense</h3>
                    <span class="close">&times;</span>
                </div>
                <form method="POST" action="index.php">
                    <input type="hidden" name="expense_id" id="expenseId">
                    <div class="form-group">
                        <label for="modalDescription">Description</label>
                        <input type="text" id="modalDescription" name="description" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="modalAmount">Amount</label>
                        <input type="number" id="modalAmount" name="amount" class="form-control" step="0.01" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="modalCategory">Category</label>
                        <select id="modalCategory" name="category" class="form-control" required>
                            <option value="Food">Food & Dining</option>
                            <option value="Transportation">Transportation</option>
                            <option value="Utilities">Utilities</option>
                            <option value="Entertainment">Entertainment</option>
                            <option value="Shopping">Shopping</option>
                            <option value="Health">Health</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="modalDate">Date</label>
                        <input type="date" id="modalDate" name="date" class="form-control" required>
                    </div>
                    
                    <button type="submit" name="save_expense" class="btn btn-primary">Save Expense</button>
                </form>
            </div>
        </div>

        <footer>
            <div class="container">
                <p>Dev25Expenses &copy; 2023. Developed by Team B: Saifullah & Umar</p>
                <p>An expense tracking solution for Dev25 program</p>
            </div>
        </footer>

        <script src="assets/js/script.js"></script>
    </body>
</html>