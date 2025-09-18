@extends('frontend.layouts.app', ['title' => get_settings('login_site_title')])
@section('meta')
<meta property="og:image:width" content="200">
    <meta property="og:image:height" content="200">
    <meta property="og:site_name" content="{{ get_settings('system_name') }}">
    
    <meta name="title" content="{{ get_settings('login_meta_title') }}">
    <meta name="author" content="{{ get_settings('system_name') }} : {{ route('home') }} : {{ get_settings('system_footer_contact_email') }}">
    <meta name="description" content="{{ get_settings('login_meta_description') }}">	

    <!-- For Open Graph -->
    <meta property="og:url" content="{{ url()->current() }}">	
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ get_settings('login_meta_title') }}">	
    <meta property="og:description" content="{{ get_settings('login_meta_description') }}.">	
    <meta property="og:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">	

    <!-- For Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:creator" content="{{ get_settings('system_name') }}" /> 
    <meta name="twitter:title" content="{{ get_settings('login_meta_title') }}" />
    <meta name="twitter:description" content="{{ get_settings('login_meta_description') }}" />	
    <meta name="twitter:site" content="{{ route('home') }}" />		
    <meta name="twitter:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">
    
    {!! get_settings('login_meta_article_tag') !!}
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
                    <li class="breadcrumb-item active">
                        Account Login
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endpush
@push('styles')
<style>
    .btn-outline-facebook {
        border-color: #3b5998;
        color: #3b5998 !important;
    }

    .btn-outline-facebook:hover {
        background-color: #3b5998;
        color: #fff !important;
    }

    .btn-outline-google {
        border-color: #db4437;
        color: #db4437 !important;
    }

    .btn-outline-google:hover {
        background-color: #db4437;
        color: #fff !important;
    }

</style>
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
                                    <h3>Account Login</h3>
                                </div>
                                <form method="POST" id="login-form" action="{{ route('login.post') }}">
                                    @if(request()->has('back'))
                                        <input type="hidden" name="back" value="1">
                                    @endif
                                    @if(request()->has('buy'))
                                        <input type="hidden" name="buy" value="{{ request()->get('buy') }}">
                                    @endif
                                    <div class="row">
                                        <!-- email -->
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="username">Phone/E-Mail <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="username" required>
                                        </div>

                                        <!-- password -->
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="password">Password</label>
                                            <div class="input-group">
                                                <input data-parsley-errors-container="#password_error" type="password" name="password" id="password" class="form-control" required>
                                                <button type="button" id="togglePassword" class="btn btn-outline-secondary">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </div>
                                            <span id="password_error"></span>
                                        </div>
                                    </div>
                                    <div class="login_footer form-group mb-3">
                                        {{-- <div class="chek-form">
                                            <div class="custome-checkbox">
                                                <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox1" value="">
                                                <label class="form-check-label" for="exampleCheckbox1"><span>Remember me</span></label>
                                            </div>
                                        </div> --}}
                                        <a href="{{ route('forget-password') }}">Forgot password?</a>
                                    </div>
                                    <div class="form-group mb-3">
                                        <button style="display: none;" type="submit" id="submit" class="btn btn-fill-out btn-block" name="login">Log in</button>
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
                                <ul class="btn-login list_none text-center">
                                    {{-- <li>
                                        @php
                                            $facebookBack = request()->has('back') ? request('back') : (request()->has('buy') ? request('buy') : null);
                                        @endphp
                                        <a style="padding: 8px 10px;" 
                                           href="{{ route('login.facebook', $facebookBack ? ['back' => $facebookBack] : []) }}" 
                                           class="btn btn-outline-facebook btn-sm">
                                            <i class="fab fa-facebook"></i>
                                            <span class="d-none d-sm-inline">Connect with Facebook</span>
                                        </a>
                                    </li> --}}
                                    {{-- <li>
                                        @php
                                            $googleBack = request()->has('back') ? request('back') : (request()->has('buy') ? request('buy') : null);
                                        @endphp
                                        <a style="padding: 8px 10px;" 
                                           href="{{ route('google.login', $googleBack ? ['back' => $googleBack] : []) }}" 
                                           class="btn btn-outline-google btn-sm">
                                                <i class="fab fa-google fa-fw"></i>
                                            Connect with Google
                                        </a>
                                    </li> --}}
                                </ul>
                                
                                <div class="form-note text-center">Don't Have an Account? 
                                    @if(request()->has('back'))
                                        <a href="{{ route('register', ['back' => request('back')]) }}">Sign up now</a>
                                    @elseif (request()->has('buy'))
                                        <a href="{{ route('register', ['buy' => request('buy')]) }}">Sign up now</a>
                                    @else
                                        <a href="{{ route('register') }}">Sign up now</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
@endsection
@push('scripts')
    
    <script src="{{ asset('backend/assets/js/parsley.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/toastr.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/pages/login.js') }}"></script>
@endpush