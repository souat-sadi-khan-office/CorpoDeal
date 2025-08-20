@if ($categories->isNotEmpty())
    <ul>
        @foreach ($categories as $category)
            <li>
                <a title="Visit {{ $category->name }} Page" class="dropdown-item nav-link nav_item" href="{{ route('slug.handle', $category->slug) }}">
                    {{ $category->name }}
                </a>
                @if ($category->children->isNotEmpty())
                    @include('frontend.layouts.partials.category-dropdown', ['categories' => $category->children])
                @endif  
            </li>
        @endforeach
    </ul>
@endif