@php $selected = $selectedComponents[$cat->id] ?? null; @endphp
<div class="spark_pcbuild_component_row">
    <div class="spark_pcbuild_icon_col">
        <i class="fa-solid {{ $cat->icon ?? 'fa-microchip' }}"></i>
    </div>
    <div class="spark_pcbuild_info_col">
        <div class="spark_pcbuild_category_name">
            {{ $cat->name }} @if($cat->is_required) <span class="text-danger">*</span> @endif
        </div>
        @if($selected)
            <div class="fw-bold text-primary">{{ $selected['name'] }}</div>
        @else
            <div class="spark_pcbuild_placeholder_line"></div>
        @endif
    </div>
    <div class="spark_pcbuild_price_col">
        @if($selected) {{ number_format($selected['price'], 0) }}৳ @endif
    </div>
    <div class="spark_pcbuild_action_col d-flex gap-2 justify-content-end align-items-center">
        @if($selected)
            {{-- SINGLE DELETE BUTTON --}}
            <a href="{{ route('pc_builder.remove', $cat->id) }}" class="text-danger remove-single-component" title="Remove Component" style="font-size: 1.2rem; cursor: pointer;">
                <i class="fa-solid fa-circle-xmark"></i>
            </a>
            {{-- CHANGE BUTTON WITH USER ICON --}}
            <a href="{{ route('pc_builder.choose', $cat->slug) }}" class="btn btn-sm btn-outline-warning d-flex align-items-center gap-1">
                <i class="fa-solid fa-user-pen"></i>
  
            </a>
        @else
            <a href="{{ route('pc_builder.choose', $cat->slug) }}" class="btn spark_pcbuild_btn_choose">Choose</a>
        @endif
    </div>
</div>