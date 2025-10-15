@if(isset($cart) && count($cart) > 0)
    @foreach($cart as $id => $details)
    <div class="cart-item mb-3" data-product-id="{{ $id }}">
        <img src="{{ $details['image'] ? $front_ins_url . 'public/' . $details['image'] : 'https://placehold.co/70x70?text=N/A' }}" alt="{{ $details['name'] }}" class="cart-item-img">
        <div class="cart-item-details">
            <div class="d-flex justify-content-between align-items-start">
                <h6>{{ $details['name'] }}</h6>
                <button class="btn btn-sm text-muted cart-item-remove" data-product-id="{{ $id }}">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
            <p class="mb-0 text-muted" style="font-size: 0.85rem;">Unit Price: ৳{{ number_format($details['price']) }}</p>
            <div class="d-flex justify-content-between align-items-center mt-1">
                <span class="fw-bold fs-6">৳{{ number_format($details['price'] * $details['quantity']) }}</span>
                
                {{-- NEW Quantity Button Group --}}
                <div class="input-group input-group-sm" style="width: 100px;">
                    <button class="btn btn-outline-secondary cart-quantity-btn" type="button" data-product-id="{{ $id }}" data-change="-1">-</button>
                    <input type="text" class="form-control text-center cart-quantity-input" value="{{ $details['quantity'] }}" data-product-id="{{ $id }}" readonly>
                    <button class="btn btn-outline-secondary cart-quantity-btn" type="button" data-product-id="{{ $id }}" data-change="1">+</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@else
    <div class="text-center p-5">
        <i class="fa-solid fa-cart-plus fa-3x text-muted mb-3"></i>
        <p class="text-muted">Your cart is empty.</p>
    </div>
@endif