@extends('front.master.master')

@section('title', "Search Results for '" . e($searchQuery) . "'")

@section('css')
<style>
    /* Styles are copied from category.blade.php for consistency */
    @media (min-width: 992px) {
        .spark_product_page_filter-sidebar {
            position: sticky; top: 140px; max-height: calc(100vh - 160px);
            overflow-y: auto; padding-right: 15px;
        }
    }
    .spark_product_page_filter-scroll { max-height: 200px; overflow-y: auto; padding-right: 5px; }
    #loading-indicator { display: none; padding: 20px; text-align: center; }
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
                        <li class="breadcrumb-item active" aria-current="page">Search</li>
                    </ol>
                </nav>
                <div class="page_breadcrumbs_text">
                    <h1 class="spark_product_page_heading fs-3 fw-bold text-dark">Search Results for "{{ e($searchQuery) }}"</h1>
                    <p class="spark_product_page_description text-muted mb-4" style="font-size: 0.85rem;">
                        Showing products that match your search query.
                    </p>
                </div>
            </div>
        </div>

        {{-- Mobile Filter Button --}}
        <div class="row d-lg-none mt-3">
            <div class="col-12">
                <button class="btn btn-dark filter-button w-100" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasFilter" aria-controls="offcanvasFilter">
                    <i class="fas fa-filter me-2"></i> Filter Products
                </button>
            </div>
        </div>

        <div class="row mt-4">
            {{-- Desktop Filter Sidebar --}}
            <div class="col-lg-3 d-none d-lg-block">
                <div class="spark_product_page_filter-sidebar">
                    <form id="desktop-filter-form">
                        {{-- Include the reusable filter content --}}
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
                        @include('front.product._product-card', ['product' => $product, 'wishlistProductIds' => $wishlistProductIds])
                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning text-center">
                                <p class="mb-0">No products found matching your search and filters.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div id="loading-indicator">
                    <div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>
                </div>
            </div>
        </div>
    </div>
</main>

{{-- Re-use the same mobile filter canvas --}}
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

    function setFiltersFromUrl() {
        const params = new URLSearchParams(window.location.search);
        if (params.has('sort_by')) { $('.product_page_sort_select').val(params.get('sort_by')); }
        if (params.has('min_price')) { $('input[name="min_price"]').val(params.get('min_price')); }
        if (params.has('max_price')) { $('input[name="max_price"]').val(params.get('max_price')); }
        params.getAll('availability[]').forEach(v => $(`input[name="availability[]"][value="${v}"]`).prop('checked', true));
        for (const [key, value] of params.entries()) {
            const match = key.match(/attributes\[(\d+)\]\[\]/);
            if (match) {
                $(`input[name="attributes[${match[1]}][]"][value="${value}"]`).prop('checked', true);
            }
        }
    }

    function fetchProducts(page = 1, append = false) {
        if (isLoading) return;
        isLoading = true;
        if (!append) { productGrid.html(''); }
        loadingIndicator.show();

        const formId = window.innerWidth >= 992 ? '#desktop-filter-form' : '#mobile-filter-form';
        const filterData = $(formId).serialize();
        const sortData = $('.product_page_sort_select').serialize();
        
        // Always include the original search query in the parameters
        const allParams = filterData + '&' + sortData + '&query={{ urlencode($searchQuery) }}';

        const newUrl = `{{ route('products.search') }}?${allParams}`;
        window.history.pushState({ path: newUrl }, '', newUrl);

        $.ajax({
            url: `{{ route('products.search_filter') }}?${allParams}&page=${page}`,
            type: 'GET',
            success: function(response) {
                if (response.html.trim() === '' && !append) {
                    productGrid.html('<div class="col-12"><div class="alert alert-warning text-center"><p class="mb-0">No products match your filters.</p></div></div>');
                } else {
                    append ? productGrid.append(response.html) : productGrid.html(response.html);
                }
                hasMorePages = response.hasMorePages;
                currentPage = page;
            },
            error: function() {
                productGrid.html('<div class="col-12"><div class="alert alert-danger text-center"><p class="mb-0">An error occurred.</p></div></div>');
            },
            complete: function() {
                isLoading = false;
                loadingIndicator.hide();
            }
        });
    }

    // --- Event Handlers ---
    $('#desktop-filter-form').on('change', '.product-filter-input', function() { fetchProducts(1, false); });
    $('#mobile-filter-form').on('submit', function(e) { e.preventDefault(); fetchProducts(1, false); bootstrap.Offcanvas.getInstance(document.getElementById('offcanvasFilter')).hide(); });
    $('.product_page_sort_select').on('change', function() { fetchProducts(1, false); });
    $(window).on('scroll', function() { if (hasMorePages && !isLoading && $(window).scrollTop() + $(window).height() >= $(document).height() - 300) { fetchProducts(currentPage + 1, true); } });

    // --- Initial Setup ---
    setFiltersFromUrl();
});
</script>
@endsection