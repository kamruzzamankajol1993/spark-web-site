  {{-- Price Filter --}}
                     <div class="spark_product_page_filter-group mb-2">
    <div class="spark_product_page_filter-title" data-bs-toggle="collapse" data-bs-target="#priceCollapse">
        Price Range <i class="fas fa-chevron-down float-end"></i>
    </div>
    <div class="collapse show" id="priceCollapse">
        <div class="spark_product_page_range-slider px-2">
            
            <div class="price-range-wrapper px-2 mb-3 mt-4">
                {{-- Container for the track and sliders --}}
                <div class="range-slider-container position-relative mb-4" style="height: 20px;">
                    
                    {{-- The Actual Background Track Layer --}}
                    <div id="custom-track" style="position: absolute; top: 50%; left: 0; width: 100%; height: 6px; background: #e9ecef; border-radius: 5px; z-index: 1; transform: translateY(-50%);"></div>
                    
                    {{-- Slider 1 (Min) --}}
                    <input type="range" class="form-range product-filter-input" id="min-price-slider" 
                           min="0" max="2000000" value="0" name="min_price" 
                           style="position: absolute; width: 100%; pointer-events: none; appearance: none; background: none; z-index: 20; border:none; top: 33%; transform: translateY(-50%); margin: 0;">
                    
                    {{-- Slider 2 (Max) --}}
                    <input type="range" class="form-range product-filter-input" id="max-price-slider" 
                           min="0" max="2000000" value="2000000" name="max_price" 
                           style="position: absolute; width: 100%; pointer-events: none; appearance: none; background: none; z-index: 10; border:none; top: 33%; transform: translateY(-50%); margin: 0;">
                </div>

                {{-- Price Display Boxes --}}
                <div class="d-flex justify-content-between align-items-center gap-2">
                    <div class="price-box border flex-grow-1 text-center py-2 bg-light" style="border-radius: 4px; font-size: 14px;">
                        <span id="min-price-display">0</span>
                    </div>
                    <div class="price-box border flex-grow-1 text-center py-2 bg-light" style="border-radius: 4px; font-size: 14px;">
                        <span id="max-price-display">2000000</span>
                    </div>
                </div>
            </div>

            <style>
                /* Hide default track for all browsers */
                .product-filter-input::-webkit-slider-runnable-track { background: none; border: none; height: 0; }
                .product-filter-input::-moz-range-track { background: none; border: none; height: 0; }
                
                /* Styled Dots (Thumbs) aligned to center of track */
                .product-filter-input::-webkit-slider-thumb {
                    pointer-events: auto;
                    width: 18px;
                    height: 18px;
                    background-color: #ef4a23; 
                    border: 2px solid #fff;
                    border-radius: 50%;
                    cursor: pointer;
                    appearance: none;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.4);
                    /* Vertical centering logic */
                    position: relative;
                    z-index: 30;
                }

                .product-filter-input::-moz-range-thumb {
                    pointer-events: auto;
                    width: 18px;
                    height: 18px;
                    background-color: #ef4a23;
                    border: 2px solid #fff;
                    border-radius: 50%;
                    cursor: pointer;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.4);
                    z-index: 30;
                }

                .product-filter-input:focus { outline: none; box-shadow: none; }
            </style>

            <script>
            $(document).ready(function() {
                const minSlider = $('#min-price-slider');
                const maxSlider = $('#max-price-slider');
                const customTrack = $('#custom-track');
                const color = "#ef4a23"; 

                function updateSlider() {
                    let minVal = parseInt(minSlider.val());
                    let maxVal = parseInt(maxSlider.val());

                    if (minVal > maxVal) {
                        minSlider.val(maxVal);
                        minVal = maxVal;
                    }

                    const minPercent = (minVal / minSlider.attr('max')) * 100;
                    const maxPercent = (maxVal / maxSlider.attr('max')) * 100;
                    
                    // Update the track fill color
                    customTrack.css('background', `linear-gradient(to right, #e9ecef ${minPercent}%, ${color} ${minPercent}%, ${color} ${maxPercent}%, #e9ecef ${maxPercent}%)`);

                    $('#min-price-display').text(minVal.toLocaleString());
                    $('#max-price-display').text(maxVal.toLocaleString());
                }

                minSlider.on('input', updateSlider);
                maxSlider.on('input', updateSlider);
                updateSlider(); 
            });
            </script>
        </div>
    </div>
</div>
                        {{-- Availability Filter --}}
                        <div class="spark_product_page_filter-group mb-2">
                            <div class="spark_product_page_filter-title" data-bs-toggle="collapse" data-bs-target="#availabilityCollapse">
                                Availability <i class="fas fa-chevron-down float-end"></i>
                            </div>
                            <div class="collapse show" id="availabilityCollapse">
                                <div class="form-check"><input class="form-check-input product-filter-input" name="availability[]" type="checkbox" value="in_stock" id="instock"><label class="form-check-label" for="instock">In Stock</label></div>
                                {{-- Add other availability options if needed in the future --}}
                            </div>
                        </div>

                        {{-- Dynamic Attribute Filters --}}
                        @if(isset($filterableAttributes) && $filterableAttributes->isNotEmpty())
                            @foreach($filterableAttributes as $attribute)
                                <div class="spark_product_page_filter-group mb-2">
                                    <div class="spark_product_page_filter-title" data-bs-toggle="collapse" data-bs-target="#collapse-{{ \Illuminate\Support\Str::slug($attribute->name) }}">
                                        {{ $attribute->name }} <i class="fas fa-chevron-down float-end"></i>
                                    </div>
                                    <div class="collapse show" id="collapse-{{ \Illuminate\Support\Str::slug($attribute->name) }}">
                                        <div class="spark_product_page_filter-scroll">
                                            @foreach($attribute->options as $option)
                                                <div class="form-check">
                                                    <input class="form-check-input product-filter-input" name="attributes[{{ $attribute->id }}][]" type="checkbox" value="{{ $option->value }}" id="option-{{ $option->id }}">
                                                    <label class="form-check-label" for="option-{{ $option->id }}">{{ $option->value }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif