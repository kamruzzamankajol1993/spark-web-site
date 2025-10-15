<style>
    .search-results-popup {
        position: absolute;
        top: 100%; /* Position it right below the search bar */
        left: 0;
        right: 0;
        z-index: 1050; /* Ensure it's above other content */
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0 0 0.375rem 0.375rem;
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
        max-height: 400px;
        overflow-y: auto;
    }
    .search-results-table {
        margin-bottom: 0; /* Remove default table margin */
    }
    .search-results-table tr {
        transition: background-color 0.2s ease-in-out;
    }
    .search-results-table tr:hover {
        background-color: #f8f9fa; /* Light gray on hover */
    }
</style>

<div class="header-section sticky-top">
    <div class="spark_container">
        <header class="d-none d-lg-block">
            <div class="header-top">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="spark_logo">
                        <a href="{{route('home.index')}}"><img src="{{$front_ins_url.$front_logo_name}}" alt="Spark Logo" class="h-10"></a>
                    </div>
                    <div class="d-flex" style="gap: 0.5rem;">
                        <button class="btn btn-header">
                            <i class="fa-solid fa-gift"></i>
                            <span>Offers</span>
                        </button>
                        <button class="btn btn-header">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                            <span>Tools</span>
                        </button>
                        <button class="btn btn-header">
                            <i class="fa-solid fa-microchip"></i>
                            <span>PC Builder</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="header-main">
                <div class="row align-items-center">
                    <div class="col-7">
                           <div class="search-container position-relative">
                            <div class="input-group">
                                <button class="btn category-button py-2 px-4 d-flex align-items-center" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCategories">
                                    <i class="fa-solid fa-list-ul me-2"></i> All Category
                                </button>
                                {{-- ADDED: ID for JS --}}
                                <input type="text" id="product-search-input" class="form-control form-control-search py-2" placeholder="Search for products..." aria-label="Search" autocomplete="off">
                                <button class="btn search-btn px-4" type="button" id="desktop-search-icon">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                            {{-- ADDED: Results popup --}}
                            <div class="search-results-popup" id="search-results-container" style="display: none;">
            <table class="table table-hover search-results-table">
                <tbody id="desktop-search-tbody">
                    {{-- AJAX results will be inserted here --}}
                </tbody>
            </table>
        </div>
                        </div>
                    </div>

                    <div class="col-5 d-flex justify-content-end" style="gap: 1.5rem;">
                        {{-- REVERTED: Cart icon now opens the offcanvas sidebar --}}
                        <button class="icon-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
                            <i class="fa-solid fa-cart-shopping fa-xl"></i>
                            <span class="icon-badge" id="cart-item-count-desktop">0</span>
                        </button>

                        {{-- Compare Icon --}}
                        <a href="{{ route('compare.index') }}" class="icon-btn">
                            <i class="fa-solid fa-exchange-alt fa-xl"></i>
                            <span class="icon-badge" id="compare-count-desktop">0</span>
                        </a>

                        {{-- Conditional Wishlist Icon --}}
                        @auth
                            <a href="{{ route('wishlist.index') }}" class="icon-btn">
                                <i class="fa-solid fa-heart fa-xl"></i>
                                <span class="icon-badge" id="wishlist-count-desktop">0</span>
                            </a>
                        @else
                            <button class="icon-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasLogin" aria-controls="offcanvasLogin">
                                <i class="fa-solid fa-heart fa-xl"></i>
                                <span class="icon-badge" id="wishlist-count-desktop">0</span>
                            </button>
                        @endauth

                        {{-- Conditional User Icon --}}
                        @auth
                            <a href="{{ route('dashboard.user') }}" class="icon-btn">
                                <i class="fa-solid fa-user fa-xl"></i>
                            </a>
                        @else
                            <button class="icon-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasLogin" aria-controls="offcanvasLogin">
                                <i class="fa-solid fa-user fa-xl"></i>
                            </button>
                        @endauth
                    </div>
                </div>
            </div>

            <div class="header-bottom">
                <nav class="nav nav-pills nav-link-list d-flex justify-content-between flex-nowrap">
                    @foreach($headerCategories->take(16) as $category)
                        @if($category->children->isEmpty())
                            <a class="nav-link" href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a>
                        @else
                            <div class="dropdown">
                                <a class="nav-link" href="{{ route('category.show', $category->slug) }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ $category->name }} <i class="fa-solid fa-caret-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-custom">
                                    <li><a class="dropdown-item" href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a></li>
                                    @foreach($category->children as $child)
                                        @if($child->children->isEmpty())
                                            <li><a class="dropdown-item" href="{{ route('category.show', $child->slug) }}">{{ $child->name }}</a></li>
                                        @else
                                            <li class="dropdown-submenu">
                                                <a class="dropdown-item" href="{{ route('category.show', $child->slug) }}">
                                                    {{ $child->name }} <i class="fa-solid fa-caret-right"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    @foreach($child->children as $grandchild)
                                                        <li><a class="dropdown-item" href="{{ route('category.show', $grandchild->slug) }}">{{ $grandchild->name }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endforeach
                </nav>
            </div>
        </header>
    </div>
</div>


<header class="d-lg-none header-section sticky-top">
    <nav class="header-main px-3">
        <div class="d-flex justify-content-between align-items-center">
            <button class="btn btn-dark py-2 px-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNav" aria-controls="offcanvasNav">
                <i class="fa-solid fa-bars text-white fa-xl"></i>
            </button>
            <a href="{{route('home.index')}}"><img src="{{$front_ins_url.$front_logo_name}}" alt="Spark Logo" class="h-10"></a>
            <div class="d-flex" style="gap: 1.0rem;">
                {{-- REVERTED: Cart icon now opens the offcanvas sidebar --}}
                <button class="icon-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
                    <i class="fa-solid fa-cart-shopping fa-xl"></i>
                    <span class="icon-badge" id="cart-item-count-mobile">0</span>
                </button>
                 {{-- ADD THIS COMPARE BUTTON --}}
    <a href="{{ route('compare.index') }}" class="icon-btn">
        <i class="fa-solid fa-exchange-alt fa-xl"></i>
        <span class="icon-badge" id="compare-count-mobile">0</span>
    </a>
                {{-- Conditional Wishlist Icon --}}
                @auth
                    <a href="{{ route('wishlist.index') }}" class="icon-btn">
                        <i class="fa-solid fa-heart fa-xl"></i>
                        <span class="icon-badge" id="wishlist-count-mobile">0</span>
                    </a>
                @else
                    <button class="icon-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasLogin">
                        <i class="fa-solid fa-heart fa-xl"></i>
                        <span class="icon-badge" id="wishlist-count-mobile">0</span>
                    </button>
                @endauth

                {{-- Conditional User Icon --}}
                @auth
                    <a href="{{ route('dashboard.user') }}" class="icon-btn">
                        <i class="fa-solid fa-user fa-xl"></i>
                    </a>
                @else
                    <button class="icon-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasLogin">
                        <i class="fa-solid fa-user fa-xl"></i>
                    </button>
                @endauth
            </div>
        </div>
    </nav>
</header>

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNav" aria-labelledby="offcanvasNavLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title text-white" id="offcanvasNavLabel">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column h-100">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex gap-2">
                <button class="btn btn-header"><i class="fa-solid fa-gift"></i><span>Offers</span></button>
                <button class="btn btn-header"><i class="fa-solid fa-screwdriver-wrench"></i><span>Tools</span></button>
                <button class="btn btn-header"><i class="fa-solid fa-microchip"></i><span>PC Builder</span></button>
            </div>
        </div>
          {{-- ADDED: Wrapper for positioning --}}
        <div class="search-container position-relative mb-4">
            <div class="input-group">
                {{-- ADDED: ID for JS --}}
                <input type="text" id="mobile-product-search-input" class="form-control form-control-search py-2" placeholder="Search for products..." aria-label="Search" autocomplete="off">
                <button class="btn search-btn px-4" type="button" id="mobile-search-icon"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
            {{-- ADDED: Results popup --}}
             <div class="search-results-popup" id="mobile-search-results-container" style="display: none;">
             <table class="table table-hover search-results-table">
                <tbody id="mobile-search-tbody">
                    {{-- AJAX results will be inserted here --}}
                </tbody>
            </table>
        </div>
        </div>

        <div class="flex-grow-1 overflow-y-auto">
            <ul class="list-group list-group-flush">
                @include('front.include._category-list', ['categories' => $sidebarCategories, 'prefix' => 'mobile'])
            </ul>
        </div>
    </div>
</div>

@include('front.include.sidebar')

@include('front.include.cart_offcanvas')

@include('front.include.login_offcanvas')