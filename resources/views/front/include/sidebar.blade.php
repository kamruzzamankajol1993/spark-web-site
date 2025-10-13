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
                {{-- Use the partial to render the dynamic list for the desktop sidebar --}}
                @include('front.include._category-list', ['categories' => $sidebarCategories, 'prefix' => 'desktop'])
            </ul>
        </div>
    </div>
</div>