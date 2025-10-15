@extends('front.master.master')

@section('title', 'Shopping Cart')

@section('body')
<main class="spark_container">
    <div class="spark_product_page_main-content">
        <div class="row">
            <div class="col-12">
                <nav class="spark_product_page_breadcrumbs" aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ route('home.index') }}" class="text-decoration-none text-secondary">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cart</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="spark_cart_page_container">
            <h1 class="spark_cart_page_title">Shopping Cart</h1>

            <div class="spark_cart_page_table">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 100px; padding-left: 1rem;">Image</th>
                                <th style="min-width: 250px;">Product Name</th>
                                <th style="width: 150px;">Quantity</th>
                                <th style="width: 120px;" class="text-end">Unit Price</th>
                                <th style="width: 120px; padding-right: 1rem;" class="text-end">Total</th>
                            </tr>
                        </thead>
                        {{-- The table body will be updated by the global AJAX script --}}
                        <tbody id="main-cart-body">
                            @include('front.cart._main_cart_table_body')
                        </tbody>
                    </table>
                </div>
                <div class="spark_cart_page_summary-row">
    <div class="spark_cart_page_summary-box" id="cart-summary-box">
        <div class="spark_cart_page_summary-item">
            <span class="spark_cart_page_summary-label">Sub-Total:</span>
            {{-- CHANGE THE ID HERE --}}
            <span class="spark_cart_page_summary-value">৳<span id="main-cart-subtotal">{{ $subtotal }}</span></span>
        </div>
        <div class="spark_cart_page_summary-item" id="discount-row" style="{{ !$coupon ? 'display: none;' : '' }}">
            <span class="spark_cart_page_summary-label">Discount (<span id="coupon-code-text">{{ $coupon['code'] ?? '' }}</span>):</span>
            {{-- CHANGE THE ID HERE --}}
            <span class="spark_cart_page_summary-value text-danger">- ৳<span id="main-cart-discount">{{ $discount }}</span></span>
        </div>
        <div class="spark_cart_page_summary-item fw-bold">
            <span class="spark_cart_page_summary-label">Total:</span>
            {{-- CHANGE THE ID HERE --}}
            <span class="spark_cart_page_summary-value">৳<span id="main-cart-total">{{ $total }}</span></span>
        </div>
    </div>
</div>
            </div>
            <div class="spark_cart_page_actions">
                <div class="spark_cart_page_actions-header">What would you like to do next?</div>
                <p class="text-muted" style="font-size: 0.9rem;">Choose if you have a discount code you want to use.</p>

                <div class="row g-3">
                    <div class="col-md-12">
                        <div id="coupon-input-area" style="{{ $coupon ? 'display: none;' : '' }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="input-group me-3 flex-grow-1">
                                    <input type="text" class="form-control" id="coupon-input" placeholder="Enter coupon code here">
                                </div>
                                <button class="btn spark_cart_page_apply-btn" id="apply-coupon-btn" type="button">Apply Coupon</button>
                            </div>
                        </div>
                        <div id="applied-coupon-area" class="alert alert-success" style="{{ !$coupon ? 'display: none;' : '' }}">
                            Applied Coupon: <strong id="applied-coupon-code">{{ $coupon['code'] ?? '' }}</strong>
                            <button class="btn-close float-end" id="remove-coupon-btn"></button>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between spark_cart_page_actions-buttons">
                    <a href="{{ route('shop.show') }}" class="btn spark_cart_page_action-btn spark_cart_page_continue-btn">
                        Continue Shopping
                    </a>
                    <a href="#" class="btn spark_cart_page_action-btn spark_cart_page_confirm-btn">
                        Process To Checkout
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection