@extends('frontend.layouts.app', ['title' => 'Email Verification'])
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
                            Email Verification
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endpush
@section('content')
    <div class="main_content bg_gray">
        <div class="container">

            <div class="verification-container section">
                <h1>Verify Your Email</h1>
                <p>A verification link has been sent to your email address.</p>
                <p>If you didn’t receive the email, click the button below to resend it.</p>

                @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn btn-fill-out">Resend Verification Email</button>
                </form>
            </div>
        </div>
    </div>
@endsection
