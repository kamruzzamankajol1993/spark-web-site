<div class="col">
    <div class="spark_product_box_card h-100 d-flex flex-column">
        @php
            // Check if there is a valid offer price
            $hasOffer = $product->offer_price > 0 && $product->offer_price < $product->selling_price;
            $discountAmount = $hasOffer ? $product->selling_price - $product->offer_price : 0;
        @endphp

        @if($discountAmount > 0)
        <div class="spark_product_box_discount-banner">
            {{ number_format($discountAmount, 0) }}৳ Discount
        </div>
        @endif

        {{-- Main link for image, title, and details --}}
        <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none text-dark d-flex flex-column flex-grow-1 p-3">
            <div class="spark_product_box_img-container">
                <img src="{{ $product->images->isNotEmpty() ?  $front_ins_url .'public/' . $product->images->first()->image_path : 'https://placehold.co/400x400?text=N/A' }}"
                    alt="{{ $product->name }}" class="spark_product_box_product-img">
            </div>

            <div class="spark_product_box_details d-flex flex-column flex-grow-1">
                <div class="spark_product_box_title flex-grow-1">
                    {{ $product->name }}
                </div>

                @if($product->short_description)
                <div class="spark_product_box_specs-list">
                    {!! \Illuminate\Support\Str::limit($product->short_description, 100) !!}
                </div>
                @endif
                
                <div class="spark_product_box_price mt-2">
                    @if($hasOffer)
                        <span class="text-danger fw-bold fs-5">৳{{ number_format($product->offer_price, 0) }}</span>
                        <del class="text-muted ms-2 fs-6">৳{{ number_format($product->selling_price, 0) }}</del>
                    @else
                        <span class="text-dark fw-bold fs-5">৳{{ number_format($product->selling_price, 0) }}</span>
                    @endif
                </div>
            </div>
        </a> {{-- End of product details link --}}
        
        {{-- START: ACTION BUTTONS SECTION --}}
        {{-- This new div adds padding around all the buttons --}}
        <div class="p-3 pt-0">
            <div class="d-grid gap-2">
                <button class="spark_product_box_buy-btn" type="button">
                    <i class="fas fa-shopping-cart me-2"></i> Add To Cart
                </button>
            </div>
            <div class="d-grid gap-2 mt-1">
                <button class="spark_product_box_buy-btn" type="button">
                    <i class="fas fa-shopping-basket me-2"></i> Buy Now
                </button>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <button class="spark_product_box_compare-link" type="button">
                    <i class="fas fa-plus me-2"></i> Compare
                </button>
                <button class="spark_product_box_compare-link1" type="button">
                    <i class="fas fa-heart me-2"></i> Wishlist
                </button>
            </div>
        </div>
        {{-- END: ACTION BUTTONS SECTION --}}
    </div>
</div>