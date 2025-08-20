<div class="row">
    <div class="col-md-12">
        <ul class="pagination mt-3 justify-content-center pagination_style1">
            @if ($products->onFirstPage())
                <li class="page-item disabled"><span class="page-link">«</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $products->previousPageUrl() }}">«</a></li>
            @endif

            @php
                $end = $products->lastPage();
                $current = $products->currentPage();
            @endphp

            @if ($products->lastPage() >= 9)
                {{-- First 4 pages --}}
                @for ($i = 1; $i <= 4; $i++)
                    <li class="page-item {{ $i === $current ? 'active' : '' }}">
                        <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor

                {{-- Ellipsis if there is a gap --}}
                @if ($current > 5)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif

                {{-- Middle Pages (only show when needed) --}}
                @if ($current > 4 && $current < $products->lastPage() - 3)
                    <li class="page-item">
                        <a class="page-link" href="{{ $products->url($current) }}">{{ $current }}</a>
                    </li>
                @endif

                {{-- Ellipsis before last 4 pages --}}
                @if ($current < $products->lastPage() - 3)
                        <li class="page-item disabled"><span class="page-link">.....</span></li>
                @endif

                {{-- Last 4 pages --}}
                @for ($i = max($products->lastPage() - 3, 5); $i <= $products->lastPage(); $i++)
                    <li class="page-item {{ $i === $current ? 'active' : '' }}">
                        <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor
            @else
                {{-- Show all pages if less than 9 --}}
                @for ($i = 1; $i <= $products->lastPage(); $i++)
                    <li class="page-item {{ $i === $current ? 'active' : '' }}">
                        <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor
            @endif

            @if ($products->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $products->nextPageUrl() }}"> »</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">»</span></li>
            @endif
        </ul>
    </div>
</div>
