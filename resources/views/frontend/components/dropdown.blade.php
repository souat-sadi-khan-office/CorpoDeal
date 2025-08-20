<li>
    <a title="Visit {{ $page->title }} Page" class="dropdown-item nav-link nav_item" href="{{ route('slug.handle', $page->slug) }}">
        {{ $page->title }}
    </a>
    @if ($page->children->isNotEmpty())
        <div class="dropdown-menu">
            <ul>
                @foreach ($page->children as $child)
                    @include('partials.dropdown', ['page' => $child])
                @endforeach
            </ul>
        </div>
    @endif
</li>
