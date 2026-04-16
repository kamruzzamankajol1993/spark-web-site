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
                            
                            <div class="price-range-wrapper px-2 mb-3 mt-4">
                                {{-- Mobile Slider Container --}}
                                <div class="range-slider-container position-relative mb-4" style="height: 20px;">
                                    
                                    {{-- Background Track Layer --}}
                                    <div id="mobile-custom-track" style="position: absolute; top: 50%; left: 0; width: 100%; height: 6px; background: #e9ecef; border-radius: 5px; z-index: 1; transform: translateY(-50%);"></div>
                                    
                                    {{-- Slider 1 (Min) --}}
                                    <input type="range" class="form-range mobile-product-filter-input" id="mobile-min-price-slider" 
                                           min="0" max="2000000" value="1000" name="min_price" 
                                           style="position: absolute; width: 100%; pointer-events: none; appearance: none; background: none; z-index: 20; border:none; top: 34%; transform: translateY(-50%); margin: 0;">
                                    
                                    {{-- Slider 2 (Max) --}}
                                    <input type="range" class="form-range mobile-product-filter-input" id="mobile-max-price-slider" 
                                           min="0" max="2000000" value="2000000" name="max_price" 
                                           style="position: absolute; width: 100%; pointer-events: none; appearance: none; background: none; z-index: 10; border:none; top: 34%; transform: translateY(-50%); margin: 0;">
                                </div>

                                {{-- Price Display Boxes --}}
                                <div class="d-flex justify-content-between align-items-center gap-2">
                                    <div class="price-box border flex-grow-1 text-center py-2 bg-light" style="border-radius: 4px; font-size: 14px;">
                                        <span id="mobile-min-price-display">1,000</span>
                                    </div>
                                    <div class="price-box border flex-grow-1 text-center py-2 bg-light" style="border-radius: 4px; font-size: 14px;">
                                        <span id="mobile-max-price-display">2000000</span>
                                    </div>
                                </div>
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

                {{-- Dynamic Attributes (rest of your original logic) --}}
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

<style>
    /* Hide default track for mobile sliders */
    .mobile-product-filter-input::-webkit-slider-runnable-track { background: none; border: none; height: 0; }
    .mobile-product-filter-input::-moz-range-track { background: none; border: none; height: 0; }
    
    /* Styled Dots (Thumbs) for mobile */
    .mobile-product-filter-input::-webkit-slider-thumb {
        pointer-events: auto;
        width: 22px; /* Slightly larger for easier touch control */
        height: 22px;
        background-color: #ef4a23; 
        border: 2px solid #fff;
        border-radius: 50%;
        cursor: pointer;
        appearance: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.4);
        position: relative;
        z-index: 30;
    }

    .mobile-product-filter-input::-moz-range-thumb {
        pointer-events: auto;
        width: 22px;
        height: 22px;
        background-color: #ef4a23;
        border: 2px solid #fff;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 1px 3px rgba(0,0,0,0.4);
        z-index: 30;
    }

    .mobile-product-filter-input:focus { outline: none; box-shadow: none; }
</style>

<script>
$(document).ready(function() {
    const mobMinSlider = $('#mobile-min-price-slider');
    const mobMaxSlider = $('#mobile-max-price-slider');
    const mobCustomTrack = $('#mobile-custom-track');
    const mobColor = "#ef4a23"; 

    function updateMobileSlider() {
        let minVal = parseInt(mobMinSlider.val());
        let maxVal = parseInt(mobMaxSlider.val());

        // Prevents handles from crossing
        if (minVal > maxVal) {
            mobMinSlider.val(maxVal);
            minVal = maxVal;
        }

        const maxLimit = mobMinSlider.attr('max');
        const minPercent = (minVal / maxLimit) * 100;
        const maxPercent = (maxVal / maxLimit) * 100;
        
        // Fills the orange color between handles
        mobCustomTrack.css('background', `linear-gradient(to right, #e9ecef ${minPercent}%, ${mobColor} ${minPercent}%, ${mobColor} ${maxPercent}%, #e9ecef ${maxPercent}%)`);

        $('#mobile-min-price-display').text(minVal.toLocaleString());
        $('#mobile-max-price-display').text(maxVal.toLocaleString());
    }

    mobMinSlider.on('input', updateMobileSlider);
    mobMaxSlider.on('input', updateMobileSlider);
    
    // Initial load
    updateMobileSlider(); 
});
</script>