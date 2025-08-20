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
    .categories_box a {
        min-height: 0px;
    }
</style>
<div class="main_content bg_gray py-5">

    <div class="custom-container">
        @foreach ($brands as $brand)
            <section class="section" style="padding: 10px 0px;">
                <div class="">
                    <div class="row">
                        <div class="col-12">
                            <div class="cat_overlap radius_all_5">
                                <div class="row align-items-center">
                                    <div class="{{ count($brand->types->where('status', 1)) > 0 ? 'col-lg-3 col-md-4' : 'col-md-12' }}">
                                        <div class="text-center text-md-start">
                                            <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" title="{{ $brand->name }} Logo" width="150">
                                            {{-- <h6 class="mt-3">{{ $brand->name }}</h6> --}}
                                            <br>
                                            <a href="{{ route('slug.handle', $brand->slug) }}" class="mt-3 btn btn-line-fill btn-sm">View All Products</a>
                                        </div>
                                    </div>
                                    @if ($brandTypes = $brand->types->where('status', 1))
                                        <div class="col-lg-9 col-md-8">
                                            <div class="cat_slider mt-4 mt-md-0 carousel_slider owl-carousel owl-theme nav_style5" data-loop="false" data-dots="false" data-nav="false" data-margin="30" data-responsive='{"0":{"items": "1"}, "380":{"items": "2"}, "991":{"items": "3"}, "1199":{"items": "4"}}'>
                                                @foreach ($brandTypes as $brandType)
                                                    <div class="item">
                                                        <div class="categories_box">
                                                            <a href="{{ route('slug.handle', $brand->slug) }}">
                                                                {!! $brandType->icon !!}
                                                                <span>{{ $brandType->name }}</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endforeach
    </div>

</div>
@endsection
@push('scripts')
    <script src="{{ asset('backend/assets/js/parsley.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/toastr.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/pages/login.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const icons = document.querySelectorAll(".nav-link i");
            icons.forEach(icon => {
                const randomClass = `random-color-${Math.floor(Math.random() * 15) + 1}`;
                icon.classList.add(randomClass);
            });
            const iconsMenu = document.querySelectorAll(".categories_box i");
            iconsMenu.forEach(icon => {
                const randomClass = `random-color-${Math.floor(Math.random() * 15) + 1}`;
                icon.classList.add(randomClass);
            });
        });
    </script>
@endpush