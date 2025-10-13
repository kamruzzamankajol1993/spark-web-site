<div class="offcanvas offcanvas-end offcanvas-login" tabindex="-1" id="offcanvasLogin"
    aria-labelledby="offcanvasLoginLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasLoginLabel">Login</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-4">
        <form class="login-form">
            <div class="mb-3">
                <label for="loginEmail" class="form-label">Email address</label>
                <input type="email" class="form-control" id="loginEmail" aria-describedby="emailHelp"
                    placeholder="Enter your email">
            </div>
            <div class="mb-3">
                <label for="loginPassword" class="form-label">Password</label>
                <input type="password" class="form-control" id="loginPassword" placeholder="Enter your password">
            </div>
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                </div>
                <a href="#" class="text-decoration-none">Forgot Password?</a>
            </div>
            <div class="d-grid gap-2 mb-3">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
            <p class="text-center text-muted">
                Don't have an account? <a href="#" class="text-decoration-none">Register Now</a>
            </p>
        </form>
    </div>
</div>