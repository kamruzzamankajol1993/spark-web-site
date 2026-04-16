@extends('front.master.master')

@section('title')
Choose {{ $category->name }} - PC Builder | {{ $front_ins_name }}
@endsection

@section('body')
<main>
    <section class="py-4 bg-light">
        <div class="spark_container">
            <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 shadow-sm rounded">
                <div>
                    <h4 class="mb-0">Select {{ $category->name }}</h4>
                    <small class="text-muted">Choose the best component for your PC build</small>
                </div>
                <a href="{{ route('pc_builder.index') }}" class="btn btn-outline-dark btn-sm">
                    <i class="fa-solid fa-arrow-left"></i> Back to Builder
                </a>
            </div>

            <div class="row">
                @forelse($products as $product)
                    <div class="col-12 mb-3">
                        <div class="card border-0 shadow-sm p-3">
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center">
                                    @php
                                        $image = $product->images->first()->image_path ?? 'no-image.jpg';
                                    @endphp
                                    <img src="{{ $front_ins_url . 'public/' . $image }}" 
                                         alt="{{ $product->name }}" 
                                         style="width: 80px; height: 80px; object-fit: contain;">
                                </div>

                                <div class="col-md-7">
                                    <h5 class="mb-1" style="font-size: 1.1rem;">{{ $product->name }}</h5>
                                    <div class="text-muted small">
                                        @if($product->brand) <span>Brand: <strong>{{ $product->brand->name }}</strong></span> | @endif
                                        <span>Product ID: {{ $product->id }}</span>
                                    </div>
                                    {{-- এখানে শর্ট ডেসক্রিপশন বা স্পেসিফিকেশন লুপ করতে পারেন --}}
                                </div>

                                <div class="col-md-3 text-end border-start">
                                    <div class="mb-2">
                                        @php
                                            $price = $product->offer_price > 0 ? $product->offer_price : $product->selling_price;
                                        @endphp
                                        <span class="h5 fw-bold text-danger">{{ number_format($price, 0) }}৳</span>
                                    </div>
                                    
                                    {{-- পিসি বিল্ডারে অ্যাড করার ফর্ম --}}
                                    <form action="{{ route('pc_builder.add', $product->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm px-4">
                                            Add to Builder
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">No products available in this category.</p>
                        <a href="{{ route('pc_builder.index') }}" class="btn btn-secondary">Back to Builder</a>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </section>
</main>
@endsection