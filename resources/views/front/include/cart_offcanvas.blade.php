{{-- resources/views/front/include/cart_offcanvas.blade.php --}}

<div class="offcanvas offcanvas-end offcanvas-cart" tabindex="-1" id="offcanvasCart" aria-labelledby="offcanvasCartLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasCartLabel">
            <i class="fa-solid fa-cart-shopping me-2"></i> Cart (<span id="offcanvas-cart-count">0</span>)
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column justify-content-between p-3">
        {{-- This container will be filled by AJAX --}}
        <div class="cart-items-container flex-grow-1 overflow-auto">
            {{-- Cart items will be loaded here --}}
        </div>

        <div class="cart-total-footer border-top pt-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Sub Total:</h5>
                <h5 class="fw-bold mb-0">৳ <span id="sidebar-cart-subtotal">0</span></h5>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('cart.show') }}" class="btn btn-outline-dark fw-bold w-100">View Cart</a>
                <a href="#" class="btn btn-dark fw-bold w-100">Checkout</a>
            </div>
        </div>
    </div>
</div>