@foreach($categories as $category)
    <li class="list-group-item">
        {{-- If the category has children, create a collapsible item --}}
        @if($category->children->isNotEmpty())
            <a class="d-flex justify-content-between align-items-center text-decoration-none"
               data-bs-toggle="collapse" href="#collapse-{{ $category->slug }}-{{ $prefix }}" role="button" aria-expanded="false"
               aria-controls="collapse-{{ $category->slug }}-{{ $prefix }}">
                <span>{{ $category->name }}</span>
                <i class="fa-solid fa-chevron-down"></i>
            </a>
            <div class="collapse" id="collapse-{{ $category->slug }}-{{ $prefix }}">
                <ul class="list-group list-group-flush ms-3">
                    {{-- This is the recursive part: include this file again for the children --}}
                    @include('front.include._category-list', ['categories' => $category->children, 'prefix' => $prefix])
                </ul>
            </div>
        @else
            {{-- If there are no children, it's a simple link --}}
            <a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a>
        @endif
    </li>
@endforeach