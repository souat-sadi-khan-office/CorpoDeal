<div class="featured-products carousel_slider owl-carousel owl-theme" data-loop="true" data-autoplay="true" data-dots="false" data-margin="20" data-responsive='{"0":{"items": "1"}, "481":{"items": "2"}, "768":{"items": "3"}, "991":{"items": "5"}}'>
    @foreach ($products as $product)
        <div class="item">
            @include('frontend.components.product_main', ['listing' => 'section_wise'])
        </div>
    @endforeach
</div>