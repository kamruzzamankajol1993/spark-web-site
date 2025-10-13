<!-- Desktop & Tablet Header (Hidden on Mobile) -->
<div class="header-section sticky-top">
    <div class="spark_container">
        <header class="d-none d-lg-block">
            <!-- Top Header Bar -->
            <div class="header-top">
                <div class="">
                    <div class="d-flex align-items-center justify-content-between">
                        <!-- Logo -->
                        <div class="spark_logo">
                            <a href="{{route('home.index')}}"><img src="{{$front_ins_url.$front_logo_name}}" alt="Spark Logo" class="h-10"></a>

                        </div>
                        <!-- Buttons -->
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
            </div>

            <!-- Main Header Bar -->
            <div class="header-main">
                <div class="">
                    <div class="row align-items-center">
                        <!-- All Category Button for Desktop -->
                        <div class="col-8">
                            <div class="input-group">
                                <button class="btn category-button py-2 px-4 d-flex align-items-center" type="button"
                                    data-bs-toggle="offcanvas" data-bs-target="#offcanvasCategories">
                                    <i class="fa-solid fa-list-ul me-2"></i> All Category
                                </button>
                                <input type="text" class="form-control form-control-search py-2"
                                    placeholder="Search for products..." aria-label="Search">
                                <button class="btn search-btn px-4" type="button">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Icons -->
                        <div class="col-4 d-flex justify-content-end" style="gap: 1.5rem;">
                            <button class="icon-btn" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
                                <i class="fa-solid fa-cart-shopping fa-xl"></i>
                                <span class="icon-badge">0</span>
                            </button>
                            <button class="icon-btn">
                                <i class="fa-solid fa-heart fa-xl"></i>
                                <span class="icon-badge">0</span>
                            </button>
                            <button class="icon-btn" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasLogin" aria-controls="offcanvasLogin">
                                <i class="fa-solid fa-user fa-xl"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Navigation -->
            <div class="header-bottom">
                <div class="">
                    <nav class="nav nav-pills nav-link-list d-flex justify-content-between flex-nowrap">
                        {{-- Loop through the first 16 top-level categories --}}
                          @foreach($headerCategories->take(16) as $category)
                            @if($category->children->isEmpty())
                                <a class="nav-link" href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a>
                            @else
                                <div class="dropdown">
                                    <a class="nav-link" href="{{ route('category.show', $category->slug) }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ $category->name }} <i class="fa-solid fa-caret-down"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-custom">
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
            </div>
        </header>
    </div>
</div>


<!-- Mobile Header (Hidden on Desktop & Tablet) -->
<header class="d-lg-none header-section sticky-top">
    <nav class="header-main px-3">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Hamburger menu button to toggle offcanvas -->
            <button class="btn btn-dark py-2 px-3" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNav" aria-controls="offcanvasNav">
                <i class="fa-solid fa-bars text-white fa-xl"></i>
            </button>
            <!-- Logo -->
            <div class="spark_logo">
              <a href="{{route('home.index')}}"><img src="{{$front_ins_url.$front_logo_name}}" alt="Spark Logo" class="h-10"></a>
            </div>
            <!-- Icons -->
            <div class="d-flex" style="gap: 0.5rem;">
                <button class="icon-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart"
                    aria-controls="offcanvasCart">
                    <i class="fa-solid fa-cart-shopping fa-xl"></i>
                    <span class="icon-badge">0</span>
                </button>
                <button class="icon-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasLogin"
                    aria-controls="offcanvasLogin">
                    <i class="fa-solid fa-user fa-xl"></i>
                </button>
            </div>
        </div>
    </nav>
</header>


<!-- Mobile Offcanvas Menu (appears from the left) -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNav" aria-labelledby="offcanvasNavLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title text-white" id="offcanvasNavLabel">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <!-- ADDED h-100 HERE FOR SCROLLING -->
    <div class="offcanvas-body d-flex flex-column h-100">
        <!-- Replicating the full header content inside the offcanvas -->
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex gap-2">
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
        <div class="input-group mb-4">
            <input type="text" class="form-control form-control-search py-2" placeholder="Search for products..."
                aria-label="Search">
            <button class="btn search-btn px-4" type="button">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>

        <!-- List of Categories - Wrapped in flex-grow-1 for scrolling -->
        <div class="flex-grow-1 overflow-y-auto">
            <ul class="list-group list-group-flush">
                               @include('front.include._category-list', ['categories' => $sidebarCategories, 'prefix' => 'mobile'])

            </ul>
        </div>
    </div>
</div>


<!-- Desktop Offcanvas Menu for All Categories (appears from the left) -->
@include('front.include.sidebar')


<!-- Cart Offcanvas (Appears from the right) -->
@include('front.include.cart_offcanvas')

<!-- Login Offcanvas (Appears from the right) -->
@include('front.include.login_offcanvas')