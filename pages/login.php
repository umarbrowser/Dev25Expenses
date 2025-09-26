<div class="form-section auth-form">
    <h2 class="form-title">Login to Your Account</h2>
    <form method="POST" action="index.php">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        
        <button type="submit" name="login" class="btn btn-primary" style="width: 100%;">Login</button>
    </form>
    
    <p style="text-align: center; margin-top: 20px;">
        Don't have an account? <a href="index.php?page=register">Register here</a>
    </p>
</div>