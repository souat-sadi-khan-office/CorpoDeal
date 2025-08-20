@if ($category->children->count())
    <li class="{{ $submenu == true ? 'dropdown-submenu' : 'dropdown dropdown-mega-menu' }}">
        @if ($submenu == true)
            <a class="dropdown-item nav-link dropdown-toggler" href="{{ route('slug.handle', $category->slug) }}">
                {!! $category->icon !!}
                <span class="ms-1">{{ $category->name }}</span>
            </a>
        @else    
            <a class="nav-link dropdown-toggle d-none d-lg-block" href="{{ route('slug.handle', $category->slug) }}">
                {!! $category->icon !!}
                <span class="ms-1">{{ $category->name }}</span>
            </a>
            <a class="nav-link dropdown-toggle d-block d-lg-none" role="button" data-bs-toggle="dropdown">
                {!! $category->icon !!}
                <span class="ms-1">{{ $category->name }}</span>
            </a>
        @endif
        
        <ul class="dropdown-menu">
            @foreach ($category->children as $child)
                @include('frontend.layouts.partials.category-item', ['category' => $child, 'submenu' => true])
            @endforeach
        </ul>
    </li>

@else
    <li>
        <a class="dropdown-item" href="{{ route('slug.handle', $category->slug) }}">
            {!! $category->icon !!}
            <span class="ms-1">{{ $category->name }}</span>
        </a>
    </li>
@endif