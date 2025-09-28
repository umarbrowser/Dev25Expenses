<div class="auth-container">
    <div class="auth-form">
        <div style="text-align: center; margin-bottom: 2rem;">
            <i class="fas fa-sign-in-alt" style="font-size: 3rem; color: var(--primary); margin-bottom: 1rem;"></i>
            <h2 class="form-title">Welcome Back</h2>
            <p style="color: var(--gray);">Sign in to your account</p>
        </div>
        
        <form method="POST" action="index.php">
            <div class="form-group">
                <label for="username">
                    <i class="fas fa-user"></i> Username
                </label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required value="admin">
            </div>
            
            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i> Password
                </label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required value="admin123">
            </div>
            
            <button type="submit" name="login" class="btn btn-primary" style="width: 100%; padding: 1rem;">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>
        
        <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--light-gray);">
            <p style="color: var(--gray);">Don't have an account?</p>
            <a href="index.php?page=register" class="btn btn-outline" style="width: 100%;">
                <i class="fas fa-user-plus"></i> Create New Account
            </a>
        </div>
        
        <div style="text-align: center; margin-top: 2rem;">
            <small style="color: var(--gray);">
                <strong>Demo Credentials pre-filled:</strong><br>
                Username: admin<br>
                Password: admin123
            </small>
        </div>
    </div>
</div>