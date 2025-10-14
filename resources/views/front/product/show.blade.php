@extends('front.master.master')

@section('title')
{{ $product->name }}
@endsection
@section('css')
<style>
   
#realImageModal .modal-body {
    padding: 0.5rem; /* Add some padding around the slider */
}

.real-image-slider .slick-slide img {
    width: 100%;
    max-height: 75vh; /* Ensure image is not taller than the screen */
    object-fit: contain; /* Show the full image without cropping */
    margin: auto;
}

/* Slick slider arrow customization for the modal */
.real-image-slider .slick-prev,
.real-image-slider .slick-next {
    z-index: 10;
    width: 40px;
    height: 40px;
}
.real-image-slider .slick-prev { left: 25px; }
.real-image-slider .slick-next { right: 25px; }

.real-image-slider .slick-prev:before,
.real-image-slider .slick-next:before {
    font-size: 30px;
    opacity: .75;
    color: #333;
}
    #customer-reviews-section {
    position: relative;
    background-size: cover;
    background-position: center;
    background-attachment: fixed; /* Optional: Creates a cool parallax scrolling effect */
    z-index: 1;
}

#customer-reviews-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(255, 255, 255, 0.92); /* White overlay with 92% opacity */
    z-index: -1; /* Places the overlay behind the content */
}
    /* Style for color swatches */
    .color-option {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        cursor: pointer;
        border: 2px solid #ddd;
        transition: transform 0.2s, border-color 0.2s;
        display: inline-block;
    }
    .color-option.active {
        border-color: #000;
        transform: scale(1.15);
        box-shadow: 0 0 8px rgba(0,0,0,0.3);
    }
    /* Style for size buttons */
    .size-option.active {
        background-color: #212529 !important;
        color: #fff !important;
        border-color: #212529 !important;
    }
    .size-option:disabled {
        cursor: not-allowed;
        opacity: 0.5;
        text-decoration: line-through;
    }
     .star-rating .bi-star-fill { color: #ffc107; }
    .review-images-container img { width: 70px; height: 70px; object-fit: cover; border-radius: 5px; cursor: pointer; }
</style>
@endsection
@section('body')

@endsection
@section('script')
<script>
$(document).ready(function() {

    // --- Real Image Modal Slider Initialization ---
const realImageModal = document.getElementById('realImageModal');
if (realImageModal) {
    realImageModal.addEventListener('shown.bs.modal', function () {
        const slider = $('#real-image-slider');
        
        // Initialize slider only if it hasn't been initialized before
        if (!slider.hasClass('slick-initialized')) {
            slider.slick({
                dots: true,
                infinite: true,
                speed: 300,
                slidesToShow: 1,
                adaptiveHeight: true,
                arrows: true
            });
        }
    });
}

    // --- Real-Time "People Watching" Counter ---
    const watchingContainer = $('.d-flex[data-product-id]');
    const watchingCountElement = $('#watching-count');

    if (watchingContainer.length && watchingCountElement.length) {
        const productId = watchingContainer.data('product-id');
        
        // Create a URL template using the named route and a placeholder
        const urlTemplate = "{{ route('product.view_count', ['id' => ':id']) }}";

        // Set an interval to check for new counts every 8 seconds
        setInterval(function() {
            // Replace the placeholder with the actual product ID for each request
            const finalUrl = urlTemplate.replace(':id', productId);

            $.ajax({
                url: finalUrl, // Use the generated URL
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        watchingCountElement.text(response.view_count);
                    }
                },
                error: function() {
                    console.log('Could not fetch new view count.');
                }
            });
        }, 8000); // Check every 8 seconds
    }
    // --- Configuration ---
    const BASE_PRODUCT_PRICE = {{ $product->discount_price ?? $product->base_price }};
    const IMAGE_BASE_URL = "{{ $front_ins_url . 'public/uploads/' }}";
    let selectedVariantId = null;
    let selectedSize = null;
    let currentStock = 0;
    
    // --- Slick Slider Initialization ---
    function initializeSlick() {
        // Destroy existing sliders if they exist
        if ($('#main-product-slider').hasClass('slick-initialized')) {
            $('#main-product-slider').slick('unslick');
        }
        if ($('#thumbnail-nav-slider').hasClass('slick-initialized')) {
            $('#thumbnail-nav-slider').slick('unslick');
        }

        $('#main-product-slider').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '#thumbnail-nav-slider'
        });

        $('#thumbnail-nav-slider').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            vertical: true,
            asNavFor: '#main-product-slider',
            dots: false,
            arrows: false,
            focusOnSelect: true,
            responsive: [{
                breakpoint: 768,
                settings: {
                    slidesToShow: 4,
                    vertical: false
                }
            }]
        });
    }

    // Custom arrow functionality
    $('.prev-arrow').click(() => $('#thumbnail-nav-slider').slick('slickPrev'));
    $('.next-arrow').click(() => $('#thumbnail-nav-slider').slick('slickNext'));

    // --- Core Logic Functions ---
    function updateSizes(sizes) {
        const container = $('#size-options-container');
        container.empty(); // Clear old sizes
        $('#selected-size-name').text('Select a size');
        selectedSize = null;
        currentStock = 0;

        if (sizes && sizes.length > 0) {
            sizes.forEach(size => {
                const button = $('<button></button>')
                    .addClass('btn btn-outline-secondary rounded-3 size-option')
                    .text(size.name)
                    .data('size-name', size.name)
                    .data('stock', size.quantity);
                
                if (size.quantity <= 0) {
                    button.prop('disabled', true);
                }
                container.append(button);
            });
        } else {
            container.html('<p class="text-danger small">This color is currently out of stock.</p>');
        }
    }

    function updateImages(mainImages, thumbImages) {
        const mainSlider = $('#main-product-slider');
        const thumbSlider = $('#thumbnail-nav-slider');
        
        mainSlider.empty();
        thumbSlider.empty();

        mainImages.forEach(img => {
            mainSlider.append(`<div><img src="${IMAGE_BASE_URL}${img}" class="img-fluid rounded-3"></div>`);
        });

        thumbImages.forEach(img => {
            thumbSlider.append(`<div><img src="${IMAGE_BASE_URL}${img}" class="img-fluid rounded-3 thumbnail-image"></div>`);
        });

        initializeSlick();
    }
    
    function updatePrice(additionalPrice) {
        const finalPrice = BASE_PRODUCT_PRICE + parseFloat(additionalPrice || 0);
        $('#product-price').text(`৳ ${finalPrice.toFixed(2)}`);
    }

    // --- Event Handlers ---
    $('.color-option').on('click', function() {
        const $this = $(this);

        // Update active state
        $('.color-option').removeClass('active');
        $this.addClass('active');

        // Extract data
        const variantData = $this.data();
        selectedVariantId = variantData.variantId;
        
        // Update UI
        $('#selected-color-name').text(variantData.colorName);
        $('#product-sku').text(variantData.variantSku || '{{ $product->product_code }}');
        updateSizes(variantData.sizes);
        updatePrice(variantData.additionalPrice);
        updateImages(variantData.mainImages, variantData.thumbImages);
        $('#quantity-value').text(1);
    });

    $('#size-options-container').on('click', '.size-option:not(:disabled)', function() {
        const $this = $(this);
        $('#size-options-container .size-option').removeClass('active');
        $this.addClass('active');
        selectedSize = $this.data('size-name');
         currentStock = $this.data('stock');
        $('#selected-size-name').text(selectedSize);
        $('#quantity-value').text(1);
    });

    $('#quantity-plus').on('click', () => {
    // First, check if a size has been selected at all
    if (!selectedSize) {
        Swal.fire({
            icon: 'warning',
            title: 'Select a Size',
            text: 'Please select a size first.'
        });
        return;
    }

    let qty = parseInt($('#quantity-value').text());

    // Now, check if the quantity is already at the stock limit
    if (qty >= currentStock) {
        Swal.fire({
            icon: 'info',
            title: 'Stock Limit Reached',
            text: `Only ${currentStock} items are available for this size.`
        });
    } else {
        // Only increase the quantity if it's less than the stock
        $('#quantity-value').text(++qty);
    }
});

    $('#quantity-minus').on('click', () => {
        let qty = parseInt($('#quantity-value').text());
        if (qty > 1) {
            $('#quantity-value').text(--qty);
        }
    });

    $('#add-to-cart').on('click', function() {
        // Validation

         if (!selectedVariantId) {
            Swal.fire({
              icon: 'warning',
              title: 'Hold on!',
              text: 'Please select a color.'
            });
            return;
        }
        if (!selectedSize) {
            Swal.fire({
              icon: 'warning',
              title: 'Almost there!',
              text: 'Please select a size.'
            });
            return;
        }
        
        const $button = $(this);
        const cartData = {
            productId: {{ $product->id }},
            variantId: selectedVariantId,
            size: selectedSize,
            quantity: parseInt($('#quantity-value').text()),
            _token: "{{ csrf_token() }}" 
        };

        // AJAX call to add the product to the cart
        $.ajax({
            url: '{{ route("cart.add") }}',
            type: 'POST',
            data: cartData,
            beforeSend: function() {
                // Provide visual feedback
                $button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...');
            },
            success: function(response) {
                if (response.success) {
                    // Update the cart display everywhere
                    updateCartOffcanvas();
                    
                    // Automatically open the cart offcanvas to show the user their new item
                    const cartOffcanvas = new bootstrap.Offcanvas(document.getElementById('cartOffcanvas'));
                    cartOffcanvas.show();
                } else {
                    Swal.fire({
                      icon: 'error',
                      title: 'Oops...',
                      text: response.message || 'An unknown error occurred.'
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = 'Something went wrong. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                  icon: 'error',
                  title: 'Request Failed',
                  text: errorMessage
                });
            },
            complete: function() {
                // Restore the button to its original state
                $button.prop('disabled', false).html('Add To Cart');
            }
        });
    });

     // --- NEW SEPARATE "Buy Now" Handler ---
    $('#buy-now').on('click', function() {
        if (!selectedVariantId) {
            Swal.fire({ icon: 'warning', title: 'Hold on!', text: 'Please select a color.' });
            return;
        }
        if (!selectedSize) {
            Swal.fire({ icon: 'warning', title: 'Almost there!', text: 'Please select a size.' });
            return;
        }

        const $button = $(this);
        const cartData = {
            productId: {{ $product->id }},
            variantId: selectedVariantId,
            size: selectedSize,
            quantity: parseInt($('#quantity-value').text()),
            _token: "{{ csrf_token() }}"
        };

        $.ajax({
            url: '{{ route("cart.add") }}',
            type: 'POST',
            data: cartData,
            beforeSend: function() {
                $button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing...');
            },
            success: function(response) {
                if (response.success) {
                    updateCartOffcanvas();
                    
                     @auth
                        // If user is logged in, redirect straight to checkout
                        window.location.href = "{{ route('user.checkout') }}";
                    @else
                        // If user is a guest, open the login/register offcanvas
                        const signInOffcanvas = new bootstrap.Offcanvas(document.getElementById('signInOffcanvas'));
                        signInOffcanvas.show();
                    @endauth
                } else {
                    Swal.fire({ icon: 'error', title: 'Oops...', text: response.message || 'An error occurred.' });
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Request Failed', text: 'Something went wrong.' });
            },
            complete: function() {
                // Only re-enable the button if the user is a guest (and the modal is shown)
                // Otherwise, the page will redirect.
                @guest
                    $button.prop('disabled', false).html('Buy Now');
                @endguest
            }
        });
    });

    // --- NEW: Handle Add to Wishlist on Product Detail Page ---
    $('#add-to-wishlist').on('click', function() {
        @auth
            // --- USER IS LOGGED IN ---
            if (!selectedVariantId) {
                Swal.fire({ icon: 'warning', title: 'Hold on!', text: 'Please select a color first.' });
                return;
            }
            if (!selectedSize) {
                Swal.fire({ icon: 'warning', title: 'Almost there!', text: 'Please select a size.' });
                return;
            }

            const $button = $(this);
            const wishlistData = {
                product_id: {{ $product->id }},
                variant_id: selectedVariantId,
                size: selectedSize,
                _token: "{{ csrf_token() }}"
            };

            $.ajax({
                url: '{{ route("wishlist.add") }}',
                type: 'POST',
                data: wishlistData,
                beforeSend: function() {
                    $button.prop('disabled', true).find('span').text('Adding...');
                },
                success: function(response) {
    if (response.success) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: response.message,
            showConfirmButton: false,
            timer: 2000
        });
        // UPDATE THE COUNT
        if(response.count !== undefined) {
            $('#wishlist-count').text(response.count);
            $('#mobile-wishlist-count').text(response.count);
        }
    } else {
         Swal.fire({ icon: 'info', title: 'Already Added', text: response.message });
    }
},
                error: function(xhr) {
                     Swal.fire({ icon: 'error', title: 'Oops...', text: 'Something went wrong. Please try again.' });
                },
                complete: function() {
                    $button.prop('disabled', false).find('span').text('Add to wishlist');
                }
            });

        @else
            // --- USER IS A GUEST ---
            Swal.fire({
                title: 'Login Required',
                text: "You need to be logged in to add items to your wishlist.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Login or Register',
                cancelButtonText: 'Not Now'
            }).then((result) => {

                 if (result.isConfirmed) {
                    // --- START OF NEW, MORE ROBUST FIX ---
                    const quickViewModalEl = document.getElementById('quickViewModal');
                    const quickViewModalInstance = bootstrap.Modal.getInstance(quickViewModalEl);
                    const signInOffcanvas = new bootstrap.Offcanvas(document.getElementById('signInOffcanvas'));

                    // 1. Hide the quick view modal
                    if (quickViewModalInstance) {
                        quickViewModalInstance.hide();
                    }

                    // 2. Manually remove the backdrop and cleanup body styles.
                    //    This forcefully resets the state and prevents conflicts.
                    $('.modal-backdrop').remove();
                    $('body').removeAttr('style').removeClass('modal-open');
                    
                    // 3. Show the sign-in offcanvas.
                    signInOffcanvas.show();
                    // --- END OF NEW FIX ---
                }
               
            });
        @endauth
    });

    // --- NEW: Add to Compare Handler ---
    $('#add-to-compare').on('click', function() {
        const productId = {{ $product->id }};
        const button = $(this);
        button.prop('disabled', true).find('span').text('Adding...');

        $.ajax({
            url: '{{ route("compare.add") }}',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}', product_id: productId },
            success: function(response) {
                Swal.fire({ toast: true, position: 'top-end', icon: response.success ? 'success' : 'info', title: response.message, showConfirmButton: false, timer: 2500 });
                if(response.count !== undefined) {
                    $('#compare-count').text(response.count);
                }
            },
            error: function() {
                Swal.fire({icon: 'error', title: 'Error', text: 'Could not add to compare list.'});
            },
            complete: function() {
                button.prop('disabled', false).find('span').text('Add to compare');
            }
        });
    });

    // --- Initial Page Load ---
    initializeSlick();
    // Trigger a click on the first color to initialize sizes and prices
    $('.color-option.active').first().trigger('click');
});
</script>
@endsection