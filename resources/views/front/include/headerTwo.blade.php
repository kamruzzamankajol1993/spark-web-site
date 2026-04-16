<!-- Desktop & Tablet Header (Hidden on Mobile) -->
<div class="header-section sticky-top d-none d-lg-block">
    <div class="spark_container container-fluid px-lg-5">
        <header>
            <!-- Top Header Bar -->
            <div class="header-top">
                <div class="d-flex align-items-center justify-content-between">
                    <!-- Logo -->
                    <div class="spark_logo">
                        <a href="index.php"><img src="assets/img/spark-logo.png" alt="Spark Logo"></a>
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

            <!-- Main Header Bar -->
            <div class="header-main">
                <div class="row align-items-center">
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
                            data-bs-target="#offcanvasCart">
                            <i class="fa-solid fa-cart-shopping fa-xl"></i>
                            <span class="icon-badge">0</span>
                        </button>
                        <button class="icon-btn">
                            <i class="fa-solid fa-heart fa-xl"></i>
                            <span class="icon-badge">0</span>
                        </button>
                        <button class="icon-btn" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasLogin">
                            <i class="fa-solid fa-user fa-xl"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Bottom Navigation -->
            <div class="header-bottom">
                <!-- Removed overflow-auto to prevent clipping dropdowns -->
                <nav class="nav nav-link-list d-flex justify-content-between flex-nowrap">
                    <a class="nav-link" href="product_page.php">Laptop</a>
                    <a class="nav-link" href="#">Desktop</a>
                    <a class="nav-link" href="#">Components</a>
                    <div class="dropdown">
                        <a class="nav-link" href="#">Accessories <i class="fa-solid fa-caret-down"></i></a>
                        <ul class="dropdown-menu dropdown-menu-custom">
                            <li class="dropdown-submenu">
                                <a class="dropdown-item d-flex justify-content-between align-items-center"
                                    href="#">Input Devices <i class="fa-solid fa-caret-right ms-2"></i></a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Keyboard</a></li>
                                    <li class="dropdown-submenu">
                                        <a class="dropdown-item d-flex justify-content-between align-items-center"
                                            href="#">Mice <i class="fa-solid fa-caret-right ms-2"></i></a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Wired Mouse</a></li>
                                            <li><a class="dropdown-item" href="#">Wireless Mouse</a></li>
                                            <li><a class="dropdown-item" href="#">Gaming Mouse</a></li>
                                        </ul>
                                    </li>
                                    <li><a class="dropdown-item" href="#">Headsets</a></li>
                                </ul>
                            </li>
                            <li><a class="dropdown-item" href="#">Storage Devices</a></li>
                            <li><a class="dropdown-item" href="#">Webcams</a></li>
                        </ul>
                    </div>
                    <a class="nav-link" href="#">Smartphone</a>
                    <a class="nav-link" href="#">Monitor</a>
                    <a class="nav-link" href="#">Networking</a>
                    <a class="nav-link" href="#">Office Equipments</a>
                    <a class="nav-link" href="#">Gadgets</a>
                    <a class="nav-link" href="#">Cameras</a>
                    <a class="nav-link" href="#">TV</a>
                    <a class="nav-link" href="#">UPS</a>
                    <a class="nav-link" href="#">Security</a>
                    <a class="nav-link" href="#">Gaming</a>
                    <a class="nav-link" href="#">Appliance</a>
                    <a class="nav-link" href="#">Software</a>
                    <a class="nav-link" href="#">Servers</a>
                </nav>
            </div>
        </header>
    </div>
</div>

<!-- Mobile Header (Updated Layout) -->
<header class="d-lg-none header-section sticky-top">
    <nav class="px-3 py-2">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Left: Hamburger -->
            <button class="btn p-0 text-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNav">
                <i class="fa-solid fa-bars fa-xl"></i>
            </button>

            <!-- Middle: Logo -->
            <div class="spark_logo">
                <a href="index.php"><img src="assets/img/spark-logo.png" alt="Spark Logo"></a>
            </div>

            <!-- Right: Cart -->
            <button class="icon-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart">
                <i class="fa-solid fa-cart-shopping fa-lg"></i>
                <span class="icon-badge">0</span>
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
    <a href="{{ route('pc_builder') }}" class="bottom-nav-item">
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

<!-- Mobile Offcanvas Sidebar -->
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
                <li class="list-group-item">
                    <a href="#">Laptop</a>
                </li>
                <li class="list-group-item">
                    <a class="d-flex justify-content-between align-items-center text-decoration-none"
                        data-bs-toggle="collapse" href="#collapseComponentsMobile" role="button" aria-expanded="false"
                        aria-controls="collapseComponentsMobile">
                        <span>Components</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </a>
                    <div class="collapse" id="collapseComponentsMobile">
                        <ul class="list-group list-group-flush ms-3">
                            <li class="list-group-item">
                                <a class="d-flex justify-content-between align-items-center text-decoration-none"
                                    data-bs-toggle="collapse" href="#collapseProcessorsMobile" role="button"
                                    aria-expanded="false" aria-controls="collapseProcessorsMobile">
                                    <span>Processors</span>
                                    <i class="fa-solid fa-chevron-down"></i>
                                </a>
                                <div class="collapse" id="collapseProcessorsMobile">
                                    <ul class="list-group list-group-flush ms-3">
                                        <li class="list-group-item"><a href="#">Intel Core i9</a></li>
                                        <li class="list-group-item"><a href="#">AMD Ryzen 9</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="list-group-item"><a href="#">Motherboards</a></li>
                        </ul>
                    </div>
                </li>
                <li class="list-group-item">
                    <a class="d-flex justify-content-between align-items-center text-decoration-none"
                        data-bs-toggle="collapse" href="#collapseAccessoriesMobile" role="button" aria-expanded="false"
                        aria-controls="collapseAccessoriesMobile">
                        <span>Accessories</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </a>
                    <div class="collapse" id="collapseAccessoriesMobile">
                        <ul class="list-group list-group-flush ms-3">
                            <li class="list-group-item">
                                <a class="d-flex justify-content-between align-items-center text-decoration-none"
                                    data-bs-toggle="collapse" href="#collapseInputDevicesMobile" role="button"
                                    aria-expanded="false" aria-controls="collapseInputDevicesMobile">
                                    <span>Input Devices</span>
                                    <i class="fa-solid fa-chevron-down"></i>
                                </a>
                                <div class="collapse" id="collapseInputDevicesMobile">
                                    <ul class="list-group list-group-flush ms-3">
                                        <li class="list-group-item"><a href="#">Keyboard</a></li>
                                        <li class="list-group-item"><a href="#">Mouse</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="list-group-item"><a href="#">Storage Devices</a></li>
                        </ul>
                    </div>
                </li>
                <li class="list-group-item"><a href="#">Smartphone</a></li>
                <li class="list-group-item"><a href="#">Monitor</a></li>
                <li class="list-group-item"><a href="#">Networking</a></li>
                <li class="list-group-item"><a href="#">Office Equipments</a></li>
                <li class="list-group-item"><a href="#">Gadgets</a></li>
                <li class="list-group-item"><a href="#">Cameras</a></li>
                <li class="list-group-item"><a href="#">TV</a></li>
                <li class="list-group-item"><a href="#">UPS</a></li>
                <li class="list-group-item"><a href="#">Security</a></li>
                <li class="list-group-item"><a href="#">Gaming</a></li>
                <li class="list-group-item"><a href="#">Appliance</a></li>
                <li class="list-group-item"><a href="#">Software</a></li>
                <li class="list-group-item"><a href="#">Servers</a></li>
            </ul>
        </div>
    </div>
</div>

<!-- Desktop Categories Offcanvas -->
<div class="offcanvas offcanvas-start d-none d-lg-block" tabindex="-1" id="offcanvasCategories"
    aria-labelledby="offcanvasCategoriesLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title text-white" id="offcanvasCategoriesLabel">All Categories</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <!-- ADDED h-100 HERE FOR SCROLLING -->
    <div class="offcanvas-body d-flex flex-column h-100">

        <!-- List of Categories - Wrapped in flex-grow-1 for scrolling -->
        <div class="flex-grow-1 overflow-y-auto">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <a href="#">Laptop</a>
                </li>
                <li class="list-group-item">
                    <a href="#">Desktop</a>
                </li>
                <li class="list-group-item">
                    <a class="d-flex justify-content-between align-items-center text-decoration-none"
                        data-bs-toggle="collapse" href="#collapseComponentsDesktop" role="button" aria-expanded="false"
                        aria-controls="collapseComponentsDesktop">
                        <span>Components</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </a>
                    <div class="collapse" id="collapseComponentsDesktop">
                        <ul class="list-group list-group-flush ms-3">
                            <li class="list-group-item">
                                <a class="d-flex justify-content-between align-items-center text-decoration-none"
                                    data-bs-toggle="collapse" href="#collapseProcessorsDesktop" role="button"
                                    aria-expanded="false" aria-controls="collapseProcessorsDesktop">
                                    <span>Processors</span>
                                    <i class="fa-solid fa-chevron-down"></i>
                                </a>
                                <div class="collapse" id="collapseProcessorsDesktop">
                                    <ul class="list-group list-group-flush ms-3">
                                        <li class="list-group-item"><a href="#">Intel Core i9</a></li>
                                        <li class="list-group-item"><a href="#">AMD Ryzen 9</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="list-group-item"><a href="#">Motherboards</a></li>
                        </ul>
                    </div>
                </li>
                <li class="list-group-item">
                    <a class="d-flex justify-content-between align-items-center text-decoration-none"
                        data-bs-toggle="collapse" href="#collapseAccessoriesDesktop" role="button" aria-expanded="false"
                        aria-controls="collapseAccessoriesDesktop">
                        <span>Accessories</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </a>
                    <div class="collapse" id="collapseAccessoriesDesktop">
                        <ul class="list-group list-group-flush ms-3">
                            <li class="list-group-item">
                                <a class="d-flex justify-content-between align-items-center text-decoration-none"
                                    data-bs-toggle="collapse" href="#collapseInputDevicesDesktop" role="button"
                                    aria-expanded="false" aria-controls="collapseInputDevicesDesktop">
                                    <span>Input Devices</span>
                                    <i class="fa-solid fa-chevron-down"></i>
                                </a>
                                <div class="collapse" id="collapseInputDevicesDesktop">
                                    <ul class="list-group list-group-flush ms-3">
                                        <li class="list-group-item"><a href="#">Keyboard</a></li>
                                        <li class="list-group-item"><a href="#">Mouse</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="list-group-item"><a href="#">Storage Devices</a></li>
                        </ul>
                    </div>
                </li>
                <li class="list-group-item"><a href="#">Smartphone</a></li>
                <li class="list-group-item"><a href="#">Monitor</a></li>
                <li class="list-group-item"><a href="#">Networking</a></li>
                <li class="list-group-item"><a href="#">Office Equipments</a></li>
                <li class="list-group-item"><a href="#">Gadgets</a></li>
                <li class="list-group-item"><a href="#">Cameras</a></li>
                <li class="list-group-item"><a href="#">TV</a></li>
                <li class="list-group-item"><a href="#">UPS</a></li>
                <li class="list-group-item"><a href="#">Security</a></li>
                <li class="list-group-item"><a href="#">Gaming</a></li>
                <li class="list-group-item"><a href="#">Appliance</a></li>
                <li class="list-group-item"><a href="#">Software</a></li>
                <li class="list-group-item"><a href="#">Servers</a></li>
                <!-- Duplicated items to test scrolling -->
                <li class="list-group-item"><a href="#">Extra Item 1</a></li>
                <li class="list-group-item"><a href="#">Extra Item 2</a></li>
                <li class="list-group-item"><a href="#">Extra Item 3</a></li>
                <li class="list-group-item"><a href="#">Extra Item 4</a></li>
                <li class="list-group-item"><a href="#">Extra Item 5</a></li>
                <li class="list-group-item"><a href="#">Extra Item 6</a></li>
            </ul>
        </div>
    </div>
</div>

