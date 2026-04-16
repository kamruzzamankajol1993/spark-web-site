{{-- পুরাতন হেডার থেকে সার্চ এবং পপআপের CSS --}}
<style>
    .search-results-popup {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1050;
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0 0 0.375rem 0.375rem;
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
        max-height: 400px;
        overflow-y: auto;
    }
    .search-results-table {
        margin-bottom: 0;
    }
    .search-results-table tr {
        transition: background-color 0.2s ease-in-out;
    }
    .search-results-table tr:hover {
        background-color: #f8f9fa;
    }
</style>

<div class="header-section sticky-top d-none d-lg-block">
    <div class="spark_container container-fluid px-lg-5">
        <header>
            <div class="header-top">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="spark_logo">
                        <a href="{{route('home.index')}}">
                            <img src="{{$front_ins_url.$front_logo_name}}" alt="Logo"> {{-- --}}
                        </a>
                    </div>
                    <div class="d-flex" style="gap: 0.5rem;">
                        
                       <a href="{{ route('pc_builder.index') }}" class="btn btn-header">
                    <i class="fa-solid fa-microchip"></i>
                    <span>PC Builder</span>
                </a>
                    </div>
                </div>
            </div>

            <div class="header-main">
                <div class="row align-items-center">
                    <div class="col-8">
                        {{-- AJAX সার্চ কন্টেইনার --}}
                        <div class="search-container position-relative">
                            <div class="input-group">
                                <button class="btn category-button py-2 px-4 d-flex align-items-center" type="button"
                                    data-bs-toggle="offcanvas" data-bs-target="#offcanvasCategories">
                                    <i class="fa-solid fa-list-ul me-2"></i> All Category
                                </button>
                                <input type="text" id="product-search-input" class="form-control form-control-search py-2"
                                    placeholder="Search for products..." autocomplete="off"> {{-- --}}
                                <button class="btn search-btn px-4" type="button" id="desktop-search-icon">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                            <div class="search-results-popup" id="search-results-container" style="display: none;">
                                <table class="table table-hover search-results-table">
                                    <tbody id="desktop-search-tbody"></tbody> {{-- --}}
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-4 d-flex justify-content-end" style="gap: 1.5rem;">
                        <button class="icon-btn" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart">
                            <i class="fa-solid fa-cart-shopping fa-xl"></i>
                            <span class="icon-badge" id="cart-item-count-desktop">0</span> {{-- --}}
                        </button>

                        @auth {{-- --}}
                            <a href="{{ route('wishlist.index') }}" class="icon-btn">
                                <i class="fa-solid fa-heart fa-xl"></i>
                                <span class="icon-badge" id="wishlist-count-desktop">0</span>
                            </a>
                        @else
                            <button class="icon-btn" data-bs-toggle="offcanvas" data-bs-target="#offcanvasLogin">
                                <i class="fa-solid fa-heart fa-xl"></i>
                                <span class="icon-badge">0</span>
                            </button>
                        @endauth
                        
                        <button class="icon-btn" data-bs-toggle="offcanvas" data-bs-target="@auth # @else #offcanvasLogin @endauth">
                            <a href="@auth {{ route('dashboard.user') }} @else # @endauth" class="text-black">
                                <i class="fa-solid fa-user fa-xl"></i>
                            </a>
                        </button>
                    </div>
                </div>
            </div>

            <div class="header-bottom">
                <nav class="nav nav-link-list d-flex justify-content-between flex-nowrap">
                    @foreach($headerCategories->take(16) as $category) {{-- --}}
                        <div class="dropdown">
                            <a class="nav-link" href="{{ route('category.show', $category->slug) }}">
                                {{ $category->name }} @if($category->children->isNotEmpty()) <i class="fa-solid fa-caret-down"></i> @endif
                            </a>
                            @if($category->children->isNotEmpty())
                                <ul class="dropdown-menu dropdown-menu-custom">
                                    @foreach($category->children as $child)
                                        <li><a class="dropdown-item" href="{{ route('category.show', $child->slug) }}">{{ $child->name }}</a></li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </nav>
            </div>
        </header>
    </div>
</div>

<header class="d-lg-none header-section sticky-top">
    <nav class="px-3 py-2">
        <div class="d-flex justify-content-between align-items-center">
            <button class="btn p-0 text-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNav">
                <i class="fa-solid fa-bars fa-xl"></i>
            </button>
            <div class="spark_logo">
                <a href="{{route('home.index')}}"><img src="{{$front_ins_url.$front_logo_name}}" alt="Logo"></a>
            </div>
            <button class="icon-btn" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart">
                <i class="fa-solid fa-cart-shopping fa-lg"></i>
                <span class="icon-badge" id="cart-item-count-mobile">0</span>
            </button>
        </div>
    </nav>
</header>

<div class="mobile-bottom-nav d-lg-none">
    {{-- মোবাইল সার্চ বাটন --}}
    <button class="bottom-nav-item" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSearchMobile">
        <i class="fa-solid fa-magnifying-glass"></i>
        <span>Search</span>
    </button>
    
    {{-- পিসি বিল্ডার লিঙ্ক --}}
    <a href="{{ route('pc_builder.index') }}" class="bottom-nav-item">
        <i class="fa-solid fa-microchip"></i>
        <span>PC Builder</span>
    </a>
    
    {{-- ডাইনামিক উইশলিস্ট: লগইন চেক সহ --}}
    @auth
        <a href="{{ route('wishlist.index') }}" class="bottom-nav-item">
            <i class="fa-solid fa-heart"></i>
            <span class="position-relative">
                Wishlist
                <span class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle" id="wishlist-count-mobile">0</span>
            </span>
        </a>
    @else
        <button class="bottom-nav-item" data-bs-toggle="offcanvas" data-bs-target="#offcanvasLogin">
            <i class="fa-solid fa-heart"></i>
            <span>Wishlist</span>
        </button>
    @endauth

    {{-- ডাইনামিক অ্যাকাউন্ট: লগইন থাকলে ড্যাশবোর্ড, না থাকলে লগইন অফক্যানভাস --}}
    @auth
        <a href="{{ route('dashboard.user') }}" class="bottom-nav-item">
            <i class="fa-solid fa-circle-user"></i>
            <span>Account</span>
        </a>
    @else
        <button class="bottom-nav-item" data-bs-toggle="offcanvas" data-bs-target="#offcanvasLogin">
            <i class="fa-solid fa-circle-user"></i>
            <span>Account</span>
        </button>
    @endauth
</div>

<div class="offcanvas offcanvas-top offcanvas-top-search d-lg-none" tabindex="-1" id="offcanvasSearchMobile">
    <div class="offcanvas-body">
        <div class="search-container position-relative w-100">
            <div class="input-group">
                {{-- ID: mobile-product-search-input (master.blade.php এর AJAX স্ক্রিপ্টের জন্য) --}}
                <input type="text" id="mobile-product-search-input" class="form-control border-secondary py-2" placeholder="Search for products..." autofocus autocomplete="off">
                <button class="btn btn-dark px-3" type="button" id="mobile-search-icon">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">
                    <i class="fa-solid fa-x"></i>
                </button>
            </div>
            
            {{-- AJAX সার্চ রেজাল্ট দেখানোর টেবিল কন্টেইনার --}}
            <div class="search-results-popup w-100" id="mobile-search-results-container" style="display: none; left: 0;">
                <table class="table table-hover search-results-table">
                    <tbody id="mobile-search-tbody">
                        {{-- রেজাল্ট এখানে লোড হবে --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNav">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title text-white">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column h-100">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex gap-2">
                
  <a href="{{ route('pc_builder.index') }}" class="btn btn-header">
                    <i class="fa-solid fa-microchip"></i>
                    <span>PC Builder</span>
                </a>
            </div>
        </div>

        {{-- মোবাইল AJAX সার্চ বার --}}
        <div class="search-container position-relative mb-4">
            <div class="input-group">
                <input type="text" id="mobile-product-search-input" class="form-control" placeholder="Search products..." autocomplete="off">
                <button class="btn search-btn" id="mobile-search-icon"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
            <div class="search-results-popup" id="mobile-search-results-container" style="display: none;">
                <table class="table table-hover">
                    <tbody id="mobile-search-tbody"></tbody>
                </table>
            </div>
        </div>

        {{-- ডাইনামিক ক্যাটাগরি লিস্ট (রিকার্সিভ) --}}
        <div class="flex-grow-1 overflow-y-auto">
            <ul class="list-group list-group-flush">
                @include('front.include._category-list', ['categories' => $sidebarCategories, 'prefix' => 'mobile']) {{-- --}}
            </ul>
        </div>
    </div>
</div>

{{-- বাকি অফক্যানভাসগুলো --}}
@include('front.include.sidebar')
@include('front.include.cart_offcanvas')
@include('front.include.login_offcanvas')