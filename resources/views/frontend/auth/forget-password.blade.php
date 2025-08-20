@extends('frontend.layouts.app', ['title' => get_settings('forget_site_title')])
@section('meta')
<meta property="og:image:width" content="200">
    <meta property="og:image:height" content="200">
    <meta property="og:site_name" content="{{ get_settings('system_name') }}">
    
    <meta name="title" content="{{ get_settings('home_meta_title') }}">
    <meta name="author" content="{{ get_settings('system_name') }} : {{ route('home') }} : {{ get_settings('system_footer_contact_email') }}">
    <meta name="description" content="{{ get_settings('forget_meta_description') }}">	

    <!-- For Open Graph -->
    <meta property="og:url" content="{{ url()->current() }}">	
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ get_settings('forget_meta_title') }}">	
    <meta property="og:description" content="{{ get_settings('forget_meta_description') }}.">	
    <meta property="og:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">	

    <!-- For Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:creator" content="{{ get_settings('system_name') }}" /> 
    <meta name="twitter:title" content="{{ get_settings('forget_meta_title') }}" />
    <meta name="twitter:description" content="{{ get_settings('forget_meta_description') }}" />	
    <meta name="twitter:site" content="{{ route('home') }}" />		
    <meta name="twitter:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">
    
    {!! get_settings('forget_meta_article_tag') !!}
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
                            Forgotten Password
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endpush
@section('content')
    <div class="main_content bg_gray">

        <div class="login_register_wrap section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-6 col-md-10">
                        <div class="login_wrap">
                            <div class="padding_eight_all bg-white">
                                <div class="heading_s1">
                                    <h3>Forgot Your Password?</h3>
                                </div>
                                <form method="POST" id="forget-password-form" action="{{ route('post.forget-password') }}">
                                    @csrf
                                    <input type="hidden" name="type" value="email">
                                    <div class="row">
                                        <!-- email -->
                                        <div class="col-md-12 email-area form-group mb-3">
                                            <label for="email">Enter Your E-Mail Address <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" id="email" required>
                                            <small>Use your <a data-value="phone" class="change_type" href="javascript:;">Phone Number</a> instead</small>
                                        </div>

                                        <div style="display: none;" class="col-md-12 phone-area form-group mb-3">
                                            <label for="phone">Enter Your Phone Number<span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control" name="phone" id="phone">
                                            <small>Use your <a data-value="email" class="change_type" href="javascript:;">Email Address</a> instead</small>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <button style="display: none;" type="submit" id="submit" class="btn btn-fill-out btn-block" name="login">Submit</button>
                                    </div>
                                    <div class="form-group mb-3">
                                        <button class="btn btn-dark btn-block" disabled id="submitting" type="button">
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            Loading...
                                        </button>
                                    </div>
                                </form>
                                <div class="different_login">
                                    <span> or</span>
                                </div>
                                <div class="form-note text-center">Don't Have an Account? <a href="{{ route('register') }}">Sign up now</a></div>
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
    <script src="{{ asset('frontend/assets/js/pages/forget-password.js') }}"></script>
@endpush