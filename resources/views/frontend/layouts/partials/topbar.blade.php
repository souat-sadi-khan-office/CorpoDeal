{{-- <div class="top-header">
    <div class="custom-container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                    <div class="lng_dropdown me-2">
                        <select name="countries" class="custome_select">
                            <option value='en' data-image="{{ asset('frontend/assets/images/eng.png') }}" data-title="English">English</option>
                            <option value='fn' data-image="{{ asset('frontend/assets/images/fn.png') }}" data-title="France">France</option>
                            <option value='us' data-image="{{ asset('frontend/assets/images/us.png') }}" data-title="United States">United States</option>
                        </select>
                    </div>

                    <div class="me-3">
                        <select name="countries" class="custome_select">
                            <option value='USD' data-title="USD">USD</option>
                            <option value='EUR' data-title="EUR">EUR</option>
                            <option value='GBR' data-title="GBR">GBR</option>
                        </select>
                    </div>
                    
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-center text-md-end">
                    <ul class="header_list">
                        <li>
                            <i class="ti-mobile"></i>
                            <span>123-456-7890</span>
                        </li>
                        
                        @if (Auth::guard('customer')->check())
                            <li>
                                <a href="{{ route('dashboard') }}">
                                    <i class="ti-user"></i>
                                    <span>My Account</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('logout') }}">
                                    <i class="ti-user"></i>
                                    <span>Logout</span>
                                </a>
                            </li>
                        @else 
                            <li>
                                <a href="{{ route('login') }}">
                                    <i class="ti-user"></i>
                                    <span>Login</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('register') }}">
                                    <i class="ti-user"></i>
                                    <span>Register</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<style>
    .topbar {
            height: 45px;
            background: linear-gradient(90deg, #FF0000, #FFA500);;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: bold;
            position: block;
            top: 0;
            width: 100%;
            animation: slideIn 1s ease-in-out;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }

        .blink-text {
            animation: blink 2.5s infinite;
        }

        @keyframes slideIn {
            from { transform: translateY(-100%); }
            to { transform: translateY(0); }
        }
</style>

<div class="topbar">
    <span class="blink-text">We're launching soon! Stay tuned.</span>
</div>