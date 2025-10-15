<form id="otp-form">
    @csrf
    <div class="alert alert-danger" id="otp-errors" style="display: none;"></div>
    <div class="alert alert-success">An OTP has been sent to your phone.</div>
    
    <div class="mb-3">
        <label for="otp-input-1" class="form-label">Enter 6-Digit OTP</label>
        {{-- The 6 separate input fields --}}
        <div class="d-flex justify-content-between gap-2" id="otp-input-container">
            <input type="number" class="form-control text-center otp-digit-input" id="otp-input-1" maxlength="1">
            <input type="number" class="form-control text-center otp-digit-input" maxlength="1">
            <input type="number" class="form-control text-center otp-digit-input" maxlength="1">
            <input type="number" class="form-control text-center otp-digit-input" maxlength="1">
            <input type="number" class="form-control text-center otp-digit-input" maxlength="1">
            <input type="number" class="form-control text-center otp-digit-input" maxlength="1">
        </div>
        {{-- Hidden input to store the combined OTP for submission --}}
        <input type="hidden" name="otp" id="otp-combined-input">
    </div>

    <div class="d-grid gap-2 mb-3">
        <button type="submit" class="btn btn-dark">
            <span class="button-text">Verify OTP</span>
            <span class="spinner-border spinner-border-sm" style="display: none;"></span>
        </button>
    </div>
    <p class="text-center text-muted">
        Didn't receive the code?
        <a href="#" class="text-decoration-none" id="resend-otp-link">Resend OTP</a>
        <span id="resend-otp-timer" class="text-muted"></span>
    </p>
</form>