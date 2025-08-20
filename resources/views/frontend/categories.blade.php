@extends('frontend.layouts.app', ['title' => get_settings('all_categories_site_title')])
@section('meta')
<meta property="og:image:width" content="200">
    <meta property="og:image:height" content="200">
    <meta property="og:site_name" content="{{ get_settings('system_name') }}">
    
    <meta name="title" content="{{ get_settings('all_categories_meta_title') }}">
    <meta name="author" content="{{ get_settings('system_name') }} : {{ route('home') }} : {{ get_settings('system_footer_contact_email') }}">
    <meta name="description" content="{{ get_settings('all_categories_meta_description') }}">	

    <!-- For Open Graph -->
    <meta property="og:url" content="{{ url()->current() }}">	
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ get_settings('all_categories_meta_title') }}">	
    <meta property="og:description" content="{{ get_settings('all_categories_meta_description') }}.">	
    <meta property="og:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">	

    <!-- For Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:creator" content="{{ get_settings('system_name') }}" /> 
    <meta name="twitter:title" content="{{ get_settings('all_categories_meta_title') }}" />
    <meta name="twitter:description" content="{{ get_settings('all_categories_meta_description') }}" />	
    <meta name="twitter:site" content="{{ route('home') }}" />		
    <meta name="twitter:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">
    
    {!! get_settings('all_categories_meta_article_tag') !!}
@endsection


@push('breadcrumb')
    <div class="breadcrumb_section page-title-mini">
        <div class="custom-container">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">
                                <i class="linearicons-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            All Categories
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endpush
@section('content')
    <style>
        .card-grid-style-2-small {
            padding-bottom: 20px;
        }

        .card-grid-style-2 {
            border: 1px solid #D5DFE4;
            background: #fff;
            padding: 15px;
            display: block;
            margin-bottom: 24px;
            border-radius: 4px;
            width: calc(100% - 1px);
            min-height: 166px;
            transition-duration: 0.2s;
            overflow: hidden;
            position: relative;
        }

        .hover-up {
            transition: all 0.25s cubic-bezier(0.02, 0.01, 0.47, 1);
        }

        .card-grid-style-2 .image-box {
            text-align: center;
        }
        
        .card-grid-style-2 .image-box img {
            min-width: 135px;
            max-width: 135px;
        }

        .card-grid-style-2 .info-main {
            width: 100%;
        }
        
        .card-grid-style-2 .info-main h6 {
            text-align: center;
        }

        .list-links-disc {
            display: inline-block;
            width: 100%;
            padding: 15px 0px 10px 0px;
        }

        .list-links-disc li {
            margin: 0px 0px 6px 0px;
            list-style: none;
            transition: 0.2s;
        }

        .list-links-disc li a {
            display: block;
            padding: 0px 0px 0px 10px;
            background: url("{{ asset('frontend/assets/images/arrow-small.svg') }}") no-repeat left center;
        }

        .hover-up:hover {
            transform: scale(1.1); /* Increase the size by 10% */
            transition: transform 0.3s ease;
        }

        .dropdown-selector {
            position: absolute;
            width: 100%;
            max-width: 250px;
            z-index: 1;
        }

        @media only screen and (max-width: 991px) {
            .dropdown-selector {
                position: relative;
                width: auto; /* Auto width for smaller devices */
                z-index: 1;
            }

            .hover-up:hover {
                transform: none;
                transition: none;
            }
        }

        .card-grid-style-2 {
            transition: border 0.2s, box-shadow 0.2s;
        }

        .card-grid-style-2:hover {
            border: 1px solid var(--primary-color);
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); /* Add shadow effect */
        }


        .treeview a {
            text-decoration: none;
            display: block;
            padding: 5px;
        }

        .treeview ul {
            padding-left: 5px;
            display: none;
        }

        .treeview .toggle {
            cursor: pointer;
        }

        .list-links-disc li a:hover {
            color: var(--primary-color);
        }

    </style>
    <div class="main_content bg_gray py-5">
        <section class="section mb-2" style="padding: 10px 0px;">
            <div class="custom-container">
                <div class="row">
                    @foreach ($categories as $key => $category)
                        <div class="col-md-2 col-sm-12 col-12">
                            <div class="card-grid-style-2 card-grid-style-2-small hover-up">
                                <div class="image-box">
                                    <a href="{{ route('slug.handle', $category->slug) }}">
                                        <img src="{{ asset($category->photo) }}" alt="{{ $category->name }}">
                                    </a>
                                </div>
                                <div class="info-main">
                                    @if ($category->children->count())
                                        <div class="treeview">
                                            <span class="toggle trigger">
                                                ⮮
                                                {{ $category->name }}</span>
                                            <ul class="list-links-disc">
                                                @foreach ($category->children as $child)
                                                    @include('frontend.layouts.partials.treeview', ['category' => $child])
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <ul style="padding: 0px;" class="list-links-disc">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('slug.handle', $category->slug) }}">
                                                    <span class="ms-1">{{ $category->name }}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
@push('scripts')
    <script src="{{ asset('backend/assets/js/parsley.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/toastr.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/pages/login.js') }}"></script>
    <script>

document.addEventListener("DOMContentLoaded", function () {
    let treeViews = document.querySelectorAll(".treeview");

    treeViews.forEach(function (treeview) {
        let toggleBtn = treeview.querySelector(".toggle");
        let submenu = treeview.querySelector("ul");

        treeview.addEventListener("mouseenter", function () {
            if (submenu) {
                submenu.style.display = "block";
                submenu.style.transition = "none"; // Remove animation
            }
            if (toggleBtn) {
                toggleBtn.textContent = toggleBtn.textContent.replace("⮮", "⮬");
                if (toggleBtn.classList.contains("trigger")) {
                    treeview.parentElement.parentElement.classList.add("dropdown-selector");
                }
            }
        });

        treeview.addEventListener("mouseleave", function () {
            if (submenu) {
                submenu.style.display = "none";
                submenu.style.transition = "none"; // Remove animation
            }
            if (toggleBtn) {
                toggleBtn.textContent = toggleBtn.textContent.replace("⮬", "⮮");
                if (toggleBtn.classList.contains("trigger")) {
                    treeview.parentElement.parentElement.classList.remove("dropdown-selector");
                }
            }
        });
    });
});


        $(document).ready(function () {
            $('.toggle').click(function () {
                $(this).siblings('ul').slideToggle();

                var currentText = $(this).text();
                var newText = currentText.startsWith('⮬ ') 
                    ? '⮮ ' + currentText.slice(2) 
                    : '⮬ ' + currentText.slice(2);
                $(this).text(newText);

                if($(this).hasClass('trigger')) {
                    $(this).parent().parent().parent().toggleClass('dropdown-selector');
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            const catIcons = document.querySelectorAll(".category-icon i");
            catIcons.forEach(icon => {
                const randomClass = `random-color-${Math.floor(Math.random() * 15) + 1}`;
                icon.classList.add(randomClass);
            });
            const catButtonIcons = document.querySelectorAll(".categories_box a i");
            catButtonIcons.forEach(icon => {
                const randomClass = `random-color-${Math.floor(Math.random() * 15) + 1}`;
                icon.classList.add(randomClass);
            });
        });
    </script>
@endpush