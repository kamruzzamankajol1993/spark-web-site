<form id="login-form">
    @csrf
    <div class="alert alert-danger" id="login-errors" style="display: none;"></div>
    <div class="mb-3">
        <label for="login-email" class="form-label">Email or Phone</label>
        <input type="text" class="form-control" name="email" id="login-email" placeholder="Enter your email or phone">
    </div>
    <div class="mb-3">
        <label for="login-password" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" id="login-password" placeholder="Enter your password">
    </div>
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="remember-me" name="remember">
            <label class="form-check-label" for="remember-me">Remember me</label>
        </div>
        <a href="#" class="text-decoration-none" id="show-forgot-password-form">Forgot Password?</a>
    </div>
    <div class="d-grid gap-2 mb-3">
        <button type="submit" class="btn btn-dark">
            <span class="button-text">Login</span>
            <span class="spinner-border spinner-border-sm" style="display: none;"></span>
        </button>
    </div>
    <p class="text-center text-muted">
        Don't have an account? <a href="#" class="text-decoration-none" id="show-register-form">Register Now</a>
    </p>
</form>