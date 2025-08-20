<div class="product_slider flash_deal_slider owl-carousel owl-theme nav_style3"
     data-loop="true" data-dots="false" data-nav="false" data-margin="30"
     data-autoplay='true'
     data-responsive='{"0":{"items": "1"}, "650":{"items": "2"}, "1199":{"items": "2"}}'>

    @if ($flashDeals)
        @foreach ($flashDeals as $deal)
            @foreach ($deal['product_details'] as $product)
                <div class="item">
                    <div class="deal_wrap">
                        <div class="product_img" style="margin-right:10px">
                            <a href="{{ route('slug.handle', ['slug' => $product['slug']]) }}">
                                <img src="{{ asset($product['thumb_image'] ?? 'frontend/assets/images/default-image.png') }}"
                                    alt="{{ $product['name'] }}">
                            </a>
                        </div>
                        <div class="deal_content">
                            <div class="product_info">
                                <h5 class="product_title">
                                    <a href="{{ route('slug.handle', ['slug' => $product['slug']]) }}">
                                        {!! limit_str($product['name'],4) !!}
                                    </a>
                                </h5>
                                <div class="product_price">
                                    <span class="price">{{ $product['discounted_price'] }}</span>
                                    <del>{{ $product['unit_price'] }}</del>
                                </div>
                            </div>
                            <div class="deal_progress">
                                <span class="stock-sold">Already Sold: <strong>{{ $product['number_of_sale'] }}</strong></span>
                                <span class="stock-available">Available: <strong>{{ $product['current_stock'] }}</strong></span>
                                <div class="progress">
                                    @php
                                        $totalSold = $product['number_of_sale'];
                                        $totalAvailable = $product['current_stock'] + $totalSold;
                                        $percentage = $totalAvailable > 0 ? ($totalSold / $totalAvailable) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $percentage }}"
                                        aria-valuemin="0" aria-valuemax="100" style="width:{{ $percentage }}%">
                                        {{ round($percentage) }}%
                                    </div>
                                </div>
                            </div>
                            <div class="countdown_time countdown_style4 mb-4" data-time="{{ $deal['end_time'] }}">
                                <div class="countdown_box">
                                    <div class="countdown-wrap">
                                        <span class="countdown days">00</span>
                                        <span class="cd_text">Days</span>
                                    </div>
                                </div>
                                <div class="countdown_box">
                                    <div class="countdown-wrap">
                                        <span class="countdown hours">00</span>
                                        <span class="cd_text">Hours</span>
                                    </div>
                                </div>
                                <div class="countdown_box">
                                    <div class="countdown-wrap">
                                        <span class="countdown minutes">00</span>
                                        <span class="cd_text">Minutes</span>
                                    </div>
                                </div>
                                <div class="countdown_box">
                                    <div class="countdown-wrap">
                                        <span class="countdown seconds">00</span>
                                        <span class="cd_text">Seconds</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
    @endif
</div>

@if (!$flashDeals)
    <p>No flash deals available at this time.</p>
@endif
