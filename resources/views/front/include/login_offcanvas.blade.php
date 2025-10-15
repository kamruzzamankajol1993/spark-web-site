<div class="offcanvas offcanvas-end offcanvas-login" tabindex="-1" id="offcanvasLogin" aria-labelledby="offcanvasLoginLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasLoginLabel">Login</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-4" id="auth-offcanvas-body">
        {{-- The correct form partial will be loaded here by JavaScript --}}
        @include('front.include._login_form')
    </div>
</div>