@extends('frontend.layouts.app', ['title' => 'Phone Number Verification'])
@section('meta')
    <meta property="og:image:width" content="200">
    <meta property="og:image:height" content="200">
    <meta property="og:site_name" content="{{ get_settings('system_name') }}">

    <meta name="title" content="{{ get_settings('home_meta_title') }}">
    <meta name="author"
          content="{{ get_settings('system_name') }} : {{ route('home') }} : {{ get_settings('system_footer_contact_email') }}">
    <meta name="description" content="{{ get_settings('reset_password_meta_description') }}">

    <!-- For Open Graph -->
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Email Verification">
    <meta property="og:description" content="Email Verification for Customer.">
    <meta property="og:image"
          content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">

    <!-- For Twitter -->
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:creator" content="{{ get_settings('system_name') }}"/>
    <meta name="twitter:title" content="Email Verification"/>
    <meta name="twitter:description" content="Email Verification for Customer"/>
    <meta name="twitter:site" content="{{ route('home') }}"/>
    <meta name="twitter:image"
          content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">

    {!! get_settings('reset_password_meta_article_tag') !!}
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
                            Phone Number Verification
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endpush
@section('content')
    <style>
        .otp {
            text-align: center;
            letter-spacing: 5px;
        }

        #resend-link {
            pointer-events: none;
            color: gray;
            text-decoration: none;
        }
        #resend-link.active {
            pointer-events: auto;
            color: var(--primary-color);
            text-decoration: underline;
        }
    </style>
    <div class="main_content bg_gray">
        <div class="container">

            <div class="verification-container section">
                <h1>Verify Your Phone</h1>
                <p>A verification Code has been sent to your phone number. <br>If you didn’t receive the phone number, click the button below to resend it.</p>

                @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-4">
                        <form method="POST" id="register-form" action="{{ route('verification.resend') }}">
                            @csrf
                            <input type="text" name="code" id="code" placeholder="OTP" class="form-control otp" required>

                            <button type="submit" id="submit" style="display: none;" class="mt-3  btn-block  btn-sm btn btn-fill-out">Verify</button>
                            <button style="margin-left:0px;" class="mt-3 btn btn-sm btn-dark btn-block"  id="submitting" type="button">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading...
                            </button>

                            <p id="resend-text" class="mt-3">
                                Didn't receive the OTP? Click <a href="{{ route('resend.otp') }}" id="resend-link" disabled>RESEND</a> 
                                <span id="timeout-time">after <span id="countdown-timer"></span> minutes</span>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="countdown_time" value="{{ session()->get('timeout_start_time') }}">
@endsection
@push('scripts')
    <script src="{{ asset('frontend/assets/js/pages/phone-verify.js') }}"></script>
@endpush