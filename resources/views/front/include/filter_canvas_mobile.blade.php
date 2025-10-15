<div class="offcanvas offcanvas-start bg-light" tabindex="-1" id="offcanvasFilter" aria-labelledby="offcanvasFilterLabel">
    <div class="offcanvas-header bg-white shadow-sm">
        <h5 class="offcanvas-title fw-bold" id="offcanvasFilterLabel">Filter Products</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column p-0">
        <form id="mobile-filter-form" class="flex-grow-1 d-flex flex-column">
            <div class="p-3 flex-grow-1 overflow-auto">
                {{-- Price Range --}}
                <div class="spark_product_page_filter-group mb-4">
                    <div class="spark_product_page_filter-title" data-bs-toggle="collapse" data-bs-target="#priceCollapseMobile">Price Range <i class="fas fa-chevron-down float-end"></i></div>
                    <div class="collapse show" id="priceCollapseMobile">
                        <div class="spark_product_page_range-slider px-2">
                            <input type="range" min="1000" max="500000" value="1000" name="min_price">
                            <input type="range" min="1000" max="500000" value="500000" name="max_price">
                            <div class="d-flex justify-content-between mt-2">
                                <span class="badge bg-danger">1,000৳</span>
                                <span class="badge bg-danger">500,000৳</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Availability --}}
                <div class="spark_product_page_filter-group mb-4">
                    <div class="spark_product_page_filter-title" data-bs-toggle="collapse" data-bs-target="#availabilityCollapseMobile">Availability <i class="fas fa-chevron-down float-end"></i></div>
                    <div class="collapse show" id="availabilityCollapseMobile">
                        <div class="form-check"><input class="form-check-input" name="availability[]" type="checkbox" value="in_stock" id="instockMobile"><label class="form-check-label" for="instockMobile">In Stock</label></div>
                    </div>
                </div>

                {{-- Dynamic Attributes --}}
                @if(isset($filterableAttributes) && $filterableAttributes->isNotEmpty())
                    @foreach($filterableAttributes as $attribute)
                        <div class="spark_product_page_filter-group mb-4">
                            <div class="spark_product_page_filter-title" data-bs-toggle="collapse" data-bs-target="#collapse-{{ \Illuminate\Support\Str::slug($attribute->name) }}-mobile">{{ $attribute->name }} <i class="fas fa-chevron-down float-end"></i></div>
                            <div class="collapse show" id="collapse-{{ \Illuminate\Support\Str::slug($attribute->name) }}-mobile">
                                <div class="spark_product_page_filter-scroll">
                                    @foreach($attribute->options as $option)
                                        <div class="form-check">
                                            <input class="form-check-input" name="attributes[{{ $attribute->id }}][]" type="checkbox" value="{{ $option->value }}" id="option-{{ $option->id }}-mobile">
                                            <label class="form-check-label" for="option-{{ $option->id }}-mobile">{{ $option->value }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="p-3 bg-white border-top">
                 <button type="submit" class="btn btn-dark w-100">Apply Filters</button>
            </div>
        </form>
    </div>
</div>