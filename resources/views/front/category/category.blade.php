@extends('front.master.master')

@section('title', $category->name ?? 'Category')

@section('css')
<style>
    /* Make the entire filter sidebar sticky and scrollable on larger screens */
    @media (min-width: 992px) {
        .spark_product_page_filter-sidebar {
            position: sticky;
            top: 20px; /* Adjust based on your header's height */
            max-height: calc(100vh - 40px); /* Limits height to viewport height minus padding */
            overflow-y: auto; /* Enables vertical scrolling for the WHOLE sidebar */
            padding-right: 15px; /* Adds space for the main scrollbar */
        }
    }

    /* THIS IS THE FIX: Re-enables scrolling for INDIVIDUAL filter sections */
    .spark_product_page_filter-scroll {
        max-height: 200px; /* Set a fixed height for individual scrollable areas */
        overflow-y: auto;
        padding-right: 5px; /* A smaller padding for the nested scrollbar */
    }

    /* Style for the loading indicator */
    #loading-indicator {
        display: none; /* Hidden by default */
        padding: 20px;
        text-align: center;
    }
</style>
@endsection

@section('body')
<main class="spark_container">
    <div class="spark_product_page_main-content">

        <div class="row">
            <div class="col-12">
                <nav class="spark_product_page_breadcrumbs" aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ route('home.index') }}" class="text-decoration-none text-secondary">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                    </ol>
                </nav>
                <div class="page_breadcrumbs_text">
                    <h1 class="spark_product_page_heading fs-3 fw-bold text-dark">{{ $category->name }}</h1>
                    @if($category->description)
                    <p class="spark_product_page_description text-muted mb-4" style="font-size: 0.85rem;">
                        {{ $category->description }}
                    </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="row d-lg-none mt-3">
            <div class="col-12">
                <button class="btn btn-dark filter-button w-100" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasFilter" aria-controls="offcanvasFilter">
                    <i class="fas fa-filter me-2"></i> Filter Products
                </button>
            </div>
        </div>

        <div class="row mt-4">

            <div class="col-lg-3 d-none d-lg-block">
                <div class="spark_product_page_filter-sidebar">

                    <div class="spark_product_page_filter-group mb-2">
                        <div class="spark_product_page_filter-title" data-bs-toggle="collapse" data-bs-target="#priceCollapse">
                            Price Range <i class="fas fa-chevron-down float-end"></i>
                        </div>
                        <div class="collapse show" id="priceCollapse">
                            <div class="spark_product_page_range-slider px-2">
                                <input type="range" min="1000" max="500000" value="1000" id="minPrice">
                                <input type="range" min="1000" max="500000" value="500000" id="maxPrice">
                                <div class="d-flex justify-content-between mt-2">
                                    <span class="badge bg-danger">1,000৳</span>
                                    <span class="badge bg-danger">500,000৳</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="spark_product_page_filter-group mb-2">
                        <div class="spark_product_page_filter-title" data-bs-toggle="collapse" data-bs-target="#availabilityCollapse">
                            Availability <i class="fas fa-chevron-down float-end"></i>
                        </div>
                        <div class="collapse show" id="availabilityCollapse">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="instock"><label class="form-check-label" for="instock">In Stock</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="preorder"><label class="form-check-label" for="preorder">Pre Order</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="upcoming"><label class="form-check-label" for="upcoming">Up Coming</label></div>
                        </div>
                    </div>

                    @if(isset($filterableAttributes) && $filterableAttributes->isNotEmpty())
                        @foreach($filterableAttributes as $attribute)
                            @if($attribute->options->isNotEmpty())
                                <div class="spark_product_page_filter-group mb-2">
                                    <div class="spark_product_page_filter-title" data-bs-toggle="collapse" data-bs-target="#collapse-{{ \Illuminate\Support\Str::slug($attribute->name) }}">
                                        {{ $attribute->name }} <i class="fas fa-chevron-down float-end"></i>
                                    </div>
                                    <div class="collapse" id="collapse-{{ \Illuminate\Support\Str::slug($attribute->name) }}">
                                        {{-- This class now correctly enables individual scrolling --}}
                                        <div class="spark_product_page_filter-scroll">
                                            @foreach($attribute->options as $option)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="{{ $option->value }}" id="option-{{ $option->id }}">
                                                    <label class="form-check-label" for="option-{{ $option->id }}">{{ $option->value }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="col-lg-9">
                <div class="spark_product_page_sort-bar d-flex justify-content-end mb-3 align-items-center">
                    <span class="text-muted me-2" style="font-size: 0.9rem;">Show</span>
                    <select class="form-select form-select-sm w-auto me-3">
                        <option selected>20</option>
                        <option value="1">40</option>
                        <option value="2">60</option>
                    </select>
                    <span class="text-muted me-2" style="font-size: 0.9rem;">Sort by</span>
                    <select class="form-select form-select-sm w-auto">
                        <option selected>Default</option>
                        <option value="1">Price: Low to High</option>
                        <option value="2">Price: High to Low</option>
                        <option value="3">Newest</option>
                    </select>
                </div>

                <div id="product-grid-container" class="row row-cols-lg-4 row-cols-md-3 row-cols-sm-2 row-cols-1 g-3">
                    @forelse($products as $product)
                        @include('front.product._product-card', ['product' => $product])
                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning text-center">
                                <p class="mb-0">No products found in this category.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div id="loading-indicator">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@include('front.include.filter_canvas_mobile')
@endsection

@section('script')
<script>
$(document).ready(function() {
    let currentPage = {{ $products->currentPage() }};
    let hasMorePages = {{ $products->hasMorePages() ? 'true' : 'false' }};
    let isLoading = false;
    const loadingIndicator = $('#loading-indicator');
    const productGrid = $('#product-grid-container');

    function loadMoreProducts() {
        if (isLoading || !hasMorePages) return;

        isLoading = true;
        currentPage++;
        loadingIndicator.show();

        const categoryId = '{{ $category->id }}';

        $.ajax({
            url: "{{ route('products.filter') }}",
            type: 'GET',
            data: {
                page: currentPage,
                category_id: categoryId,
            },
            success: function(response) {
                if (response.html.trim()) {
                    productGrid.append(response.html);
                }
                hasMorePages = response.hasMorePages;
                if (!hasMorePages) {
                    loadingIndicator.hide();
                }
            },
            error: function() {
                console.error('Failed to load more products.');
                currentPage--;
            },
            complete: function() {
                isLoading = false;
                if (hasMorePages) {
                    loadingIndicator.hide();
                }
            }
        });
    }

    $(window).on('scroll', function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 250) {
            loadMoreProducts();
        }
    });
});
</script>
@endsection