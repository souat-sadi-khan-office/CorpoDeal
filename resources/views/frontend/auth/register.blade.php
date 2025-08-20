@extends('frontend.layouts.app', ['title' => get_settings('register_site_title')])
@section('meta')
<meta property="og:image:width" content="200">
    <meta property="og:image:height" content="200">
    <meta property="og:site_name" content="{{ get_settings('system_name') }}">
    
    <meta name="title" content="{{ get_settings('register_meta_title') }}">
    <meta name="author" content="{{ get_settings('system_name') }} : {{ route('home') }} : {{ get_settings('system_footer_contact_email') }}">
    <meta name="description" content="{{ get_settings('register_meta_description') }}">	

    <!-- For Open Graph -->
    <meta property="og:url" content="{{ url()->current() }}">	
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ get_settings('register_meta_title') }}">	
    <meta property="og:description" content="{{ get_settings('register_meta_description') }}.">	
    <meta property="og:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">	

    <!-- For Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:creator" content="{{ get_settings('system_name') }}" /> 
    <meta name="twitter:title" content="{{ get_settings('register_meta_title') }}" />
    <meta name="twitter:description" content="{{ get_settings('register_meta_description') }}" />	
    <meta name="twitter:site" content="{{ route('home') }}" />		
    <meta name="twitter:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">
    
    {!! get_settings('register_meta_article_tag') !!}
@endsection
@push('breadcrumb')
    <div class="breadcrumb_section page-title-mini">
        <div class="custom-container">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">
                                <i class="linearicons-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('login') }}">
                                Account Login
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Register Account
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endpush

@section('content')
    <div class="main_content bg_gray">
        <style>
            
        </style>
        <div class="login_register_wrap section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-6 col-md-10">
                        <div class="login_wrap">
                            <div class="padding_eight_all bg-white">
                                <div class="heading_s1">
                                    <h3>Register Account</h3>
                                </div>
                                <form method="POST" id="register-form" action="{{ route('register.post') }}">
                                    @csrf
                                    @if(request()->has('back'))
                                        <input type="hidden" name="back" value="1">
                                    @endif
                                    @if(request()->has('buy'))
                                        <input type="hidden" name="buy" value="{{ request()->get('buy') }}">
                                    @endif
                                    <div class="row">
                                        <input type="hidden" name="type" id="type" value="email">
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="customer_name">Full Name</label>
                                            <input type="text" name="customer_name" id="customer_name" class="form-control" required>
                                        </div>
                                        {{-- <div class="col-md-12 form-group mb-3">
                                            <label for="customer_last_name">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" name="customer_last_name" id="customer_last_name" class="form-control" required placeholder="Last Name">
                                        </div> --}}
                                        <!-- email -->
                                        <div class="col-md-12 form-group mb-3">
                                            <div class="console">
                                                <label for="email">Enter Your E-Mail Address <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" name="email" id="email" required>
                                            </div>
                                            <small class="email-area">
                                                Use your 
                                                <a data-value="phone" class="change_type primary-color" href="javascript:;">
                                                    Phone Number
                                                </a> instead
                                            </small>
                                            <small class="phone-area" style="display: none;">
                                                Use your 
                                                <a data-value="email" class="change_type primary-color" href="javascript:;">
                                                    Email Address
                                                </a> instead
                                            </small>
                                        </div>

                                        {{-- <div style="display: none;" class="col-md-12 form-group mb-3">
                                            <label for="phone">Enter Your Phone Number<span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control" name="phone" id="phone">
                                        </div> --}}
                                        {{-- <div class="col-md-12 form-group mb-3">
                                            <label for="customer_phone">Phone <span class="text-danger">*</span></label>
                                            <input type="text" name="customer_phone" id="customer_phone" class="form-control" required placeholder="Phone">
                                        </div> --}}
                                        <div id="password_area" class="col-md-12 form-group mb-3">
                                            <label for="password">Password</label>
                                            <div class="input-group">
                                                <input data-parsley-errors-container="#password_error" type="password" name="password" id="password" class="form-control" required>
                                                <button type="button" id="togglePassword" class="btn btn-outline-secondary">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </div>
                                            <span id="password_error"></span>
                                        </div>
                                        <div class="login_footer form-group mb-3">
                                            <div class="chek-form">
                                                <div class="custome-checkbox">
                                                    <input checked class="form-check-input" type="checkbox" name="agree_terms" id="agree_terms" value="accepted">
                                                    <label class="form-check-label" for="agree_terms"><span>I agree to <a href="javascript:;">terms &amp; Policy</a>.</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <button type="submit" style="display: none;" class="btn btn-fill-out btn-block" id="submit">
                                                Register 
                                            </button>
                                            <button style="margin-left:0px;" class="btn btn-dark btn-block"  id="submitting" type="button">
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                Loading...
                                            </button>
                                        </div>

                                        <div class="col-md-12 form-group mb-3">
                                            <div id="register_success_message" style="display:none;" class="alert alert-success" role="alert"></div>
                                            <div id="register_warning_message" style="display:none;" class="alert alert-danger" role="alert"></div>
                                        </div>
                                    </div>
                                </form>
                                <div class="different_login">
                                    
                                    <span> Already have an account</span>
                                </div>
                                <div class="form-note text-center">If you already have an account with us, please login at the <a class="primary-color" href="{{ route('login') }}">login</a> page.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.8/build/css/intlTelInput.min.css">
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.8/build/js/intlTelInput.min.js"></script>

<script src="{{ asset('frontend/assets/js/pages/register.js') }}"></script>
@endpush