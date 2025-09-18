@extends('frontend.layouts.app', ['title' => get_settings('all_brand_site_title')])
@section('meta')
    <meta property="og:image:width" content="200">
    <meta property="og:image:height" content="200">
    <meta property="og:site_name" content="{{ get_settings('system_name') }}">
    
    <meta name="title" content="{{ get_settings('all_brand_meta_title') }}">
    <meta name="author" content="{{ get_settings('system_name') }} : {{ route('home') }} : {{ get_settings('system_footer_contact_email') }}">
    <meta name="description" content="{{ get_settings('all_brand_meta_description') }}">	

    <!-- For Open Graph -->
    <meta property="og:url" content="{{ url()->current() }}">	
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ get_settings('all_brand_meta_title') }}">	
    <meta property="og:description" content="{{ get_settings('all_brand_meta_description') }}.">	
    <meta property="og:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">	

    <!-- For Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:creator" content="{{ get_settings('system_name') }}" /> 
    <meta name="twitter:title" content="{{ get_settings('all_brand_meta_title') }}" />
    <meta name="twitter:description" content="{{ get_settings('all_brand_meta_description') }}" />	
    <meta name="twitter:site" content="{{ route('home') }}" />		
    <meta name="twitter:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">
    
    {!! get_settings('all_brand_meta_article_tag') !!}
@endsection

@push('breadcrumb')
    <style>
        @media only screen and (max-width: 991px) {
            .section {
                margin-bottom: 30px !important;
            }
        }
    </style>
    <div class="breadcrumb_section page-title-mini">
        <div class="custom-container">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a title="Go to home page" href="{{ route('home') }}">
                                <i class="linearicons-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            All Brands
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endpush
@section('content')
    <style>
        .card-grid-style-2 .image-box {
            height: 120px; 
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            border-radius: 6px;
            padding: 10px;
            overflow: hidden;
            position: relative;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        /* Logo Image Styling */
        .card-grid-style-2 .image-box img {
            max-height: 80px;
            max-width: 100%;
            object-fit: contain; /* Keeps proportions */
            filter: grayscale(40%); /* Slightly muted for a clean look */
            transition: all 0.3s ease;
        }

        /* Hover Effect */
        .card-grid-style-2:hover .image-box {
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-4px);
        }

        .card-grid-style-2:hover .image-box img {
            filter: grayscale(0%); /* Full color on hover */
            transform: scale(1.05);
        }


    </style>

    <div class="main_content bg_gray py-5">
        <section class="section mb-2" style="padding: 10px 0px;">
            <div class="custom-container">
                <div class="row">
                    @foreach ($brands as $key => $brand)
                        <div class="col-md-2 col-sm-12 col-12">
                            <div class="card-grid-style-2 card-grid-style-2-small hover-up">
                                <div class="image-box">
                                    <a href="{{ route('slug.handle', $brand->slug) }}">
                                        <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}">
                                    </a>
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