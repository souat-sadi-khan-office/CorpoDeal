@extends('frontend.layouts.app', ['title' => get_settings('home_site_title')])
@section('meta')
<meta property="og:image:width" content="200">
    <meta property="og:image:height" content="200">
    <meta property="og:site_name" content="{{ get_settings('system_name') }}">
    
    <meta name="title" content="{{ get_settings('home_meta_title') }}">
    <meta name="author" content="{{ get_settings('system_name') }} : {{ route('home') }} : {{ get_settings('system_footer_contact_email') }}">
    <meta name="description" content="{{ get_settings('home_meta_description') }}">	

    <!-- For Open Graph -->
    <meta property="og:url" content="{{ url()->current() }}">	
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ get_settings('home_meta_title') }}">	
    <meta property="og:description" content="{{ get_settings('home_meta_description') }}.">	
    <meta property="og:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">	

    <!-- For Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:creator" content="{{ get_settings('system_name') }}" /> 
    <meta name="twitter:title" content="{{ get_settings('home_meta_title') }}" />
    <meta name="twitter:description" content="{{ get_settings('home_meta_description') }}" />	
    <meta name="twitter:site" content="{{ route('home') }}" />		
    <meta name="twitter:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">
    
    {!! get_settings('home_meta_article_tag') !!}
@endsection
@section('content')

@include('frontend.homepage.bannerSection', ['banners' => $banners])

<div class="main_content mmt-3">
    <div class="custom-container">
        <div class="row">
            <div class="col-12">
                <div class="section pb-0">
                    <div class="cat_overlap">
                        <div class="row align-items-center">
                            <div class="col-lg-3 col-md-4">
                                <div class="text-center text-md-start">
                                    <h4>Featured Categories</h4>
                                    <p class="mb-2">There are many variations of products.</p>
                                    <a href="{{ route('categories') }}" class="btn btn-line-fill btn-sm">View All</a>
                                </div>
                            </div>
                            <div class="col-lg-9 col-md-8">
                                <div class="cat_slider mt-4 mt-md-0 carousel_slider owl-carousel owl-theme nav_style5" data-loop="true" data-dots="false" data-nav="false" data-margin="30" data-responsive='{"0":{"items": "1"}, "380":{"items": "2"}, "991":{"items": "4"}, "1336":{"items": "5"}}'>
                                    @foreach ($featuredCategory as $feaCategory)
                                        <div class="item h-100">
                                            <div class="categories_box">
                                                <a href="{{ route('slug.handle', $feaCategory->slug) }}">
                                                    <img src="{{ asset($feaCategory->photo) }}" alt="{{ $feaCategory->name }} Image">
                                                    <span>{{ $feaCategory->name }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="home-page-root"></div>
        </div>
    </div>
</div>

<input type="hidden" id="sliderSectionActive" value="{{ homepage_setting('sliderSection') }}">
<input type="hidden" id="midBannerSection" value="{{ homepage_setting('midBanner') }}">
<input type="hidden" id="dealOfTheDay" value="{{ homepage_setting('dealOfTheDay') }}">
<input type="hidden" id="trendingSection" value="{{ homepage_setting('trending') }}">
<input type="hidden" id="brandSection" value="{{ homepage_setting('brands') }}">
<input type="hidden" id="popularAndFeaturedSection" value="{{ homepage_setting('popularANDfeatured') }}">
@endsection

@push('scripts')
<script src="{{ asset('frontend/assets/js/pages/home-page.js') }}"></script>
@endpush