@if (count($homeCategories) > 0)
<div class="col-md-12">
    @foreach ($homeCategories as $homeCategory)
        <section class="section pb-0">
            <div class="row {{$homeCategory->is_right ?'flex-md-row-reverse':''}}">
                <div class="col-xl-3 d-none d-xl-block">
                    <div class="sale-banner">
                        <a class="hover_effect1" href="{{ route('slug.handle', $homeCategory->category->slug) }}">
                            <img src="{{ asset($homeCategory->picture) }}" alt="{{ $homeCategory->name }}">
                        </a>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="row">
                        <div class="col-12">
                            <div class="heading_tab_header">
                                <div class="heading_s2">
                                    <h4>{{ $homeCategory->name }}</h4>
                                </div>
                                <div class="view_all">
                                    <a href="{{ route('slug.handle', $homeCategory->category->slug) }}" class="text_default"><i class="linearicons-power"></i> <span>View All</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="carousel_slider owl-carousel owl-theme dot_style1" data-loop="true" data-nav="false" data-dots="false" data-margin="20" data-responsive='{"0":{"items": "1"}, "481":{"items": "2"}, "768":{"items": "3"}, "991":{"items": "4"}}'>
                                @foreach($homeCategory->category->product as $index => $product)
                                    <div class="item">
                                        @include('frontend.components.product_main', ['tag' => 'discount_price', 'listing' => 'section_wise'])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endforeach
</div>
@endif
