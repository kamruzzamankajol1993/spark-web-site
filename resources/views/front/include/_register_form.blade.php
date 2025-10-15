<form id="register-form">
    @csrf
    <div class="alert alert-danger" id="register-errors" style="display: none;"></div>
    <div class="mb-3">
        <label for="register-name" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="register-name" name="name" placeholder="Enter your full name">
    </div>
    {{-- ADDED: Email Field --}}
    <div class="mb-3">
        <label for="register-email" class="form-label">Email Address</label>
        <input type="email" class="form-control" id="register-email" name="email" placeholder="Enter your email address">
    </div>

    {{-- Phone Field --}}
    <div class="mb-3">
        <label for="register-phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text" style="font-size: 1.2rem;">🇧🇩 +880</span>
            <input type="number" class="form-control" id="register-phone" name="phone" placeholder="1712345678">
        
        </div>
    </div>
    <div class="mb-3">
        <label for="register-password" class="form-label">Password</label>
        <input type="password" class="form-control" id="register-password" name="password" placeholder="Minimum 8 characters">
    <div id="passwordHelpBlock" class="form-text text-muted" style="font-size: 0.8rem;">
            Password must be at least 8 characters long.
        </div>
    </div>
    <div class="mb-3">
        <label for="register-password-confirmation" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" id="register-password-confirmation" name="password_confirmation" placeholder="Retype your password">
    </div>
    <div class="d-grid gap-2 mb-3">
        <button type="submit" class="btn btn-dark">
            <span class="button-text">Register</span>
            <span class="spinner-border spinner-border-sm" style="display: none;"></span>
        </button>
    </div>
    <p class="text-center text-muted">
        Already have an account? <a href="#" class="text-decoration-none" id="show-login-form">Login Now</a>
    </p>
</form>