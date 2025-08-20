@if (View::exists('frontend.homepage.newslatter') && homepage_setting('newslatter'))
    @include('frontend.homepage.newslatter')
@endif

<footer class="footer_dark">
	<div class="footer_top">
        <div class="custom-container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12">
                	<div class="widget">
                        <div class="footer_logo">
                            <a href="{{ route('home') }}" title="Go to home page">
                                <img title="{{ get_settings('system_name') }} Logo" src="{{ get_settings('system_logo_white') ? asset(get_settings('system_logo_white')) : asset('pictures/default-logo-white.png') }}" alt="logo"/>
                            </a>
                        </div>
                        {!! get_settings('system_about_wizard') !!}
                    </div>
                    <div class="widget">
                        <ul class="social_icons social_white">

                            @if (get_settings('system_facebook_link'))
                                <li>
                                    <a target="_blank" title="Visit Our Facebook Page" href="{{ get_settings('system_facebook_link') }}">
                                        <i class="fab fa-facebook"></i>
                                    </a>
                                </li>
                            @endif

                            @if (get_settings('system_twitter_link'))
                                <li>
                                    <a target="_blank" title="Visit our X handle" href="{{ get_settings('system_twitter_link') }}">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                </li>
                            @endif
                            

                            @if (get_settings('system_youtube_link'))
                                <li>
                                    <a target="_blank" title="Visit Our YouTube Channel" href="{{ get_settings('system_youtube_link') }}">
                                        <i class="fab fa-youtube"></i>
                                    </a>
                                </li>
                            @endif

                            @if (get_settings('system_instagram_link'))
                                <li>
                                    <a target="_blank" title="Visit Our Instagram Page" href="{{ get_settings('system_instagram_link') }}">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                </li>
                            @endif
                            
                            @if (get_settings('system_linkedin_link'))
                                <li>
                                    <a target="_blank" title="Visit Our LinkedIn Page" href="{{ get_settings('system_linkedin_link') }}">
                                        <i class="fab fa-linkedin"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
        		</div>
                <div class="col-lg-2 col-md-3 col-sm-6">
                	<div class="widget">
                        <h6 class="widget_title">{{ get_settings('footer_menu_one_label_text') }}</h6>
                        <ul class="widget_links">
                            @if (get_settings('footer_menu_one_labels') != null)
                                @foreach ( json_decode(get_settings('footer_menu_one_labels')) as $key => $value)
                                    <li>
                                        <a title="Visit {{ $value }} Page" href="{{ json_decode(App\Models\ConfigurationSetting::where('type', 'footer_menu_one_links')->first()->value, true)[$key] }}">
                                            {{ $value }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6">
                	<div class="widget">
                        <h6 class="widget_title">{{ get_settings('footer_menu_tow_label_text') }}</h6>
                        <ul class="widget_links">
                            @if (get_settings('footer_menu_two_labels') != null)
                                @foreach ( json_decode(get_settings('footer_menu_two_labels')) as $key => $value)
                                    <li>
                                        <a title="Visit {{ $value }} Page" href="{{ json_decode(App\Models\ConfigurationSetting::where('type', 'footer_menu_two_links')->first()->value, true)[$key] }}">
                                            {{ $value }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6">
                	<div class="widget">
                        <h6 class="widget_title">My Account</h6>
                        <ul class="widget_links">
                            <li><a title="Visit My Profile Page" href="{{ route('dashboard') }}">My Account</a></li>
                            <li><a title="Visit My Wishlist Page" href="{{ route('account.wishlist') }}">Wishlist</a></li>
                            <li><a title="Visit My Return Orders Page" href="#">Returns</a></li>
                            <li><a title="Visit My Order History Page" href="{{ route('account.my_orders') }}">Orders History</a></li>
                            <li><a title="Visit Order Tracking Page" href="{{ route('track.order') }}">Order Tracking</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                	<div class="widget">
                        <h6 class="widget_title">Contact Info</h6>
                        <ul class="contact_info contact_info_light">
                            <li>
                                <i class="ti-location-pin"></i>
                                <p>{{ get_settings('system_footer_contact_address') }}</p>
                            </li>
                            <li>
                                <i class="ti-email"></i>
                                <a target="_blank" title="Mail us for any queries" href="mailto:{{ get_settings('system_footer_contact_email') }}">Mail Us</a>
                            </li>
                            <li>
                                <i class="ti-mobile"></i>
                                <p>{{ get_settings('system_footer_contact_phone') }}</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom_footer border-top-tran">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-md-0 text-center text-md-start">
                        {!! get_settings('system_copyright_wizard') !!}
                    </p>
                </div>
                <div class="col-md-6">
                    <ul class="footer_payment text-center text-lg-end">
                        <li>
                            <a title="Payment method photo" href="javascript:;">
                                <img style="width:300px;" title="Payment Method Photos" src="{{ get_settings('system_payment_method_photo') ? asset(get_settings('system_payment_method_photo')) : '' }}" alt="Payment Method">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<nav class="mobile-nav">
    <div class="container">
        <div class="mobile-group">
            <a title="Visit Compare Page" class="mobile-widget" href="{{ route('compare') }}">
                <i class="fas fa-random"></i>
                <span>Compare</span>
                <sup class="compare_counter">
                    {{ session()->get('compare_list') ? count(session()->get('compare_list')) : 0 }}
                </sup>
            </a>

            @if (Auth::guard('customer')->check())
                <a title="Visit Wishlist Page" class="mobile-widget" href="{{ route('account.wishlist') }}">
                    <i class="fas fa-heart"></i>
                    <span>Wishlist</span>
                </a>
            @else
                @php($rand = rand(0,3))
                @switch($rand)
                    @case(1)
                        <a title="Visit Laptop Buying Guide Page" class="mobile-widget" href="{{ route('laptop-buying-guide') }}">
                            <i class="fas fa-laptop"></i>
                            <span>Laptop Guide</span>
                        </a>
                        @break
                    @case(2)
                        <a title="Visit Flash Sale Page" class="mobile-widget" href="{{ route('flash-deals') }}">
                            <i class="fas fa-bolt"></i>
                            <span>Flash Sale</span>
                        </a>
                        @break
                    @case(3)
                        <a title="Visit Coupon Sale Page" class="mobile-widget" href="{{ route('coupon-codes') }}">
                            <i class="fas fa-tags"></i>
                            <span>Coupon Sale</span>
                        </a>
                        @break
                    @default
                        <a title="Visit Coupon Sale Page" class="mobile-widget" href="{{ route('coupon-codes') }}">
                            <i class="fas fa-tags"></i>
                            <span>Coupon Sale</span>
                        </a>
                @endswitch
            @endif
            
            <a title="Visit {{ get_settings('system_name') }} Home Page" class="mobile-widget plus-btn" href="{{ route('home') }}">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>

            <a title="Checkout PC Builder Page" class="mobile-widget" href="{{ route('pc-builder') }}">
                <i class="fas fa-user"></i>
                <span>Pc Builder</span>
            </a>
            
            <a title="Go To My Profile Page" class="mobile-widget" href="{{ route('login') }}">
                <i class="fas fa-envelope"></i>
                <span>Account</span>
            </a>
        </div>
    </div>
</nav>

<a href="{{ route('compare') }}" title="Show number of item on your compare list" class="cart-button compare-btn">
    <i class="fas fa-random"></i>
    <span class="counter compare_counter">{{ session()->get('compare_list') ? count(session()->get('compare_list')) : 0 }}</span>
</a>

<a title="Show Number of item on your cart" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" href="#offcanvasExample" role="button" aria-controls="offcanvasRight" class="cart-button cart-container">
    <i class="fas fa-shopping-bag"></i>
    <span class="counter">0</span>
</a>

<div class="cart-modal m-cart" id="m-cart">
    <div class="title">
        <p>YOUR CART</p>
        <span class="mc-toggler loaded close">
            <i class="ti-close"></i>
        </span>
    </div>
    <div class="content cart-content">
        <div style="overflow-x: hidden;" class="row mt-5">
            <div class="col-md-12 text-center">
                <i class="fas fa-spinner fa-spin fa-5x"></i>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="promotion-code"></div>
                
        <div class="total">
            <div class="title">Sub-Total</div>
            <div class="amount">0৳</div>
        </div>
                
        <div class="total">
            <div class="title">Total</div>
            <div class="amount">0৳</div>
        </div>

        <div class="checkout-btn">
            <a title="Go to cart page" href="{{ route('cart') }}">
                <button type="button" class="btn submit">
                    Go To Cart
                </button>
            </a>
        </div>
    </div>
</div>