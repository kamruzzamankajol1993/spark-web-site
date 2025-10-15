@forelse ($cart as $id => $details)
<tr data-product-id="{{ $id }}">
    <td style="padding-left: 1rem;">
        <img src="{{ $details['image'] ? $front_ins_url . 'public/' . $details['image'] : 'https://placehold.co/100x100?text=N/A' }}"
            alt="{{ $details['name'] }}" class="spark_cart_page_product-image">
    </td>
    <td>
        <div class="spark_cart_page_product-name">{{ $details['name'] }}</div>
    </td>
    <td>
        <div class="d-flex align-items-center">
            <div class="input-group spark_cart_page_qty-controls me-2">
                <button class="btn btn-outline-secondary cart-quantity-btn" type="button" data-product-id="{{ $id }}" data-change="-1">-</button>
                <input type="text" class="form-control text-center cart-quantity-input" value="{{ $details['quantity'] }}" data-product-id="{{ $id }}" readonly>
                <button class="btn btn-outline-secondary cart-quantity-btn" type="button" data-product-id="{{ $id }}" data-change="1">+</button>
            </div>
            <i class="fas fa-times spark_cart_page_remove-icon cart-item-remove" data-product-id="{{ $id }}" style="cursor:pointer;"></i>
        </div>
    </td>
    <td class="text-end">৳{{ number_format($details['price']) }}</td>
    <td class="text-end cart-item-total-price" style="padding-right: 1rem;">
        ৳{{ number_format($details['price'] * $details['quantity']) }}
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="text-center py-5">
        <p class="mb-0">Your cart is empty.</p>
    </td>
</tr>
@endforelse