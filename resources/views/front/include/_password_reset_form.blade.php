<form id="password-reset-form">
    @csrf
    <div class="alert alert-danger" id="reset-errors" style="display: none;"></div>
    <p class="text-muted mb-3">Enter your email address and we will send you a link to reset your password.</p>
    <div class="mb-3">
        <label for="reset-email" class="form-label">Email Address</label>
        <input type="email" class="form-control" id="reset-email" name="email" placeholder="Enter your registered email">
    </div>
    <div class="d-grid gap-2 mb-3">
        <button type="submit" class="btn btn-dark">
            <span class="button-text">Send Password Reset Link</span>
            <span class="spinner-border spinner-border-sm" style="display: none;"></span>
        </button>
    </div>
    <p class="text-center text-muted">
        <a href="#" class="text-decoration-none" id="back-to-login">Back to Login</a>
    </p>
</form>