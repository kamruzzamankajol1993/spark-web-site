@extends('front.master.master')

@section('title')
PC Builder | {{$front_ins_name}}
@endsection

@section('css')
<style>
    .spark_pcbuild_btn_clear {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background-color: transparent;
        color: #6c757d;
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 5px 15px;
        min-width: 90px;
        height: 58px; /* Matches the height of your summary boxes */
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .spark_pcbuild_btn_clear i {
        font-size: 1.2rem;
        margin-bottom: 2px;
    }

    .spark_pcbuild_btn_clear span {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .spark_pcbuild_btn_clear:hover {
        background-color: #dc3545;
        border-color: #dc3545;
        color: #ffffff;
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.2);
    }

    /* Alignment fix for the stats area */
    .spark_pcbuild_stats_area {
        display: flex;
        gap: 12px;
        align-items: center;
    }
</style>
@endsection

@section('body')
<main>
    <section class="section">
        <div class="spark_container">
            <div class="spark_pcbuild_main_wrapper">
                <div class="spark_pcbuild_builder_header">
                    <div class="spark_pcbuild_title_area">
                        <h1>PC Builder - Build Your Own Computer - Spark Tech</h1>
                    </div>

                    <div class="spark_pcbuild_stats_area">
                        {{-- CLEAR ALL WITH SWEET ALERT --}}
                        @if(count($selectedComponents) > 0)
        <button class="spark_pcbuild_btn_clear clear-all-builder" type="button">
            <i class="fa-solid fa-trash-can"></i>
            <span>Clear All</span>
        </button>
    @endif

                        <a href="{{ route('pc_builder.pdf') }}" target="_blank" class="spark_pcbuild_btn_pdf">
                            <i class="fa-solid fa-file-pdf"></i>
                            <span>Get Quote (PDF)</span>
                        </a>

                        @php
                            $totalPrice = collect($selectedComponents)->sum('price');
                            $totalItems = count($selectedComponents);
                        @endphp

                        <div class="spark_pcbuild_summary_box">
                            <span class="spark_pcbuild_total_price">{{ number_format($totalPrice, 0) }}৳</span>
                            <span class="spark_pcbuild_total_items">{{ $totalItems }} Items</span>
                        </div>
                    </div>
                </div>

                <div class="spark_pcbuild_components_list">
                    @php
                        $desktopCategory = \App\Models\Category::where('slug', 'desktop')->first();
                        $coreComponents = $categories->where('parent_id', optional($desktopCategory)->id);
                        $peripherals = $categories->where('parent_id', '!=', optional($desktopCategory)->id);
                    @endphp

                    {{-- Core Components --}}
                    <div class="spark_pcbuild_section_bar">Core Components</div>
                    @foreach($coreComponents as $cat)
                        @include('front.pc_builder._component_row', ['cat' => $cat])
                    @endforeach

                    {{-- Peripherals --}}
                    <div class="spark_pcbuild_section_bar">Peripherals & Others</div>
                    @foreach($peripherals as $cat)
                        @include('front.pc_builder._component_row', ['cat' => $cat])
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // --- SWEET ALERT FOR CLEAR ALL ---
        $('.clear-all-builder').on('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "All selected components will be removed from your list!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4a23',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, clear all!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('pc_builder.clear') }}";
                }
            })
        });

        // --- SWEET ALERT FOR SINGLE REMOVE ---
        $(document).on('click', '.remove-single-component', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            Swal.fire({
                title: 'Remove item?',
                text: "This component will be removed from your build.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'Keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            })
        });
    });
</script>
@endsection