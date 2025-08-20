<div class="middle-header d-none d-lg-block dark_skin r">
    <div class="custom-container">
        <div class="nav_block">
            <a class="navbar-brand" title="Visit {{ get_settings('system_name') }} Home Page" href="{{ route('home') }}">
                @php
                    $whiteLogo = get_settings('system_logo_white');
                    $darkLogo = get_settings('system_logo_dark');
                @endphp

                {{-- Check if the white logo file is an SVG --}}
                @if($whiteLogo && pathinfo($whiteLogo, PATHINFO_EXTENSION) === 'svg')
                    {{-- Inline SVG --}}
                    <div class="logo_light" style="width: 100px; height: 60px;">
                        {!! file_get_contents(public_path($whiteLogo)) !!}
                    </div>
                @else
                    <img class="logo_light" src="{{ $whiteLogo ? asset($whiteLogo) : asset('pictures/default-logo-white.png') }}" alt="System white logo" title="System white logo">
                @endif

                {{-- Check if the dark logo file is an SVG --}}
                @if($darkLogo && pathinfo($darkLogo, PATHINFO_EXTENSION) === 'svg')
                    <div class="logo_dark" style="width: 150px; height: 60px;">
                        {!! file_get_contents(public_path($darkLogo)) !!}
                    </div>
                @else
                    <img class="logo_dark" src="{{ $darkLogo ? asset($darkLogo) : asset('pictures/default-logo-dark.png') }}" alt="System dark logo" title="System dark logo">
                @endif
            </a>

            <div class="order-md-2" style="width: 65px;"></div>
            <div class="product_search_form order-md-2">
                <form action="{{ route('search') }}" method="GET">
                    <div style="display: flex; align-items: center;">
                        <input
                            class="form-control"
                            style="flex: 1; padding: 10px; font-size: 12px;"
                            autocomplete="off"
                            placeholder="Search"
                            required
                            id="search"
                            name="search"
                            type="text">

                        <button
                            type="submit"
                            class="btn btn-fill-out"
                            style="padding: 7px 10px; margin-left: 5px;cursor: pointer;">
                            <i class="linearicons-magnifier" style="font-size:12px;margin-right:0px;"></i>
                        </button>
                        <button
                            type="button"
                            id="speechBtn"
                            class="btn btn-fill-out"
                            style="padding: 7px 12px; margin-left: 5px; cursor: pointer;">
                            <i class="fas fa-microphone" style="font-size:12px;margin-right:0px;"></i>
                        </button>
                    </div>
                    <audio id="notificationAudio" src="{{ asset('notifications/recording.mp3') }}"></audio>
                </form>

                <div class="typed-search-box stop-propagation document-click-d-none d-none bg-white rounded shadow-lg position-absolute left-0 top-100 w-100" style="min-height: 200px">
                    <div class="searching-preloader">
                        <div class="search-preloader">
                            <div class="lds-ellipsis">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                    <div class="search-nothing d-none p-3 text-center fs-16">

                    </div>
                    <div class="search-content text-left">
                        <div class="">

                        </div>
                        <div class="">

                        </div>
                    </div>
                </div>
            </div>
            <ul class="navbar-nav attr-nav align-items-center order-md-5">
                <li id="wishList">
                    <div class="q-actions">
                        <div style="cursor: pointer;" id="country_name_selector" data-bs-toggle="modal" data-bs-target="#locationModal" class="ac">
                            <a title="Wishlist Page" class="ic" href="javascript:;">
                                <i class="fas fa-map-marker-alt"></i>
                            </a>
                            <div class="ac-content">
                                <h5>Location</h5>
                                <p>
                                    {{ session()->get('user_city') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </li>
                <li id="wishList">
                    <div class="q-actions">
                        <div class="ac">
                            <a title="Wishlist Page" class="ic" href="{{ route('account.wishlist') }}">
                                <i class="far fa-heart"></i>
                            </a>
                            <div class="ac-content">
                                <a title="Visit Your Wishlist Page" href="{{ route('account.wishlist') }}">
                                    <h5>Wishlist</h5>
                                </a>
                                <p>
                                    <a title="Visit Your Wish List Page" id="wish_list_counter" href="{{ route('account.wishlist') }}">
                                        @if (!Auth::guard('customer')->check())
                                            0
                                        @else
                                            {{ App\Models\WishList::where('user_id', Auth::guard('customer')->user()->id)->count() }}
                                        @endif
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </li>
                <li id="accountLogin">
                    <div class="q-actions">
                        <div class="ac">
                            <a class="ic" title="Go to Login Page" href="{{ route('login') }}">
                                <i class="far fa-user"></i>
                            </a>
                            <div class="ac-content">
                                <a title="Login Now" href="{{ route('login') }}">
                                    <h5>Account</h5>
                                </a>
                                <p>
                                    @if (Auth::guard('customer')->check())
                                        <a title="Go to Profile Page" href="{{ route('dashboard') }}">Profile</a>
                                        or
                                        <a title="Logout From The System" id="logout" href="javascript:;" data-url="{{ route('logout') }}">Logout</a>
                                    @else
                                        <a title="Go to Login Page" href="{{ route('login') }}">Login</a>
                                        or
                                        <a title="Go to Register Page" href="{{ route('register') }}">Register</a>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <div id="contactPhone" class="contact_phone order-md-last">
                <a href="tel:{{ get_settings('system_footer_contact_phone') }}">
                    <i class="linearicons-phone-wave"></i>
                    <span>{{ get_settings('system_footer_contact_phone') }}</span>
                </a>
            </div>
        </div>
    </div>
</div>
