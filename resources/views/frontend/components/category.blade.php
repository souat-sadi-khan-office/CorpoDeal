<div class="d-flex justify-content-between align-items-center mb-2">
    <h5 class="modal-title fs-4">All Categories</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>

<div class="h-100" data-simplebar="">
    <ul class="category-nav navbar-nav navbar-nav-offcanvac">
        @foreach ($categories as $category)
            @if ($category->children->count())
                @include('frontend.layouts.partials.category-item', ['category' => $category, 'submenu' => false])
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('slug.handle', $category->slug) }}">
                        {!! $category->icon !!}
                        <span class="ms-1">{{ $category->name }}</span>
                    </a>
                </li>
            @endif
        @endforeach        
    </ul>
</div>

<script>
    document.querySelectorAll(".dropdown-menu a.dropdown-toggle").forEach(function (e) {
        e.addEventListener("click", function (e) {
            if (!this.nextElementSibling.classList.contains("show")) {
                this.closest(".dropdown-menu")
                    .querySelectorAll(".show")
                    .forEach(function (e) {
                        e.classList.remove("show");
                    });
            } else {
                this.closest(".dropdown-menu")
                    .querySelectorAll(".show")
                    .forEach(function (e) {
                        e.classList.add("show");
                    });
            }
            this.nextElementSibling.classList.toggle("show");
            const t = this.closest("li.nav-item.dropdown.show");
            t &&
                t.addEventListener("hidden.bs.dropdown", function (e) {
                    document.querySelectorAll(".dropdown-submenu .show").forEach(function (e) {
                        e.classList.remove("show");
                    });
                }),
                e.stopPropagation();
        });
    });
</script>