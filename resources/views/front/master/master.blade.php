<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>@yield('title')</title>
    <meta name="description" content="{{$front_ins_name}}">
    <meta name="keywords" content="{{$front_ins_name}}">
    <meta name="author" content="{{$front_ins_name}}">
    <link rel="canonical" href="{{url()->current()}}">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{url()->current()}}">
    <meta property="og:title" content="{{$front_ins_name}}">
    <meta property="og:description" content="{{$front_ins_name}}">
    <meta property="og:image" content="{{$front_ins_url}}{{$front_icon_name}}">

<!-- Favicon -->
    <link rel="shortcut icon" href="{{$front_ins_url}}{{ $front_icon_name }}">
    <!-- Vendor CSS Files -->
    <link href="{{asset('/')}}public/front/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('/')}}public/front/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="{{asset('/')}}public/front/assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="{{asset('/')}}public/front/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/')}}public/front/assets/vendor/slick_slider/slick-theme.css">
    <link rel="stylesheet" href="{{asset('/')}}public/front/assets/vendor/slick_slider/slick.css">

    <!-- Main CSS File -->
    <link href="{{asset('/')}}public/front/assets/css/main.css?v={{ filemtime(public_path('front/assets/css/main.css')) }}" rel="stylesheet">
    @yield('css')
    <script src="{{asset('/')}}public/front/assets/js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    @include('front.include.header')

    @yield('body')

    @include('front.include.footer')

    <!-- Vendor JS Files -->
    
    <script src="{{asset('/')}}public/front/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
   
    <script src="{{asset('/')}}public/front/assets/vendor/aos/aos.js"></script>
    <script src="{{asset('/')}}public/front/assets/vendor/slick_slider/slick.js"></script>

    <!-- Main JS File -->
    <script src="{{asset('/')}}public/front/assets/js/main.js"></script>
    @yield('script')

    {{-- =================================================================== --}}
    {{-- ALL GLOBAL JAVASCRIPT IS NOW CONSOLIDATED INTO THIS SINGLE SCRIPT BLOCK --}}
    {{-- =================================================================== --}}
    <script>
    $(document).ready(function() {

        // --- 1. GLOBAL SETUP & HELPER FUNCTIONS ---
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        function updateHeaderCounts(counts) {
            if (counts.wishlist_count !== undefined) {
                $('#wishlist-count-desktop, #wishlist-count-mobile').text(counts.wishlist_count);
            }
            if (counts.compare_count !== undefined) {
                $('#compare-count-desktop, #compare-count-mobile').text(counts.compare_count);
            }
        }

        function updateCartView(data, successMessage = null) {
            // 1. Always update the cart sidebar (offcanvas)
            $('.cart-items-container').html(data.sidebar_html);
            $('#offcanvas-cart-count').text(data.totalItems);
            $('#sidebar-cart-subtotal').text(data.subtotal);

            // 2. Always update the header's cart count badge
            $('#cart-item-count-desktop, #cart-item-count-mobile').text(data.totalItems);
            
            // Only hide the cart badge if the cart is empty
            // if (data.totalItems > 0) { 
            //     $('#cart-item-count-desktop, #cart-item-count-mobile').show(); 
            // } else { 
            //     $('#cart-item-count-desktop, #cart-item-count-mobile').hide();
            // }

            // 3. CHECK if we are on the main cart page, and update its unique elements.
            if ($('#main-cart-body').length > 0) {
                $('#main-cart-body').html(data.main_cart_html);
                $('#main-cart-subtotal').text(data.subtotal);
                $('#main-cart-discount').text(data.discount);
                $('#main-cart-total').text(data.total);

                // Update coupon display on the main cart page
                if (data.coupon && parseFloat(data.discount) > 0) {
                    $('#coupon-code-text').text(data.coupon.code);
                    $('#applied-coupon-code').text(data.coupon.code);
                    $('#discount-row').show();
                    $('#coupon-input-area').hide();
                    $('#applied-coupon-area').show();
                } else {
                    $('#discount-row').hide();
                    $('#coupon-input-area').show();
                    $('#applied-coupon-area').hide();
                    $('#coupon-input').val('');
                }
            }

            // 4. THIS IS THE KEY FIX: Always update the Wishlist and Compare counts
            // using the fresh data received from the server. This prevents them from vanishing.
            if (data.wishlist_count !== undefined) {
                $('#wishlist-count-desktop, #wishlist-count-mobile').text(data.wishlist_count);
            }
            if (data.compare_count !== undefined) {
                $('#compare-count-desktop, #compare-count-mobile').text(data.compare_count);
            }

            // 5. Show a success notification if provided
            if (successMessage) {
                Toast.fire({ icon: 'success', title: successMessage });
            }
        }

        const authBody = $('#auth-offcanvas-body');
        const authTitle = $('#offcanvasLoginLabel');

        function showAuthLoader(form) {
            const button = form.find('button[type="submit"]');
            button.prop('disabled', true);
            button.find('.button-text').hide();
            button.find('.spinner-border').show();
        }

        function hideAuthLoader(form) {
            const button = form.find('button[type="submit"]');
            button.prop('disabled', false);
            button.find('.spinner-border').hide();
            button.find('.button-text').show();
        }
        
        function displayAuthErrors(formId, errors) {
            const errorDiv = $(`#${formId}-errors`);
            errorDiv.html('').hide();
            let errorHtml = '<ul>';
            if (typeof errors === 'object') {
                $.each(errors, function(key, value) { errorHtml += '<li>' + value[0] + '</li>'; });
            } else {
                errorHtml += '<li>' + errors + '</li>';
            }
            errorHtml += '</ul>';
            errorDiv.html(errorHtml).slideDown();
        }
        
        // --- 2. INITIALIZATION ON PAGE LOAD ---
        function loadInitialData() {
            $.ajax({
                url: '{{ route("cart.contents") }}',
                type: 'GET',
                success: function(response) { updateCartView(response); }
            });
            $.ajax({
                url: '{{ route("header.counts") }}',
                type: 'GET',
                success: function(response) { updateHeaderCounts(response); }
            });
        }

        // --- 3. EVENT HANDLERS ---

        // ### CART & CHECKOUT ###
        $(document).on('submit', '.add-to-cart-form', function(e) {
            e.preventDefault();
            const form = $(this);
            const button = form.find('button[type="submit"]');
            $.ajax({
                url: '{{ route("cart.add") }}',
                type: 'POST',
                data: form.serialize(),
                beforeSend: function() {
                    button.prop('disabled', true).find('.spinner-border').show();
                    button.find('.button-text').hide();
                },
                success: function(response) {
                    if (response.success) {
                        updateCartView(response.cartData, response.message);
                        new bootstrap.Offcanvas(document.getElementById('offcanvasCart')).show();
                    }
                },
                complete: function() {
                    button.prop('disabled', false).find('.spinner-border').hide();
                    button.find('.button-text').show();
                }
            });
        });

        $(document).on('click', '#buy-now-btn, .buy-now-btn', function(e) {
            e.preventDefault();
        e.stopPropagation();
            const button = $(this);
            const form = button.closest('form');
            $.ajax({
                url: '{{ route("cart.add") }}',
                type: 'POST',
                data: form.serialize(),
                beforeSend: function() {
                    button.prop('disabled', true).find('.spinner-border').show();
                    button.find('.button-text').hide();
                },
                success: function(response) {
                    if (response.success) {
                        window.location.href = '{{ route("cart.show") }}';
                    }
                },
                error: function() {
                    button.prop('disabled', false).find('.spinner-border').hide();
                    button.find('.button-text').show();
                }
            });
        });

        $(document).on('click', '.cart-item-remove', function() {
            const productId = $(this).data('product-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "This item will be removed from your cart.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("cart.remove") }}',
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}', product_id: productId },
                        success: function(response) {
                            if(response.success) {
                                updateCartView(response.cartData, response.message);
                            }
                        }
                    });
                }
            });
        });

        let updateTimeout;
        $(document).on('click', '.cart-quantity-btn', function() {
            clearTimeout(updateTimeout);
            const productId = $(this).data('product-id');
            const change = parseInt($(this).data('change'));
            const quantityInput = $(`.cart-quantity-input[data-product-id="${productId}"]`);
            let newQuantity = parseInt(quantityInput.val()) + change;
            if (newQuantity < 1) return;
            quantityInput.val(newQuantity);
            updateTimeout = setTimeout(() => {
                $.ajax({
                    url: '{{ route("cart.update") }}',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}', product_id: productId, quantity: newQuantity },
                    success: function(response) {
                        if(response.success) {
                            updateCartView(response.cartData, response.message);
                        }
                    }
                });
            }, 500);
        });

        $(document).on('click', '#apply-coupon-btn', function() {
            const couponCode = $('#coupon-input').val();
            if (!couponCode) {
                Swal.fire({ icon: 'error', text: 'Please enter a coupon code.' });
                return;
            }
            $.ajax({
                url: '{{ route("cart.applyCoupon") }}',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', code: couponCode },
                success: function(response) {
                    if (response.success) {
                        updateCartView(response.cartData, response.message);
                    }
                },
                error: function(xhr) {
                    Swal.fire({ icon: 'error', text: xhr.responseJSON.message || 'An error occurred.' });
                }
            });
        });

        $(document).on('click', '#remove-coupon-btn', function() {
            Swal.fire({
                title: 'Remove Coupon?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("cart.removeCoupon") }}',
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                updateCartView(response.cartData, response.message);
                            }
                        }
                    });
                }
            });
        });

        // ### WISHLIST & COMPARE ###
      // (UPDATED) Wishlist Add/Remove Logic with Confirmation and Loader
    $(document).on('click', '.wishlist-btn', function(e) {
        e.preventDefault();
        e.stopPropagation(); 
        
         // 1. Check if the user is a guest (not logged in)
        @guest
            // Show the login sidebar
            var loginOffcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasLogin'));
            loginOffcanvas.show();

            // Show an informational popup notification
            Toast.fire({
                icon: 'info',
                title: 'Please log in to use the wishlist.'
            });
            
            // Stop the function from proceeding further
            return; 
        @endguest

        const button = $(this);
        const productId = button.data('product-id');
        const icon = button.find('i');
        const isOnWishlistPage = window.location.pathname.includes('/wishlist');

        // 2. Determine if the action is to add or remove
        let isAdding = isOnWishlistPage ? false : !icon.hasClass('fas');
        let url = isAdding ? '{{ route("wishlist.add") }}' : '{{ route("wishlist.remove") }}';

        // 3. Define the AJAX action
        function performAction() {
            $.ajax({
                url: url,
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', product_id: productId },
                beforeSend: function() {
                    button.prop('disabled', true);
                    button.find('.button-text').hide();
                    button.find('.spinner-border').show();
                },
                success: function(response) {
                    if (response.success) {
                        Toast.fire({ icon: 'success', title: response.message });
                        updateHeaderCounts({ wishlist_count: response.wishlist_count });
                        
                        // Update UI based on the current page
                        if (isOnWishlistPage) {
                            button.closest('.wishlist-item-card').fadeOut(500, function() { 
                                $(this).remove();
                                if ($('.wishlist-item-card').length === 0) { 
                                    location.reload(); 
                                }
                            });
                        } else {
                            icon.toggleClass('far fas'); // Toggle heart icon on product cards
                        }
                    } else { 
                        // Handles "already in wishlist" case
                        Toast.fire({ icon: 'info', title: response.message }); 
                    }
                },
                error: function() {
                    Toast.fire({ icon: 'error', title: 'Something went wrong.' });
                },
                complete: function() {
                    // Always re-enable the button and hide the spinner
                    button.prop('disabled', false);
                    button.find('.button-text').show();
                    button.find('.spinner-border').hide();
                }
            });
        }

        // 4. If on the wishlist page, show confirmation before removing
        if (isOnWishlistPage) {
            Swal.fire({
                title: 'Remove from Wishlist?',
                text: "Are you sure you want to remove this item?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    performAction();
                }
            });
        } else {
            // On all other pages, perform the action immediately
            performAction();
        }
    });

         $(document).on('click', '.move-to-cart-btn', function(e) {
        e.preventDefault();
        
        const button = $(this);
        const productId = button.data('product-id');

        Swal.fire({
            title: 'Move to Cart?',
            text: "This item will be moved from your wishlist to your shopping cart.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Yes, move it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("wishlist.moveToCart") }}',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}', product_id: productId },
                    beforeSend: function() {
                        button.prop('disabled', true);
                        button.find('.button-text').hide();
                        button.find('.spinner-border').show();
                    },
                    success: function(response) {
                        if(response.success) {
                            Toast.fire({ icon: 'success', title: response.message });
                            updateHeaderCounts({ wishlist_count: response.wishlist_count });
                            $('#cart-item-count-desktop, #cart-item-count-mobile').text(response.cart_count);
                            button.closest('.wishlist-item-card').fadeOut(500, function() {
                                $(this).remove();
                                if ($('.wishlist-item-card').length === 0) { location.reload(); }
                            });
                        } else {
                            Toast.fire({ icon: 'error', title: response.message });
                        }
                    },
                    error: function() {
                        Toast.fire({ icon: 'error', title: 'Something went wrong.' });
                        button.prop('disabled', false);
                        button.find('.button-text').show();
                        button.find('.spinner-border').hide();
                    }
                });
            }
        });
    });

        // Compare Add/Remove Logic
       // (UPDATED) Compare Add/Remove Logic with Loader and Count
    $(document).on('click', '.compare-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const button = $(this);
        const productId = button.data('product-id');
        let url = window.location.pathname.includes('/compare') 
            ? '{{ route("compare.remove") }}' 
            : '{{ route("compare.add") }}';

        $.ajax({
            url: url,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', product_id: productId },
            beforeSend: function() {
                // Disable button and show spinner
                button.prop('disabled', true);
                button.find('.button-text').hide();
                button.find('.spinner-border').show();
            },
            success: function(response) {
                if (response.success) {
                    Toast.fire({ icon: 'success', title: response.message });
                    
                    // --- THIS IS THE FIX ---
                    // Call the helper function to update the header count
                    updateHeaderCounts({ compare_count: response.compare_count });

                    // If on compare page, reload to show the updated table
                    if (window.location.pathname.includes('/compare')) {
                        location.reload();
                    }
                } else {
                    Toast.fire({ icon: 'info', title: response.message });
                }
            },
            error: function(xhr) {
                const error = xhr.responseJSON.message || 'An error occurred.';
                Toast.fire({ icon: 'error', title: error });
            },
            complete: function() {
                // Re-enable button and hide spinner
                button.prop('disabled', false);
                button.find('.spinner-border').hide();
                button.find('.button-text').show();
            }
        });
    });

        // Clear All Compare Items
        $(document).on('click', '#clear-compare-list', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will remove all products from your compare list.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, clear it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("compare.clear") }}',
                        type: 'GET',
                        success: function(response) {
                            if (response.success) {
                                Toast.fire({ icon: 'success', title: response.message });
                                location.reload();
                            }
                        },
                        error: function() {
                             Toast.fire({ icon: 'error', title: 'Could not clear the list.' });
                        }
                    });
                }
            });
        });

        // ### AUTHENTICATION ###
        $(document).on('click', '#show-register-form, #show-login-form, #back-to-login, #show-forgot-password-form', function(e) {
            e.preventDefault();
            const loaderHtml = '<div class="d-flex justify-content-center p-5"><div class="spinner-border" role="status"></div></div>';
            authBody.html(loaderHtml);
            let targetUrl = '', targetTitle = '', linkId = $(this).attr('id');
            if (linkId === 'show-register-form') {
                targetTitle = 'Register'; targetUrl = '{{ url("auth-partials/_register_form") }}';
            } else if (linkId === 'show-login-form' || linkId === 'back-to-login') {
                targetTitle = 'Login'; targetUrl = '{{ url("auth-partials/_login_form") }}';
            } else if (linkId === 'show-forgot-password-form') {
                targetTitle = 'Reset Password'; targetUrl = '{{ url("auth-partials/_password_reset_form") }}';
            }
            authTitle.text(targetTitle);
            authBody.load(targetUrl);
        });
        
        /// ### AUTHENTICATION ###
       

        // Helper functions for auth forms
        function showAuthLoader(form) {
            const button = form.find('button[type="submit"]');
            button.prop('disabled', true);
            button.find('.button-text').hide();
            button.find('.spinner-border').show();
        }

        function hideAuthLoader(form) {
            const button = form.find('button[type="submit"]');
            button.prop('disabled', false);
            button.find('.spinner-border').hide();
            button.find('.button-text').show();
        }
        
        function displayAuthErrors(formId, errors) {
            const errorDiv = $(`#${formId}-errors`);
            errorDiv.html('').hide();
            let errorHtml = '<ul>';
            if (typeof errors === 'object') {
                $.each(errors, function(key, value) { errorHtml += '<li>' + value[0] + '</li>'; });
            } else {
                errorHtml += '<li>' + errors + '</li>';
            }
            errorHtml += '</ul>';
            errorDiv.html(errorHtml).slideDown();
        }
        
        // --- Real-time Auth Validations ---
        $(document).on('keyup', '#register-password, #register-password-confirmation', function() {
            const passwordField = $('#register-password');
            const confirmPasswordField = $('#register-password-confirmation');
            const password = passwordField.val();
            const confirmPassword = confirmPasswordField.val();

            if (password.length > 0 && confirmPassword.length > 0) {
                if (password === confirmPassword) {
                    passwordField.css('border-color', '#198754'); // Green
                    confirmPasswordField.css('border-color', '#198754');
                } else {
                    passwordField.css('border-color', '#dc3545'); // Red
                    confirmPasswordField.css('border-color', '#dc3545');
                }
            } else {
                passwordField.css('border-color', ''); // Reset
                confirmPasswordField.css('border-color', '');
            }
        });

        $(document).on('input', '#register-phone', function() {
            let value = $(this).val().replace(/[^0-9]/g, '');
            if (value.startsWith('0')) {
                value = value.substring(1);
            }
            if (value.length > 10) {
                value = value.slice(0, 10);
            }
            $(this).val(value);
        });

        $(document).on('input', '.otp-digit-input', function() {
            if (this.value.length > 1) {
                this.value = this.value.slice(0, 1);
            }
            if (this.value.length === 1) {
                $(this).next('.otp-digit-input').focus();
            }
        });

        $(document).on('keydown', '.otp-digit-input', function(e) {
            if (e.key === 'Backspace' && this.value.length === 0) {
                $(this).prev('.otp-digit-input').focus();
            }
        });

        $(document).on('paste', '.otp-digit-input', function(e) {
            e.preventDefault();
            const pastedData = (e.originalEvent || e).clipboardData.getData('text/plain').slice(0, 6);
            const inputs = $('.otp-digit-input');
            for (let i = 0; i < pastedData.length; i++) {
                $(inputs[i]).val(pastedData[i]);
            }
            $(inputs[Math.min(pastedData.length, 5)]).focus();
        });

        // --- Auth Form Submissions ---
        $(document).on('submit', '#login-form', function(e) {
            e.preventDefault();
            const form = $(this);
            $.ajax({
                url: '{{ route("customer.login") }}',
                type: 'POST',
                data: form.serialize(),
                beforeSend: () => showAuthLoader(form),
                success: function(response) {
                    if (response.success) {
                        window.location.href = response.redirect_url;
                    }
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON.errors || xhr.responseJSON.message;
                    displayAuthErrors('login', errors);
                },
                complete: () => hideAuthLoader(form)
            });
        });

        $(document).on('submit', '#register-form', function(e) {
            e.preventDefault();
            const form = $(this);
            $.ajax({
                url: '{{ route("customer.register") }}',
                type: 'POST',
                data: form.serialize(),
                beforeSend: () => showAuthLoader(form),
                success: function(response) {
                    if (response.success) {
                        authTitle.text('Verify OTP');
                        authBody.load('{{ url("auth-partials/_otp_form") }}');
                    }
                },
                error: function(xhr) {
                    displayAuthErrors('register', xhr.responseJSON.errors);
                },
                complete: () => hideAuthLoader(form)
            });
        });

        $(document).on('submit', '#otp-form', function(e) {
            e.preventDefault();
            let otp = '';
            $('.otp-digit-input').each(function() { otp += $(this).val(); });
            $('#otp-combined-input').val(otp);
            const form = $(this);
            $.ajax({
                url: '{{ route("customer.verifyOtp") }}',
                type: 'POST',
                data: form.serialize(),
                beforeSend: () => showAuthLoader(form),
                success: function(response) {
                    if (response.success) {
                        window.location.href = response.redirect_url;
                    }
                },
                error: function(xhr) {
                    displayAuthErrors('otp', xhr.responseJSON.message);
                },
                complete: () => hideAuthLoader(form)
            });
        });

        $(document).on('submit', '#password-reset-form', function(e) {
            e.preventDefault();
            const form = $(this);
            $.ajax({
                url: '{{ route("password.email") }}',
                type: 'POST',
                data: form.serialize(),
                beforeSend: () => showAuthLoader(form),
                success: function(response) {
                    if (response.success) {
                        form.html(`<div class="alert alert-success">${response.message}</div><p class="text-center"><a href="#" class="text-decoration-none" id="back-to-login">Back to Login</a></p>`);
                    }
                },
                error: function(xhr) {
                    displayAuthErrors('reset', xhr.responseJSON.errors);
                },
                complete: () => hideAuthLoader(form)
            });
        });

        
       // ### SEARCH ###
    
    // ### SEARCH ###
        function initializeAjaxSearch(inputSelector, resultsSelector, tbodySelector, iconSelector) {
            let searchTimeout;
            const searchInput = $(inputSelector);
            const resultsContainer = $(resultsSelector);
            const tableBody = $(tbodySelector); // Target the specific tbody for results
            const searchIcon = $(iconSelector);

            // Function to redirect to the full search results page
            function goToSearchPage() {
                const query = searchInput.val().trim();
                if (query) {
                    window.location.href = `{{ route('products.search') }}?query=${encodeURIComponent(query)}`;
                }
            }

            // Listen for typing in the search bar
            searchInput.on('keyup', function(e) {
                // If user presses Enter, go directly to the search page
                if (e.key === 'Enter') {
                    goToSearchPage();
                    return;
                }
                
                clearTimeout(searchTimeout);
                const query = $(this).val().trim();

                // Hide the popup if the input is empty
                if (query.length < 1) {
                    resultsContainer.hide();
                    tableBody.html('');
                    return;
                }

                // Use a timeout to wait for the user to stop typing before sending the request
                searchTimeout = setTimeout(function() {
                    resultsContainer.show();
                    // Show a loader inside the table body while fetching
                    tableBody.html('<tr><td colspan="4" class="text-center p-3"><span class="spinner-border spinner-border-sm"></span></td></tr>');
                    
                    $.ajax({
                        url: '{{ route("products.ajax_search") }}',
                        method: 'GET',
                        data: { query: query },
                        success: function(products) {
                            tableBody.html(''); // Clear the loader
                            if (products && products.length > 0) {
                                products.forEach(function(product) {
                                    
                                    // Format the raw numbers received from the server
                                    const basePriceFormatted = `৳${parseInt(product.base_price).toLocaleString()}`;
                                    const discountPriceFormatted = `৳${parseInt(product.discount_price).toLocaleString()}`;

                                    // Build the table rows for the search results
                                    const productHtml = `
                                        <tr onclick="window.location.href='${product.url}'" style="cursor: pointer;">
                                            <td style="width: 60px;">
                                                <img src="${product.image_url}" alt="${product.name}" style="width: 40px; height: 40px; object-fit: contain;">
                                            </td>
                                            <td>${product.name}</td>
                                            <td class="text-end" style="width: 120px;">
                                                ${product.discount_price > 0 
                                                    ? `<del class="text-muted small">${basePriceFormatted}</del>` 
                                                    : basePriceFormatted
                                                }
                                            </td>
                                            <td class="text-end fw-bold text-danger" style="width: 120px;">
                                                ${product.discount_price > 0 ? discountPriceFormatted : '—'}
                                            </td>
                                        </tr>`;
                                    tableBody.append(productHtml);
                                });
                            } else {
                                tableBody.html('<tr><td colspan="4" class="text-center p-3 text-muted">No products found.</td></tr>');
                            }
                        },
                        error: function() {
                            tableBody.html('<tr><td colspan="4" class="text-center p-3 text-danger">Search failed. Please try again.</td></tr>');
                        }
                    });
                }, 300); // 300ms delay after typing stops
            });
            
            // Make the search icon clickable to go to the full search page
            searchIcon.on('click', goToSearchPage);
        }

        // --- 4. RUN INITIALIZATION ---
        loadInitialData();
        
        // Call the function for both desktop and mobile search bars
        initializeAjaxSearch('#product-search-input', '#search-results-container', '#desktop-search-tbody', '#desktop-search-icon');
        initializeAjaxSearch('#mobile-product-search-input', '#mobile-search-results-container', '#mobile-search-tbody', '#mobile-search-icon');
        
        // Hide search results when clicking anywhere else on the page
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.search-container').length) {
                $('.search-results-popup').hide();
            }
        });
    });
    </script>
</body>

</html>