@if (isset($listing) && $listing != 'product_details_short')
    <div class="product_wrap {{ $listing != 'short' ? 'card-product' : '' }} {{ isset($listing) && $listing == 'main' ? 'min-height' : '' }}">
        @isset($tag)
            @if ($tag == 'discount_price' && isset($product['discount_type']))
                <span class="pr_flash bg-success">
                    {{ $product['discount_type'] == 'amount' ? format_price(convert_price($product['discount'])) : $product['discount'] . '%' }} Off
                </span>
            @endif

            @if ($tag == 'hot_badge')
                @if ($product['ratingCount'] > 5 && $product['averageRating'] > 80)
                    <span class="pr_flash bg-danger">Hot</span>
                @else
                    <span class="pr_flash">New</span>
                @endif
            @endif
        @endisset

        <div class="product_img {{ isset($listing) && $listing == 'section_wise' ? 'normal-card' : 'short_product' }}">
            <a href="{{ route('slug.handle', $product['slug']) }}">
                <img src="{{ asset($product['thumb_image']) }}"
                    alt="thumb_image">
                <img class="product_hover_img"
                    src="{{ asset($product['hover_image']) }}"
                    alt="hover_image">
            </a>
            @if (!isset($listing) || $listing != 'short')
                <div class="product_action_box">
                    <ul class="list_none pr_action_btn">
                        @if ($product['stage'] != 'pre-order' && $product['stock_status'] == 'in_stock')
                            <li>
                                <a class="add-to-cart" href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="Top" title="Add to Cart" data-id="{{ $product['slug'] }}">
                                    <i class="fas fa-shopping-bag"></i>
                                </a>
                            </li>
                        @endif

                        <li>
                            <a href="javascript:;" class="add_compare" data-id="{{ $product['slug'] }}" data-bs-toggle="tooltip" data-bs-placement="Top" title="Add to Compare">
                                <i class="fas fa-random"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('quick.view', $product['slug']) }}" class="popup-ajax" data-bs-toggle="tooltip" data-bs-placement="Top" title="Quick View">
                                <i class="fas fa-eye"></i>
                            </a>
                        </li>
                        <li>
                            <a data-id="{{ $product['id'] }}" href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="Top" title="Save to Wish List" class="add_wishlist" >
                                <i class="far fa-heart"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            @endif

        </div>
        <div class="product_info product-listing">
            <h6 class="product_title">
                <a href="{{ route('slug.handle', $product['slug']) }}">
                    {{ ucwords($product['name']) }}
                </a>
            </h6>
            <div class="product_price" style="margin-right: 10px;">
                @if (isset($product['discount_type']))
                    <span class="price">
                        {{ format_price(convert_price($product['discounted_price'])) }}
                    </span>
                    <del>
                        {{ format_price(convert_price($product['unit_price'])) }}
                    </del>
                @else
                    <span class="price">{{ format_price(convert_price($product['unit_price'])) }}</span>
                @endif
            </div>
            <div class="rating_wrap mb-3 ml-3">
                <div class="rating">
                    <div class="product_rate" style="width:{{ $product['averageRating'] }}%"></div>
                </div>
                <span class="rating_num">({{ $product['ratingCount'] }})</span>
            </div>
            @if (isset($listing) && $listing == 'main')

                @if ($product['stock_status'] == 'in_stock' && isset($listing_type))
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <a href="{{ route('pc-builder.pick', ['item' => $item, 'id' => encode($product['id'])]) }}"class="btn btn-primary btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Choose
                            </a>
                        </div>
                    </div>
                @else
                    @if ($product['stage'] != 'normal')
                        <span class="st-btn mb-3 stock-status">{{ ucfirst($product['stage']) }}</span>
                    @endif
                    @if ($product['stage'] == 'normal' && $product['stock_status'] == 'in_stock')
                        <span class="st-btn stock-status in-stock-status">In Stock</span>
                    @endif
                    @if ($product['stock_status'] != 'in_stock')
                        <a href="{{ route('slug.handle', $product['slug']) }}" class="stock-status-warning">See all buying option</a>
                    @endif
                @endif

                <div class="pr_desc_wrapper">
                    <div class="pr_desc">
                        <ul class="desc_content">
                            @if (count($product['specifications']) > 0)
                                @foreach ($product['specifications'] as $features)
                                    <li style="font-size: 85%">
                                        {{ $features['type_name'] }} : {{ $features['attr_name'] }}
                                    </li>
                                @endforeach
                            @else
                                <li style="font-size: 85%">
                                    <b>Dimensions</b>: Compact rackmount form (LxW varies).
                                </li>
                                <li style="font-size: 85%">
                                    <b>Material</b>: Durable metal, reinforced aluminum build.
                                </li>
                                <li style="font-size: 85%">
                                    <b>Features</b>: 12 drive bays, ECC RAM, 6-core Xeon CPU.
                                </li>
                                <li style="font-size: 85%">
                                    <b>Warranty</b>: 3-year limited, covers parts and labor.
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="overlay_toggle">
                        <a href="javascript:;" class="toggle_desc">Show More</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@else
    <li>
        <div class="post_img">
            <a href="{{ route('slug.handle', $product['slug']) }}">
                <img src="{{ asset($product['thumb_image']) }}" alt="{{ $product['name'] }}">
            </a>
        </div>
        <div class="post_content">
            <h6 class="product_title">
                <a href="{{ route('slug.handle', $product['slug']) }}">
                    {{ $product['name'] }}
                </a>
            </h6>
            <div class="product_price" style="margin-right: 10px;">
                @if (isset($product['discount_type']))
                    <span class="price">
                        {{ format_price(convert_price($product['discounted_price'])) }}
                    </span>
                    <del>
                        {{ format_price(convert_price($product['unit_price'])) }}
                    </del>
                @else
                    <span class="price">{{ format_price(convert_price($product['unit_price'])) }}</span>
                @endif
            </div>
            <div class="rating_wrap">
                <div class="rating_wrap">
                    <div class="rating">
                        <div class="product_rate" style="width:{{ $product['averageRating'] }}%"></div>
                    </div>
                    <span class="rating_num">({{ $product['ratingCount']??0 }})</span>
                </div>
            </div>
        </div>
    </li>
@endif
