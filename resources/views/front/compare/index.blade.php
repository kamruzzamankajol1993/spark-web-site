@extends('front.master.master')

@section('title', 'Compare Products')

@section('body')
<main class="spark_container my-5">
    {{-- Header with Conditional "Clear All" Button --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0">Compare Products</h1>
        @if($products->isNotEmpty())
            <button class="btn btn-danger" id="clear-compare-list">
                <i class="fas fa-trash me-2"></i> Clear All
            </button>
        @endif
    </div>

    @if($products->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <tbody>
                    {{-- Product Image, Name, and Remove Button --}}
                    <tr>
                        <th class="text-start p-3" style="width: 15%;">Product</th>
                        @foreach ($products as $product)
                            <td class="p-3">
                                <a href="{{ route('product.show', $product->slug) }}">
                                    <img src="{{ $product->images->isNotEmpty() ? $front_ins_url . 'public/' . $product->images->first()->image_path : 'https://placehold.co/120x120?text=N/A' }}" alt="{{ $product->name }}" style="max-width: 120px; max-height: 120px; object-fit: contain;">
                                </a>
                                <h6 class="mb-0 mt-2 fw-bold">{{ $product->name }}</h6>
                                <button class="btn btn-sm btn-outline-danger mt-2 compare-btn" data-product-id="{{ $product->id }}">
                                    <i class="fas fa-times"></i> Remove
                                </button>
                            </td>
                        @endforeach
                    </tr>

                    {{-- Price --}}
                    <tr>
                        <th class="text-start p-3">Price</th>
                        @foreach ($products as $product)
                            <td class="p-3">
                                @if($product->offer_price > 0 && $product->offer_price < $product->selling_price)
                                    <span class="text-danger fw-bold fs-5">৳{{ number_format($product->offer_price) }}</span>
                                    <del class="text-muted ms-2">৳{{ number_format($product->selling_price) }}</del>
                                @else
                                    <span class="fw-bold fs-5">৳{{ number_format($product->selling_price) }}</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    {{-- THE DEDICATED "BRAND" ROW HAS BEEN REMOVED --}}

                    {{-- Dynamically Generated Specifications (This will now include Brand) --}}
                    @php
                        // This code gets all unique attributes (like "Color", "RAM", and "Brand")
                        // from all the products being compared.
                        $allAttributes = $products->flatMap->attributeValues->unique('attribute_id')->pluck('attribute');
                    @endphp

                    @foreach ($allAttributes as $attribute)
                        <tr>
                            <th class="text-start p-3">{{ $attribute->name }}</th>
                            @foreach ($products as $product)
                                {{-- For each product, it finds the value for the current attribute --}}
                                <td class="p-3">{{ $product->attributeValues->where('attribute_id', $attribute->id)->first()->value ?? '—' }}</td>
                            @endforeach
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    @else
        {{-- Message to show when the compare list is empty --}}
        <div class="alert alert-warning text-center">
            <p class="mb-0">There are no products in your compare list.</p>
            <a href="{{ route('home.index') }}" class="btn btn-dark mt-3">Continue Shopping</a>
        </div>
    @endif
</main>
@endsection