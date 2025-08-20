<div class="row">
    <div class="col-12">
        <div class="heading_tab_header">
            <div class="heading_s2">
                <h4>Featured Products</h4>
            </div>
            <div class="view_all">
                <a href="{{ route('search', ['sort' => 'featured']) }}" class="text_default">
                    <span>View All</span>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="featured-carousel carousel_slider product_list owl-carousel owl-theme nav_style5" data-nav="false" data-dots="false" data-loop="true" data-margin="20" data-responsive='{"0":{"items": "1"}, "380":{"items": "1"}, "640":{"items": "1"}, "991":{"items": "1"}}'>
            <div class="item">
                @foreach($products as $index => $product)
                    @include('frontend.components.product_main', ['tag' => 'discount_price', 'listing' => 'short'])
            
                    @if(($index + 1) % 3 == 0)
                        </div><div class="item">
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>