@extends('front.master.master')

@section('title')
{{$front_ins_name}}
@endsection
@section('css')

@endsection
@section('body')
   <main class="spark_container">
    <!-- Banner Section -->
        {{-- resources/views/front/index.blade.php --}}

<div class="spark_home_banner-grid">
    <div class="row g-3 align-items-stretch">
        <div class="col-12 col-lg-8">
            <div class="spark_slider_section">
                <div id="spark_home_heroSlider">
                    {{-- Check if hero section data and left images exist --}}
                    @if($heroSection && !empty($heroSection->left_image))
                        {{-- Loop through each slide image --}}
                        @foreach($heroSection->left_image as $slide)
                            <div class="spark_home_banner-image">
                                <img src="{{ $front_ins_url . 'public/uploads/' . $slide }}" alt="Slider Banner Image">
                            </div>
                        @endforeach
                    @else
                        {{-- Fallback slides if no images are set --}}
                        <div class="spark_home_banner-image">
                            <img src="{{ asset('assets/img/slider/1 (1).webp') }}" alt="Default Slider Banner 1">
                        </div>
                        <div class="spark_home_banner-image">
                            <img src="{{ asset('assets/img/slider/1 (2).webp') }}" alt="Default Slider Banner 2">
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="d-flex flex-column spark_banner_section_container">
                <div class="flex-grow-1 spark_banner_section">
                    {{-- Check for top right image, otherwise show fallback --}}
                    @if($heroSection && $heroSection->top_right_image)
                        <img src="{{ $front_ins_url .'public/uploads/' . $heroSection->top_right_image }}" alt="Static Banner Top">
                    @else
                        <img src="{{ asset('assets/img/slider/1 (3).webp') }}" alt="Default Static Banner Top">
                    @endif
                </div>
                <div class="flex-grow-1 spark_banner_section">
                     {{-- Check for bottom right image, otherwise show fallback --}}
                    @if($heroSection && $heroSection->bottom_right_image)
                        <img src="{{ $front_ins_url .'public/uploads/' . $heroSection->bottom_right_image }}" alt="Static Banner Bottom">
                    @else
                        <img src="{{ asset('assets/img/slider/1 (4).webp') }}" alt="Default Static Banner Bottom">
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
        <!-- End Banner Section -->
        <!-- Category Section -->
        <section class="section">
    <div class="section-title">
        <h1>Featured Category</h1>
        <p>Get Your Desired Product from Featured Category!</p>
    </div>
    <div class="spark_category">
        <div class="spark_category_container">

            {{-- Check if any categories were passed from the controller --}}
            @if(isset($featuredCategories) && $featuredCategories->count() > 0)
                
                {{-- Loop through each category --}}
                @foreach($featuredCategories as $category)
                    <div class="spark_category_item">
                        {{-- The link will go to the category's page using its slug --}}
                        <a href="{{ route('category.show', $category->slug) }}" class="spark_category_inner">
                            <span class="spark_category_icon">
                                {{-- Display the category's image, with a fallback if none exists --}}
                                <img src="{{ $category->image ? $front_ins_url .'public/' . $category->image : 'https://placehold.co/80x80?text=No+Image' }}" alt="{{ $category->name }}">
                            </span>
                            {{-- Display the category's name --}}
                            <p>{{ $category->name }}</p>
                        </a>
                    </div>
                @endforeach

            @else
                <p class="text-center">No featured categories are available right now.</p>
            @endif

        </div>
    </div>
</section>
        <!-- End Category Section -->
        <!-- Flash Sale Section -->
        {{-- Only display this section if there is an active flash sale with products --}}
@if(isset($activeFlashSale) && $flashSaleProducts->isNotEmpty())
<section class="section">
    <div class="section-title">
        {{-- Use the dynamic title from the database --}}
        <h1>{{ $activeFlashSale->title }}</h1>
        <p>Check & Get Your Desired Product!</p>
    </div>
    <div class="spark_flash_sales_container">
        {{-- This is your original row structure --}}
        <div class="row g-2">
            {{-- Loop through each product in the flash sale --}}
            @foreach($flashSaleProducts as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                {{-- This is your original card structure --}}
                <div class="spark_product_box_card">
                    @php
                        // Calculate the discount amount
                        $discountAmount = $product->selling_price - $product->pivot->flash_price;
                    @endphp

                    {{-- Display the discount banner only if there's a discount --}}
                    @if($discountAmount > 0)
                    <div class="spark_product_box_discount-banner">
                        {{ number_format($discountAmount, 0) }}৳ Discount
                    </div>
                    @endif

                    <a href="{{ route('product.show', $product->slug) }}">
                        <div class="spark_product_box_img-container">
                            {{-- Use the product's main image with a fallback --}}
                            <img src="{{ $product->images->isNotEmpty() ? $front_ins_url .'public/' . $product->images->first()->image_path : 'https://placehold.co/400x400?text=N/A' }}"
                                alt="{{ $product->name }}" class="spark_product_box_product-img">
                        </div>

                        <div class="spark_product_box_details">
                            <div class="spark_product_box_title">
                                {{-- Use the product's name --}}
                                {{ $product->name }}
                            </div>

                            {{-- Use the product's short description for the specs list --}}
                            @if($product->short_description)
                            <div class="spark_product_box_specs-list">
                             {!! \Illuminate\Support\Str::limit($product->short_description, 120) !!}
                            </div>
                            @endif
                            
                            <div class="spark_product_box_price">
                                {{-- Display the special flash sale price --}}
                                {{ number_format($product->pivot->flash_price, 0) }}৳
                            </div>

                            <div class="d-grid gap-2">
                                <button class="spark_product_box_buy-btn" type="button">
                                    <i class="fas fa-shopping-basket me-2"></i> Buy Now
                                </button>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button class="spark_product_box_compare-link" type="button">
                                    <i class="fas fa-plus me-2"></i> Compare
                                </button>
                                <button class="spark_product_box_compare-link1" type="button">
                                    <i class="fas fa-heart me-2"></i> Wishlist
                                </button>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif


        <!-- End Flash Sale Section -->
        <!-- About Us Section -->
        <section class="section">
    <div class="home_text_container">
        {{-- Check if the description data exists --}}
        @if(isset($homePageDescription))
            <h2>{{ $homePageDescription->title }}</h2>
            {{-- Use {!! !!} to render HTML content from the description --}}
            <p>{!! $homePageDescription->description !!}</p>
        @else
            {{-- Fallback content if no data is in the database --}}
            <h2>Leading Computer, Laptop & Gaming PC Retail & Online Shop in Bangladesh</h2>
            <p>Technology has become a part of our daily lives, and we depend on tech products daily for a vast
                portion of our lives. There is hardly a home in Bangladesh without a tech product. This is where we
                come in...</p>
        @endif
    </div>
</section>
        <!-- End About Us Section -->
    </main>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        // Use event delegation for buttons in sliders
        $('body').on('click', '.btn-add-cart', function(e) {
            e.preventDefault(); // Prevents the link from jumping to the top of the page

            const productId = $(this).data('product-id');
            const modal = $('#quickViewModal');
            const modalBody = $('#quickViewModalBody');

            // --- START: MODIFIED URL GENERATION ---
            // Create a URL template using the named route and a placeholder
            let urlTemplate = "{{ route('product.quick_view', ['id' => ':id']) }}";
            // Replace the placeholder with the actual product ID
            let productUrl = urlTemplate.replace(':id', productId);
            // --- END: MODIFIED URL GENERATION ---

            // Show the modal
            modal.modal('show');

            // Set a loading state
            modalBody.html('<div class="text-center p-5"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>');

            // Fetch product details via AJAX
            $.ajax({
                url: productUrl, // Use the dynamically generated URL
                type: 'GET',
                success: function(response) {
                    modalBody.html(response);
                },
                error: function() {
                    modalBody.html('<p class="text-danger text-center">Sorry, we could not load the product details. Please try again.</p>');
                }
            });
        });
    });
</script>

@endsection