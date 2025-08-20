<div class="ajax_quick_view">
    <div class="row">
        <div class="col-lg-6 col-md-6 mb-4 mb-md-0">
            <div class="product-image">
                <div class="product_img_box">
                    <img id="product_img" src='{{ asset($product['thumb_image']) }}'
                        data-zoom-image="{{ asset($product['thumb_image']) }}" alt="product_img" />
                </div>
                <div id="pr_item_gallery" class="product_gallery_item slick_slider" data-slides-to-show="4" data-slides-to-scroll="1" data-infinite="false">
                    @foreach ($product['images'] as $image)
                        <div class="item">
                            <a href="javascript:;" class="product_gallery_item {{ $loop->first ? 'active' : '' }}"
                                data-image="{{ asset($image->image) }}" data-zoom-image="{{ asset($image->image) }}">
                                <img src="{{ asset($image->image) }}" alt="product_small_img{{ $loop->iteration }}" />
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="pr_detail">
                <div class="product_description">
                    <h4 class="product_title"><a href="{{ route('slug.handle', $product['slug']) }}">{{ ucfirst($product['name']) }}</a></h4>
                    <div class="product_price">
                        @if (isset($product['discount_type']))
                            <span class="price">{{ format_price(convert_price($product['discounted_price'])) }}</span>
                            <del>{{ format_price(convert_price($product['price'])) }}</del>
                            <div class="on_sale">
                                <span>{{ $product['discount_type'] == 'amount' ? format_price(convert_price($product['price'])) : $product['discount'] . '%' }}
                                    Off</span>
                            </div>
                        @else
                            <span class="price">{{ format_price(convert_price($product['price'])) }}</span>
                        @endif
                    </div>
                    <div class="rating_wrap">
                        <div class="rating">
                            <div class="product_rate" style="width:{{ $product['average_rating'] }}%"></div>
                        </div>
                        <span class="rating_num">({{ $product['ratings_count'] }})</span>
                    </div>
                    <div class="pr_desc p-0 mb-3">
                        {{ substr(strip_tags($product['description']), 0, 150) }}
                    </div>
                    <br>
                    <div class="product_sort_info">
                        <ul>
                            @if ($product['total_sold'] > 0)
                                <li>
                                    <i class="linearicons-shield-check"></i> 
                                    {{ $product['total_sold'] }} Units Sold
                                </li>
                            @endif
                            @if ($product['return_deadline'] > 0)
                                <li>
                                    <i class="linearicons-sync"></i> 
                                    {{ $product['return_deadline'] }} Day Return Policy
                                </li>
                            @endif
                            @if ($product['is_COD_available'])
                                <li>
                                    <i class="linearicons-bag-dollar"></i> 
                                    Cash on Delivery available
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="pr_switch_wrap">
                        <span class="switch_lable">Key Features</span><br>
                        @foreach ($product['key_features'] as $features)
                            <li>
                                <a href="javascript:;">{{ $features['type_name'] }}</a> :
                                <a href="javascript:;">{{ $features['attribute_name'] }}</a>
                            </li>
                        @endforeach
                    </div>
                </div>
                <hr />
                <div class="cart_extra">
                    <div class="cart_btn">
                        <button class="btn btn-sm btn-fill-out add-to-cart" type="button" data-bs-toggle="tooltip" data-bs-placement="Top" title="Add to Cart" data-id="{{ $product['slug'] }}">
                            <i class="icon-basket-loaded"></i>
                            Add to cart
                        </button>
                        <a class="add_compare" data-id="{{ $product['slug'] }}" href="javascript:;">
                            <i class="icon-shuffle"></i>
                        </a>
                        <a data-id="{{ $product['id'] }}" href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="Top" title="Save to Wish List" class="add_wishlist">
                            <i class="icon-heart"></i>
                        </a>
                    </div>
                </div>
                <hr />
                <ul class="product-meta">
                    <li>SKU: <a href="{{ route('slug.handle', $product['slug']) }}">{{ $product['sku'] }}</a></li>
                    <li>Category: <a href="{{ route('slug.handle', $product['category_slug']) }}">{{ $product['category_name'] }}</a></li>
                    <li>Brand: <a href="{{ route('slug.handle', $product['brand_slug']) }}">{{ $product['brand_name'] }}</a></li>
                </ul>

                <div class="product_share">
                    <span>Share:</span>
                    <ul class="social_icons">
                        @php($shareUrl = route('slug.handle', $product['slug']))
                        <li>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-facebook"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode($product['name']) }}" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-linkedin"></i>
                            </a>
                        </li>
                        <li>
                            <a href="mailto:?subject={{ urlencode($product['name']) }}&body={{ urlencode($product['name']) }}%20{{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://wa.me/?text={{ urlencode($product['name']) }}%20{{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
