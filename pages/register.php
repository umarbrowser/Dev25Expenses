<div class="auth-container">
    <div class="auth-form">
        <div style="text-align: center; margin-bottom: 2rem;">
            <i class="fas fa-user-plus" style="font-size: 3rem; color: var(--primary); margin-bottom: 1rem;"></i>
            <h2 class="form-title">Create Account</h2>
            <p style="color: var(--gray);">Join Dev25Expenses today</p>
        </div>
        
        <form method="POST" action="index.php">
            <div class="form-group">
                <label for="username">
                    <i class="fas fa-user"></i> Username
                </label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Choose a username" required>
            </div>
            
            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i> Email
                </label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            
            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i> Password
                </label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Create a password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">
                    <i class="fas fa-lock"></i> Confirm Password
                </label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm your password" required>
            </div>
            
            <button type="submit" name="register" class="btn btn-primary" style="width: 100%; padding: 1rem;">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </form>
        
        <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--light-gray);">
            <p style="color: var(--gray);">Already have an account?</p>
            <a href="index.php?page=login" class="btn btn-outline" style="width: 100%;">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </a>
        </div>
    </div>
</div>