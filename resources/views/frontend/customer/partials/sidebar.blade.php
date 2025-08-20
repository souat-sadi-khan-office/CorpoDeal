<div class="col-lg-3 col-md-4 profile_menu">
    <div class="dashboard_menu bg_white">
        <ul class="nav nav-tabs flex-column" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ Request::is('account/dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fi fi-rr-house-blank"></i>
                    <span class="ml-2">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('account/my-orders') ||Request::is('account/my-order/*') ? 'active' : '' }}" href="{{ route('account.my_orders') }}">
                    <i class="fi fi-rr-order-history"></i>
                    <span class="ml-2">Orders</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('account/quotes') ? 'active' : '' }}" href="{{ route('account.quote') }}">
                    <i class="fi fi-rr-message-quote"></i>
                    <span class="ml-2">Quote</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('account/edit-profile') ? 'active' : '' }}" href="{{ route('account.edit_profile') }}">
                    <i class="fi fi-rr-edit"></i>
                    <span class="ml-2">Edit Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('account/change-password') ? 'active' : '' }}" href="{{ route('account.change_password') }}">
                    <i class="fi fi-rr-lock"></i>
                    <span class="ml-2">Change Password</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('account/negative-balance') ? 'active' : '' }}" href="{{ route('account.negative.balance') }}">
                    <i class="fi fi-rr-piggy-bank"></i>
                    <span class="ml-2">Negative Balance</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('account/phone-book*') ? 'active' : '' }}" href="{{ route('account.phone-book.index') }}">
                    <i class="fi fi-rr-mobile-notch"></i>
                    <span class="ml-2">Phone Book</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('account/address-book*') ? 'active' : '' }}" href="{{ route('account.address-book.index') }}">
                    <i class="fi fi-rr-marker"></i>
                    <span class="ml-2">Address</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('account/wish-list') ? 'active' : '' }}" href="{{ route('account.wishlist') }}">
                    <i class="fi fi-rr-heart"></i>
                    <span class="ml-2">Wish List</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('account/saved-pc') ? 'active' : '' }}" href="{{ route('account.saved_pc') }}">
                    <i class="fi fi-rr-screen"></i>
                    <span class="ml-2">Saved PC</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('account/star-points') ? 'active' : '' }}" href="{{ route('account.star_points') }}">
                    <i class="fi fi-rr-star"></i>
                    <span class="ml-2">Star Points</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('account/my-coupon') ? 'active' : '' }}" href="{{ route('account.my_coupons') }}">
                    <i class="fi fi-rr-tags"></i>
                    <span class="ml-2">My Coupons</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="logout" href="javascript:;" data-url="{{ route('logout') }}">
                    <i class="fi fi-rr-exit"></i>
                    <span class="ml-2">Logout</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link bg-danger text-white"  href="{{ route('account.remove.account') }}">
                    <i class="fi fi-rr-trash"></i>
                    <span class="ml-2">Delete My Account</span>
                </a>
            </li>
        </ul>
    </div>
</div>
