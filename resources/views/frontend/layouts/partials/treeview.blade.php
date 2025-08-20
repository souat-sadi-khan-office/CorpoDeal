<li>
    @if ($category->children->count())
        <span style="white-space:break-spaces" class="toggle">⮮ {{ $category->name }}</span>
        <ul class="list-links-disc">
            @foreach ($category->children as $child)
                @include('frontend.layouts.partials.treeview', ['category' => $child])
            @endforeach
        </ul>
    @else
        <a class="dropdown-item" href="{{ route('slug.handle', $category->slug) }}">
            <span style="white-space:break-spaces" class="ms-1">{{ $category->name }}</span>
        </a>
    @endif
</li>