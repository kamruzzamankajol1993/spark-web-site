@extends('front.master.master')

@section('title', 'My Wishlist')

@section('css')
<style>
    .wishlist-item-card {
        transition: all 0.3s ease-in-out;
        border: 1px solid #e9ecef;
    }
    .wishlist-item-card:hover {
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.1);
        transform: translateY(-2px);
    }
    .wishlist-item-actions .btn {
        flex: 1;
    }
</style>
@endsection

@section('body')
<main class="spark_container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0">My Wishlist</h1>
        @if($wishlistItems->isNotEmpty())
            <span class="text-muted">{{ $wishlistItems->count() }} Items</span>
        @endif
    </div>

    @if($wishlistItems->isNotEmpty())
        <div class="row g-3" id="wishlist-items-container">
            @foreach($wishlistItems as $item)
                <div class="col-12 wishlist-item-card" data-product-id="{{ $item->product->id }}">
                    <div class="p-3">
                        <div class="row align-items-center g-3">
                            {{-- Product Image & Name --}}
                            <div class="col-md-5 d-flex align-items-center">
                                <a href="{{ route('product.show', $item->product->slug) }}" class="me-3">
                                    <img src="{{ $item->product->images->isNotEmpty() ? $front_ins_url . 'public/' . $item->product->images->first()->image_path : 'https://placehold.co/100x100?text=N/A' }}" alt="{{ $item->product->name }}" style="width: 80px; height: 80px; object-fit: contain;">
                                </a>
                                <div>
                                    <a href="{{ route('product.show', $item->product->slug) }}" class="text-decoration-none text-dark fw-bold">
                                        {{ $item->product->name }}
                                    </a>
                                </div>
                            </div>

                            {{-- Price --}}
                            <div class="col-md-2 text-md-center">
                                @if($item->product->offer_price > 0 && $item->product->offer_price < $item->product->selling_price)
                                    <span class="fw-bold fs-5 text-danger">৳{{ number_format($item->product->offer_price) }}</span>
                                    <del class="text-muted small d-block">৳{{ number_format($item->product->selling_price) }}</del>
                                @else
                                    <span class="fw-bold fs-5">৳{{ number_format($item->product->selling_price) }}</span>
                                @endif
                            </div>

                            {{-- Stock Status --}}
                            <div class="col-md-2 text-md-center">
                                @if($item->product->stock && $item->product->stock->quantity > 0)
                                    <span class="badge bg-success fs-6">In Stock</span>
                                @else
                                    <span class="badge bg-danger fs-6">Out of Stock</span>
                                @endif
                            </div>

                            {{-- Action Buttons --}}
                            <div class="col-md-3 text-md-end">
                                <div class="d-flex gap-2 wishlist-item-actions">
                                    <button class="btn btn-dark btn-sm move-to-cart-btn" data-product-id="{{ $item->product->id }}" {{ ($item->product->stock && $item->product->stock->quantity > 0) ? '' : 'disabled' }}>
                                        <span class="button-text"><i class="fas fa-shopping-cart"></i></span>
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm wishlist-btn" data-product-id="{{ $item->product->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="col-12">
            <div class="alert alert-warning text-center">
                <p class="mb-0">Your wishlist is empty.</p>
                <a href="{{ route('home.index') }}" class="btn btn-dark mt-3">Continue Shopping</a>
            </div>
        </div>
    @endif
</main>
@endsection