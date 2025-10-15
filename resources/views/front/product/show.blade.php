@extends('front.master.master')

@section('title')
    {{ $product->name }}
@endsection

@section('css')
@endsection

@section('body')
<main class="spark_container">
    <div class="spark_product_details_container">
        <div class="row g-2">
            <div class="col-lg-12">
                <nav class="spark_product_page_breadcrumbs" aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home.index') }}" class="text-decoration-none text-secondary">Home</a>
                        </li>
                        @if($product->category && $product->category->parent)
                        <li class="breadcrumb-item">
                            <a href="{{ route('category.show', $product->category->parent->slug) }}" class="text-decoration-none text-secondary">{{ $product->category->parent->name }}</a>
                        </li>
                        @endif
                        @if($product->category)
                        <li class="breadcrumb-item">
                            <a href="{{ route('category.show', $product->category->slug) }}" class="text-decoration-none text-secondary">{{ $product->category->name }}</a>
                        </li>
                        @endif
                        <li class="breadcrumb-item active" aria-current="page">{{ \Illuminate\Support\Str::limit($product->name, 50) }}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-2 col-md-3 featured_card_hidden">
                <div class="card p-3 shadow-sm spark_details_features_card">
                    <div class="spark_details_features_header">
                        Related Products
                    </div>
                    <ul class="spark_details_features_product-list">
                        @forelse($relatedProducts as $relatedProduct)
                        <li class="spark_details_features_product-item">
                            <a href="{{ route('product.show', $relatedProduct->slug) }}" class="spark_details_features_product-link">
                                <div class="spark_details_features_img-container">
                                    <img src="{{ $relatedProduct->images->isNotEmpty() ? $front_ins_url . 'public/' . $relatedProduct->images->first()->image_path : 'https://placehold.co/70x70?text=N/A' }}"
                                         alt="{{ $relatedProduct->name }}" class="spark_details_features_img">
                                </div>
                                <div class="spark_details_features_details">
                                    <div class="spark_details_features_title">
                                        {{ \Illuminate\Support\Str::limit($relatedProduct->name, 60) }}
                                    </div>
                                    <div class="d-flex flex-wrap align-items-baseline">
                                        @if($relatedProduct->offer_price > 0 && $relatedProduct->offer_price < $relatedProduct->selling_price)
                                            <span class="spark_details_features_price">৳ {{ number_format($relatedProduct->offer_price) }}</span>
                                            <span class="spark_details_features_old-price">৳ {{ number_format($relatedProduct->selling_price) }}</span>
                                        @else
                                            <span class="spark_details_features_price">৳ {{ number_format($relatedProduct->selling_price) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </li>
                        @empty
                        <li class="spark_details_features_product-item text-muted" style="font-size: 0.8rem;">No related products found.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="col-lg-10 col-md-9 col-12">
                <div class="row">
                    <div class="col-12">
                        <div class="spark_product_details_main-card position-relative">
                            @php
                                $hasOffer = $product->offer_price > 0 && $product->offer_price < $product->selling_price;
                                $discountAmount = $hasOffer ? $product->selling_price - $product->offer_price : 0;
                            @endphp

                            @if($discountAmount > 0)
                            <span class="spark_product_details_save-badge">Save: ৳ {{ number_format($discountAmount) }}</span>
                            @endif

                            <div class="row">
                                <div class="col-md-5 d-flex flex-column align-items-center">
                                    <div class="spark_product_details_main-img-container text-center w-100"
                                         data-bs-toggle="modal" data-bs-target="#imageGalleryModal"
                                         id="mainImageClick">
                                        <img src="{{ $product->images->isNotEmpty() ? $front_ins_url . 'public/' . $product->images->first()->image_path : 'https://placehold.co/400x400?text=N/A' }}"
                                             alt="{{ $product->name }}" class="spark_product_details_main-img"
                                             id="mainImage">
                                    </div>

                                    @if($product->images->count() > 1)
                                    <div class="d-flex justify-content-center gap-2 mb-4 w-100">
                                        @foreach($product->images as $index => $image)
                                        <img src="{{ $front_ins_url . 'public/' . $image->image_path }}"
                                             data-image="{{ $front_ins_url . 'public/' . $image->image_path }}"
                                             alt="Thumbnail {{ $index + 1 }}" class="spark_product_details_thumb {{ $index == 0 ? 'active' : '' }}"
                                             onclick="switchImage(this, {{ $index }})">
                                        @endforeach
                                    </div>
                                    @endif
                                </div>

                                <div class="col-md-7">
                                    <h2 class="spark_product_details_title">{{ $product->name }}</h2>

                                    <div class="mb-3">
                                        <span class="spark_product_details_info-badge">Stock :
                                            @if($product->stock && $product->stock->quantity > 0)
                                                <span class="text-success">In Stock</span>
                                            @else
                                                <span class="text-danger">Out of Stock</span>
                                            @endif
                                        </span>
                                        <span class="spark_product_details_info-badge">PID : {{ $product->id }}</span>
                                        @if($product->sku)
                                        <span class="spark_product_details_info-badge">SKU : {{ $product->sku }}</span>
                                        @endif
                                        @if($product->brand)
                                        <span class="spark_product_details_info-badge">Brand : {{ $product->brand->name }}</span>
                                        @endif
                                        {{-- Static content for Model and Warranty as no dynamic field exists --}}
                                        <span class="spark_product_details_info-badge">Model : ExpertBook B1</span>
                                        <span class="spark_product_details_info-badge">Warranty : 3 Years</span>
                                    </div>

                                    <div class="mb-3 d-flex align-items-center">
                                        <div class="text-warning me-2" style="font-size: 0.9rem;">
                                            @php $rating = round($product->reviews_avg_rating ?? 0); @endphp
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <small class="text-muted">({{ $product->reviews_count }} Reviews)</small>
                                    </div>

                                    @if($product->short_description)
                                    <div class="spark_product_details_feature-list">
                                        {!! $product->short_description !!}
                                    </div>
                                    @endif

                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <div class="spark_product_details_price-box h-100">
                                                <div class="spark_product_details_price-title">{{ $hasOffer ? 'Discount Price' : 'Regular Price' }}</div>
                                                @if($hasOffer)
                                                    <span class="spark_product_details_current-price">৳ {{ number_format($product->offer_price) }}</span>
                                                    <span class="spark_product_details_old-price ms-2">৳ {{ number_format($product->selling_price) }}</span>
                                                @else
                                                    <span class="spark_product_details_current-price">৳ {{ number_format($product->selling_price) }}</span>
                                                @endif
                                                <a href="#" class="spark_product_details_emi-link d-block mt-1"><i class="fas fa-plus me-1"></i> Available Payment Method</a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="spark_product_details_price-box h-100">
                                                <div class="spark_product_details_emi-text">EMI Start From*</div>
                                                <div class="spark_product_details_emi-price">৳ {{ number_format($product->selling_price / 12, 2) }}</div>
                                                <a href="#" class="spark_product_details_emi-link d-block mt-1"><i class="fas fa-eye me-1"></i> View Banks EMI Plans</a>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- This form structure allows the global script in master.blade.php to handle the AJAX request --}}
<form class="add-to-cart-form">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <div class="d-flex flex-wrap align-items-center mb-4 gap-2">
        <div class="input-group w-auto">
            <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(-1)">-</button>
            {{-- Added name="quantity" so it can be submitted with the form --}}
            <input type="text" class="form-control text-center spark_product_details_qty-input" value="1" id="quantityInput" name="quantity">
            <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(1)">+</button>
        </div>
        {{-- The button is now type="submit" to trigger the form submission --}}
        <button type="submit" class="btn spark_product_details_cart-btn flex-grow-1">
             <span class="button-text">Add to Cart</span>
             <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
        </button>
        
        {{-- These buttons remain outside the form's control --}}
                {{-- UPDATED "BUY NOW" BUTTON --}}
        <button type="button" id="buy-now-btn" class="btn spark_product_details_buy-btn">
            <span class="button-text">Buy Now</span>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
        </button>

       <button class="btn spark_product_details_heart-btn wishlist-btn" type="button" data-product-id="{{ $product->id }}">
    <span class="button-text">
        <i class="fa-heart {{ (isset($wishlistProductIds) && in_array($product->id, $wishlistProductIds)) ? 'fas' : 'far' }}"></i>
    </span>
    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
</button>
<button class="btn spark_product_details_compare-btn compare-btn" type="button" data-product-id="{{ $product->id }}">
    <span class="button-text">
        <i class="fas fa-exchange-alt"></i>
    </span>
    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
</button>

    </div>
</form>

                                    <div class="row g-2">
                                        <div class="col-md-6"><a href="#" class="spark_product_details_bottom-link">Product Disclaimer <i class="fas fa-chevron-right"></i></a></div>
                                        <div class="col-md-6"><a href="#" class="spark_product_details_bottom-link">Any Suggestions? <i class="fas fa-chevron-right"></i></a></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="spark_product_details_tabs">
                            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="specification-tab" data-bs-toggle="tab" data-bs-target="#specification" type="button" role="tab" aria-controls="specification" aria-selected="false">Specification</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews ({{ $product->reviews_count }})</button>
                                </li>
                            </ul>
                            <div class="tab-content spark_product_details_tab-content" id="productTabContent">
                                <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                                    {!! $product->description !!}
                                </div>
                                <div class="tab-pane fade" id="specification" role="tabpanel" aria-labelledby="specification-tab">
                                    @if($product->attributeValues->isNotEmpty())
                                    <table class="table table-bordered product_page_table">
                                        <tr><td colspan="2" class="table_header_title">Basic Information</td></tr>
                                        @foreach($product->attributeValues as $attributeValue)
                                            <tr>
                                                <td>{{ $attributeValue->attribute->name }}</td>
                                                <td>{{ $attributeValue->value }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    @else
                                    <p class="text-muted p-3">No specification details available for this product.</p>
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                    @forelse($product->reviews as $review)
                                    <div class="border-bottom pb-3 mb-3">
                                        <strong>{{ $review->user->name ?? 'Anonymous' }}</strong>
                                        <div class="text-warning my-1" style="font-size: 0.8rem;">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                            @endfor
                                        </div>
                                        <p class="mb-0" style="font-size: 0.9rem;">{{ $review->description }}</p>
                                        <small class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
                                    </div>
                                    @empty
                                    <p class="text-muted p-3">No reviews yet. Be the first to review this product!</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="imageGalleryModal" tabindex="-1" aria-labelledby="imageGalleryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="imageCarousel" class="carousel slide w-100">
                    <div class="carousel-inner" id="carouselInner"></div>
                    <button class="carousel-control-prev spark_product_details_carousel-control" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                        <i class="fas fa-chevron-left"></i><span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next spark_product_details_carousel-control" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                        <i class="fas fa-chevron-right"></i><span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // Make product images available to JavaScript
    const productImages = @json($product->images->map(function($image, $front_ins_url) {
        return $front_ins_url . 'public/' . $image->image_path;
    }));
    let currentImageIndex = 0;

    function switchImage(thumbnail, index) {
        document.getElementById('mainImage').src = thumbnail.src;
        currentImageIndex = index;
        document.querySelectorAll('.spark_product_details_thumb').forEach(t => t.classList.remove('active'));
        thumbnail.classList.add('active');
    }

    const imageGalleryModalEl = document.getElementById('imageGalleryModal');

    document.getElementById('mainImageClick').addEventListener('click', function() {
        const carouselInner = document.getElementById('carouselInner');
        carouselInner.innerHTML = '';

        productImages.forEach((imageSrc, index) => {
            const carouselItem = document.createElement('div');
            carouselItem.className = `carousel-item ${index === currentImageIndex ? 'active' : ''}`;
            const img = document.createElement('img');
            img.src = imageSrc;
            img.className = 'd-block w-100';
            img.alt = `Product View ${index + 1}`;
            carouselItem.appendChild(img);
            carouselInner.appendChild(carouselItem);
        });

        const carousel = new bootstrap.Carousel(document.getElementById('imageCarousel'), { interval: false });
        carousel.to(currentImageIndex);
        const modal = new bootstrap.Modal(imageGalleryModalEl);
        modal.show();
    });
    
    // Quantity changer function
    function changeQuantity(amount) {
        const quantityInput = document.getElementById('quantityInput');
        let currentValue = parseInt(quantityInput.value, 10);
        currentValue += amount;
        if (currentValue < 1) {
            currentValue = 1;
        }
        quantityInput.value = currentValue;
    }

    // Modal cleanup fix
    imageGalleryModalEl.addEventListener('hidden.bs.modal', function () {
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    });
</script>
@endsection