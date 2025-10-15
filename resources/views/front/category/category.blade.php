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

        {{-- Mobile Filter Button --}}
        <div class="row d-lg-none mt-3">
            <div class="col-12">
                <button class="btn btn-dark filter-button w-100" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasFilter" aria-controls="offcanvasFilter">
                    <i class="fas fa-filter me-2"></i> Filter Products
                </button>
            </div>
        </div>

        <div class="row mt-4">
            {{-- Desktop Filter Sidebar --}}
            <div class="col-lg-3 d-none d-lg-block">
                <div class="spark_product_page_filter-sidebar">
                    <form id="desktop-filter-form">
                      @include('front.include._filter_sidebar_content')
                    </form>
                </div>
            </div>

            {{-- Product Grid --}}
            <div class="col-lg-9">
                <div class="spark_product_page_sort-bar d-flex justify-content-end mb-3 align-items-center">
                    <span class="text-muted me-2" style="font-size: 0.9rem;">Sort by</span>
                    <select class="form-select form-select-sm w-auto product_page_sort_select" name="sort_by">
                        <option value="default" selected>Default</option>
                        <option value="price_asc">Price: Low to High</option>
                        <option value="price_desc">Price: High to Low</option>
                        <option value="newest">Newest</option>
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
    let currentPage = 1;
    let hasMorePages = {{ $products->hasMorePages() ? 'true' : 'false' }};
    let isLoading = false;
    const loadingIndicator = $('#loading-indicator');
    const productGrid = $('#product-grid-container');

    /**
     * Reads the current URL's query parameters and sets the filter
     * inputs to match. This makes the UI consistent on page reload.
     */
    function setFiltersFromUrl() {
        const params = new URLSearchParams(window.location.search);

        // Set sort dropdown
        if (params.has('sort_by')) {
            $('.product_page_sort_select').val(params.get('sort_by'));
        }

        // Set price range sliders
        if (params.has('min_price')) {
            $('input[name="min_price"]').val(params.get('min_price'));
        }
        if (params.has('max_price')) {
            $('input[name="max_price"]').val(params.get('max_price'));
        }

        // Check availability boxes
        params.getAll('availability[]').forEach(value => {
            $(`input[name="availability[]"][value="${value}"]`).prop('checked', true);
        });

        // Check dynamic attribute boxes
        for (const [key, value] of params.entries()) {
            const match = key.match(/attributes\[(\d+)\]\[\]/);
            if (match) {
                const attributeId = match[1];
                // Use quotes around the value to handle special characters
                $(`input[name="attributes[${attributeId}][]"][value="${value}"]`).prop('checked', true);
            }
        }
    }

    /**
     * Fetches products via AJAX based on the current filter settings.
     * Also updates the browser's URL to reflect the new filter state.
     */
    function fetchProducts(page = 1, append = false) {
        if (isLoading) return;
        isLoading = true;
        if (!append) {
             productGrid.html(''); // Clear grid for new filter
        }
        loadingIndicator.show();

        const formId = window.innerWidth >= 992 ? '#desktop-filter-form' : '#mobile-filter-form';
        const filterData = $(formId).serialize();
        const sortData = $('.product_page_sort_select').serialize();
        const allParams = filterData + '&' + sortData;

        // --- NEW: Update browser URL without reloading the page ---
        const newUrl = window.location.pathname + '?' + allParams;
        window.history.pushState({ path: newUrl }, '', newUrl);
        // --- End New ---

        $.ajax({
            url: "{{ route('products.filter') }}",
            type: 'GET',
            data: allParams + '&page=' + page + '&category_id={{ $category->id }}',
            success: function(response) {
                if (response.html.trim() === '' && !append) {
                    productGrid.html('<div class="col-12"><div class="alert alert-warning text-center"><p class="mb-0">No products match your filters.</p></div></div>');
                } else if (!append) {
                    productGrid.html(response.html);
                } else {
                    productGrid.append(response.html);
                }
                hasMorePages = response.hasMorePages;
                currentPage = page;
            },
            error: function() {
                console.error('Failed to load products.');
                 productGrid.html('<div class="col-12"><div class="alert alert-danger text-center"><p class="mb-0">An error occurred while filtering products.</p></div></div>');
            },
            complete: function() {
                isLoading = false;
                loadingIndicator.hide();
            }
        });
    }

    // --- Event Handlers ---
    $('#desktop-filter-form').on('change', '.product-filter-input', function() {
        fetchProducts(1, false);
    });

    $('#mobile-filter-form').on('submit', function(e) {
        e.preventDefault();
        fetchProducts(1, false);
        var offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('offcanvasFilter'));
        offcanvas.hide();
    });

    $('.product_page_sort_select').on('change', function() {
        fetchProducts(1, false);
    });

    // Infinite scroll
    $(window).on('scroll', function() {
        if (!hasMorePages || isLoading) return;
        // Check if user is near the bottom of the page
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 300) {
            fetchProducts(currentPage + 1, true);
        }
    });

    // --- Initial Setup ---
    // On page load, set the filter controls to match the URL parameters
    setFiltersFromUrl();
});
</script>
@endsection