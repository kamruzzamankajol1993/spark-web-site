  {{-- Price Filter --}}
                        <div class="spark_product_page_filter-group mb-2">
                            <div class="spark_product_page_filter-title" data-bs-toggle="collapse" data-bs-target="#priceCollapse">
                                Price Range <i class="fas fa-chevron-down float-end"></i>
                            </div>
                            <div class="collapse show" id="priceCollapse">
                                <div class="spark_product_page_range-slider px-2">
                                    <input type="range" class="product-filter-input" min="1000" max="500000" value="1000" name="min_price">
                                    <input type="range" class="product-filter-input" min="1000" max="500000" value="500000" name="max_price">
                                    <div class="d-flex justify-content-between mt-2">
                                        <span class="badge bg-danger">1,000৳</span>
                                        <span class="badge bg-danger">500,000৳</span>
                                    </div>
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