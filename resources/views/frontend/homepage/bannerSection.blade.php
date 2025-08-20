<!-- START SECTION BANNER -->
@if (count($banners) && isset($banners['main']) && count($banners['main']))
    <div class="mt-4 staggered-animation-wrap">
        <div class="custom-container">
            <div class="row">
                <div class="col-lg-{{ isset($banners['main_sidebar']) && count($banners['main_sidebar']) > 0 ? 9 : 12 }}">
                    <div class="banner_section shop_el_slider">
                        <div id="carouselExampleControls" class="carousel slide carousel-fade light_arrow carousel_style2" data-bs-ride="carousel" data-mouse-drag="true" data-nav="true">
                            <div class="carousel-inner">
                                @foreach ($banners['main'] as $index => $carousel)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }} background_bg" data-img-src="{{ asset($carousel->image) }}">
                                        <div class="banner_slide_content banner_content_inner">
                                            <div class="col-lg-7 col-10">
                                                <div class="banner_content3 overflow-hidden">
                                                    {{-- <h5 class="mb-3 staggered-animation font-weight-light text-white" data-animation="slideInLeft" data-animation-delay="0.5s" style="z-index: 2">
                                                        {{ @$carousel->header_title }}</h5>
                                                    <h2 class="staggered-animation text-white" data-animation="slideInLeft" data-animation-delay="1s" style="z-index: 2">
                                                        {{ @$carousel->name }}
                                                    </h2>
                                                    <h4 class="staggered-animation mb-4 product-price" data-animation="slideInLeft" data-animation-delay="1.2s">
                                                        <span class="banner-price" style="z-index: 2">{{ @$carousel->old_offer }}</span><del>{{ @$carousel->new_offer }}</del>
                                                    </h4>
                                                    @if ($carousel->link)
                                                        <a class="btn btn-fill-out btn-radius staggered-animation text-uppercase"
                                                        href="{{ $carousel->link }}" data-animation="slideInLeft"
                                                        data-animation-delay="1.5s"style="z-index: 2">
                                                            Shop Now
                                                        </a>
                                                    @endif --}}
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="overlay"></div>
                                    </div>
                                @endforeach
                            </div>
                            <ol class="carousel-indicators indicators_style3">
                                @if ( isset($banners['main']) && count($banners['main']) >= 0)
                                    @for ($i = 0; $i < count($banners['main']); $i++)
                                        <li data-bs-target="#carouselExampleControls" class="{{ $i == 0 ? 'active' : '' }}" data-bs-slide-to="{{ $i }}"></li>
                                    @endfor
                                @endif
                            </ol>

                            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-bs-slide="prev">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-bs-slide="next">
                                <i class="fas fa-arrow-right"></i>    
                            </a>
                        </div>
                    </div>
                </div>
                @if (isset($banners['main_sidebar']) && !$banners['main_sidebar']->isEmpty())
                    @php
                        $main_sidebar = $banners['main_sidebar']->shuffle();
                    @endphp
                    <div class="col-lg-3 mmt-3">
                        <div class="row">
                            @if (isset($main_sidebar[0]))
                                <div class="col-md-12 mb-3 col-6">
                                    <div class="shop_banner2 el_banner1" style="background-image: url('{{ asset($main_sidebar[0]->image) }}');">
                                        <a href="{{ $main_sidebar[0]->link }}" class="hover_effect1">
                                            
                                        </a>
                                    </div>
                                </div>
                            @endif
                            @if (isset($main_sidebar[1]))
                                <div class="col-md-12 col-6">
                                    <div class="shop_banner2 el_banner2" style="background-image: url('{{ asset($main_sidebar[1]->image) }}');">
                                        <a href="{{ $main_sidebar[1]->link }}" class="hover_effect1">
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif
<!-- END SECTION BANNER -->

@push('styles')
<style>
    .carousel-item {
        position: relative;
    }

    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgb(0 0 0 / 7%);
    }

    .banner_slide_content {
        position: relative;
        z-index: 2;
    }
</style>
@endpush
