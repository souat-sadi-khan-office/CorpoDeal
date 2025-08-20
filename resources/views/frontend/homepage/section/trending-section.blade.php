<div class="col-md-12">
    <section class="section pb-0">
        <div class="row">
            <div class="col-12">
                <div class="heading_tab_header">
                    <div class="heading_s2">
                        <h4>Trending products</h4>
                    </div>
                    <div class="view_all">
                        <a href="{{ route('search', ['sort' => 'popularity']) }}" class="text_default">
                            <i class="linearicons-power"></i> 
                            <span>View All</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="trending-carousel carousel_slider owl-carousel owl-theme dot_style1" data-loop="true" data-nav="false" data-dots="false" data-margin="20" data-responsive='{"0":{"items": "1"}, "481":{"items": "2"}, "768":{"items": "3"}, "991":{"items": "5"}}'>
                    @foreach($products as $index => $product)
                        <div class="item">
                            @include('frontend.components.product_main', ['tag' => 'discount_price', 'listing' => 'section_wise'])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>