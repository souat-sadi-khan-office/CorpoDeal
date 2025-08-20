<div class="bottom_header dark_skin border-top border-bottom">
    <div class="custom-container">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-6 col-2">
                {{-- <div class="categories_wrap">
                    <button type="button" data-bs-toggle="offcanvas" data-bs-target="#category-navbar" aria-controls="category-navbar" class="categories_btn categories_menu btn-sm collapsed d-lg-block">
                        <span>All Categories </span>
                        <i class="linearicons-menu"></i>
                    </button>
                
                    <div class="offcanvas offcanvas-start p-4 w-xxl-20 w-lg-30" id="category-navbar"></div>
                </div> --}}
                @php
                    $categories = App\Models\Category::withCount('children')
                        ->where('status', 1)
                        ->where('is_featured', 1)
                        ->whereNull('parent_id')
                        ->orderByDesc('children_count') // চাইল্ডের সংখ্যা অনুযায়ী প্রথমে অর্ডার
                        ->orderBy('name', 'ASC') // এরপর নাম অনুযায়ী সাজান
                        ->limit(10)
                        ->get();
                @endphp 
                <div class="categories_wrap">
                    <button type="button" data-bs-toggle="collapse" data-bs-target="#navCatContent" aria-expanded="false" class="btn btn-xs categories_btn categories_menu">
                        <span>All Categories </span>
                        <i style="font-size:14px;" class="linearicons-menu"></i>
                    </button>
                    <div id="navCatContent" class="navbar nav collapse">
                        <ul>
                            <li class="mobile-show">
                                <a class="nav-link h4" href="javascript:;">
                                    All Categories
                                </a>
                            </li>
                            @foreach ($categories as $category)
                                @if ($category->children->count())
                                    @include('frontend.layouts.partials.category-item', ['category' => $category, 'submenu' => false])
                                @else
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('slug.handle', $category->slug) }}">
                                            {!! $category->icon !!}
                                            <span class="ms-1">{{ $category->name }}</span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach  
                        </ul> 
                        <div class="more_categories">
                            <a href="{{ route('categories') }}">
                                More Categories
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-6 col-10">
                <nav class="navbar navbar-expand-lg">
                    <div class="responsive-logo d-md-none d-sm-block">
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
                                <div class="logo_dark" style="width: 100px; height: 60px;">
                                    {!! file_get_contents(public_path($darkLogo)) !!}
                                </div>
                            @else
                                <img class="logo_dark" src="{{ $darkLogo ? asset($darkLogo) : asset('pictures/default-logo-dark.png') }}" alt="System dark logo" title="System dark logo">
                            @endif
                        </a>
                    </div>
                    
                    <div class="collapse navbar-collapse mobile_side_menu" id="navbarSidetoggle">
                        <ul class="navbar-nav">

                            @if (Auth::guard('customer')->check())
                                <li class="mobile_aside_show_only">
                                    <a class="nav-link {{ Request::is('account/dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" title="Visit My Profile Page">
                                        <i class="fi fi-rr-house-blank"></i>
                                        <span class="ml-5">Dashboard</span>
                                    </a>
                                </li>
                                <li class="mobile_aside_show_only">
                                    <a class="nav-link {{ Request::is('account/my-orders') || Request::is('account/my-order/*') ? 'active' : '' }}" href="{{ route('account.my_orders') }}" title="Visit My Orders Page">
                                        <i class="fi fi-rr-order-history"></i>
                                        <span class="ml-2">Orders</span>
                                    </a>
                                </li>
                                <li class="mobile_aside_show_only">
                                    <a class="nav-link {{ Request::is('account/quotes') ? 'active' : '' }}" href="{{ route('account.quote') }}" title="Visit My Quote Page">
                                        <i class="fi fi-rr-message-quote"></i>
                                        <span class="ml-2">Quote</span>
                                    </a>
                                </li>
                                <li class="mobile_aside_show_only">
                                    <a class="nav-link {{ Request::is('account/edit-profile') ? 'active' : '' }}" href="{{ route('account.edit_profile') }}" title="Visit Edit Profile Page">
                                        <i class="fi fi-rr-edit"></i>
                                        <span class="ml-2">Edit Profile</span>
                                    </a>
                                </li>
                                <li class="mobile_aside_show_only">
                                    <a class="nav-link {{ Request::is('account/change-password') ? 'active' : '' }}" href="{{ route('account.change_password') }}" title="Visit Change Password Page">
                                        <i class="fi fi-rr-lock"></i>
                                        <span class="ml-2">Change Password</span>
                                    </a>
                                </li>
                                <li class="mobile_aside_show_only">
                                    <a class="nav-link {{ Request::is('account/negative-balance') ? 'active' : '' }}" href="{{ route('account.negative.balance') }}" title="Visit Negative Balance Page">
                                        <i class="fi fi-rr-piggy-bank"></i>
                                        <span class="ml-2">Negative Balance</span>
                                    </a>
                                </li>
                                <li class="nav-item mobile_aside_show_only">
                                    <a class="nav-link {{ Request::is('account/phone-book*') ? 'active' : '' }}" href="{{ route('account.phone-book.index') }}" title="Visit Phone Book Page">
                                        <i class="fi fi-rr-mobile-notch"></i>
                                        <span class="ml-2">Phone Book</span>
                                    </a>
                                </li>
                                <li class="nav-item mobile_aside_show_only">
                                    <a class="nav-link {{ Request::is('account/address-book*') ? 'active' : '' }}" href="{{ route('account.address-book.index') }}" title="Visit Address Page">
                                        <i class="fi fi-rr-marker"></i>
                                        <span class="ml-2">Address</span>
                                    </a>
                                </li>
                                <li class="nav-item mobile_aside_show_only">
                                    <a class="nav-link {{ Request::is('account/wish-list') ? 'active' : '' }}" href="{{ route('account.wishlist') }}" title="Visit Wish List Page">
                                        <i class="fi fi-rr-heart"></i>
                                        <span class="ml-2">Wish List</span>
                                    </a>
                                </li>
                                <li class="nav-item mobile_aside_show_only">
                                    <a class="nav-link {{ Request::is('account/saved-pc') ? 'active' : '' }}" href="{{ route('account.saved_pc') }}" title="Visit Saved PC Page">
                                        <i class="fi fi-rr-screen"></i>
                                        <span class="ml-2">Saved PC</span>
                                    </a>
                                </li>
                                <li class="nav-item mobile_aside_show_only">
                                    <a class="nav-link {{ Request::is('account/star-points') ? 'active' : '' }}" href="{{ route('account.star_points') }}" title="Visit Star Points Page">
                                        <i class="fi fi-rr-tags"></i>
                                        <span class="ml-2">Star Points</span>
                                    </a>
                                </li>
                                
                                <li class="mobile_aside_show_only">
                                    <a class="nav-link" id="logout" title="Checkout from the system " href="javascript:;" data-url="{{ route('logout') }}">
                                        <i class="fi fi-rr-exit"></i>
                                        <span class="ml-2">Logout</span>
                                    </a>
                                </li>

                                <li class="mobile_aside_show_only">
                                    <a class="nav-link bg-danger text-white" title="Deactivate My Account" href="{{ route('account.remove.account') }}">
                                        <i class="fi fi-rr-trash"></i>
                                        <span class="ml-2">Delete My Account</span>
                                    </a>
                                </li>
                            @endif

                            @if (get_settings('header_menu_labels') != null)
                                @foreach ( json_decode(get_settings('header_menu_labels')) as $key => $value)
                                    <li>
                                        <a class="nav-link nav_item" title="Visit {{ $value }} Page" href="{{ json_decode(App\Models\ConfigurationSetting::where('type', 'header_menu_links')->first()->value, true)[$key] == '/' ? '/' : '/' . json_decode(App\Models\ConfigurationSetting::where('type', 'header_menu_links')->first()->value, true)[$key] }}">
                                            {{ $value }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif

                            @php
                                $pages = App\Models\Page::with('children')->where('status', 1)->where('show_on_navbar', 1)->whereNull('parent_id')->get();
                            @endphp
                            @foreach ($pages as $page)
                                <li class="dropdown">

                                    @if ($page->children->isNotEmpty())
                                        <a title="Visit {{ $page->title }} Page" data-bs-toggle="dropdown" class="nav-link dropdown-toggle" href="{{ route('slug.handle', $page->slug) }}">
                                            {{ $page->title }}
                                        </a>
                                        <div class="dropdown-menu">
                                            <ul>
                                                @foreach ($page->children as $child)
                                                    @include('frontend.components.dropdown', ['page' => $child])
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <a title="Visit {{ $page->title }} Page" class="nav-link" href="{{ route('slug.handle', $page->slug) }}">
                                            {{ $page->title }}
                                        </a>
                                    @endif
                                </li>
                            @endforeach

                            <li>
                                <a class="nav-link nav_item" title="Contact With Us" href="{{ route('contact') }}">
                                    Contact Us
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <ul class="navbar-nav attr-nav align-items-center">
                        <li>
                            <a title="Trigger Search Field" href="javascript:;" class="nav-link pr_search_trigger mobile-cart" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSearch" href="#offcanvasExample">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                 </svg>
                            </a>
                        </li>
                        <li>
                            <a title="Checkout Cart Information" class="mobile-cart nav-link cart-container" href="javascript:;" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" href="#offcanvasExample" role="button" aria-controls="offcanvasRight">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag">
                                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                    <line x1="3" y1="6" x2="21" y2="6"></line>
                                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                                </svg>
                                <span class="cart_count">0</span>
                            </a>
                        </li>
                        <li>
                            <a class="border-0 nav-link mobile-cart" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSidetoggle" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 16 16">
                                    <path d="M2 3.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5zm.646 2.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L4.293 8 2.646 6.354a.5.5 0 0 1 0-.708zM7 6.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm-5 3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z"></path>
                                </svg>
                            </a>
                        </li>
                    </ul>
                    <div class="pc-build-guide">
                        @if (get_settings('default_laptop_category'))
                            <a title="Visit Laptop Buying Guide Page" href="{{ route('laptop-buying-guide') }}" class="btn btn-xs btn-fill-out rounded py-2">
                                <i class="fas fa-laptop"></i>
                                Laptop 
                                <span class="hide-text">
                                    Finder
                                </span>
                            </a>
                        @endif

                        <a title="Visit PC Builder Page" href="{{ route('pc-builder') }}" class="btn btn-xs btn-fill-out rounded py-2">
                            <i class="fas fa-desktop"></i>
                            PC Builder
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>