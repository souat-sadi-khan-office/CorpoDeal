<div class="col-md-12">
    <section class="section my-8">
        <div class="row">
            <div class="col-lg-4">
                <div id="featured-product-area">
                    <div class="row">
                        <div class="col-12">
                            <div class="heading_tab_header">
                                <div class="heading_s2">
                                    <h4>Featured Products</h4>
                                </div>
                                <div class="view_all">
                                    <a href="{{ route('search', ['sort' => 'featured']) }}" class="text_default"><span>View All</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="product_wrap placeholder">
                                <div class="product_img">
                                    <a href="#">
                                        <div class="img_placeholder loading"></div>
                                    </a>
                                </div>
                                <div class="product_info">
                                    <h6 class="product_title">
                                        <div class="title_placeholder loading"></div>
                                    </h6>
                                    <div class="product_price">
                                        <div class="price_placeholder loading"></div>
                                        <del>
                                            <div class="del_placeholder loading"></div>
                                        </del>
                                    </div>
                                    <div class="rating_wrap">
                                        <div class="rating_placeholder loading"></div>
                                    </div>
                                    <div class="pr_desc">
                                        <div class="desc_placeholder loading"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mt-sm-3-mob">
                <div class="row">
                    <div class="col-12">
                        <div class="heading_tab_header">
                            <div class="heading_s2">
                                <h4>Top Rated Products</h4>
                            </div>
                            <div class="view_all">
                                <a href="{{ route('search', ['sort' => 'popularity']) }}" class="text_default">
                                    <span>View All</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="top-rated-product-carousel product_list carousel_slider owl-carousel owl-theme nav_style5" data-nav="false" data-dots="false" data-loop="true" data-margin="20" data-responsive='{"0":{"items": "1"}, "380":{"items": "1"}, "640":{"items": "2"}, "991":{"items": "1"}}'>
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
            </div>
            <div class="col-lg-4 mt-sm-3-mob">
                <div id="on-sale-product-area">
                    <div class="row">
                        <div class="col-12">
                            <div class="heading_tab_header">
                                <div class="heading_s2">
                                    <h4>On Sale Products</h4>
                                </div>
                                <div class="view_all">
                                    <a href="{{ route('search', ['sort' => 'on-sale']) }}" class="text_default"><span>View All</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="product_wrap placeholder">
                                <div class="product_img">
                                    <a href="javascript:;">
                                        <div class="img_placeholder loading"></div>
                                    </a>
                                </div>
                                <div class="product_info">
                                    <h6 class="product_title">
                                        <div class="title_placeholder loading"></div>
                                    </h6>
                                    <div class="product_price">
                                        <div class="price_placeholder loading"></div>
                                        <del>
                                            <div class="del_placeholder loading"></div>
                                        </del>
                                    </div>
                                    <div class="rating_wrap">
                                        <div class="rating_placeholder loading"></div>
                                    </div>
                                    <div class="pr_desc">
                                        <div class="desc_placeholder loading"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>