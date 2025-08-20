@extends('frontend.layouts.app', ['title' => get_settings('contact_site_title')])
@section('meta')
<meta property="og:image:width" content="200">
    <meta property="og:image:height" content="200">
    <meta property="og:site_name" content="{{ get_settings('system_name') }}">
    
    <meta name="title" content="{{ get_settings('home_meta_title') }}">
    <meta name="author" content="{{ get_settings('system_name') }} : {{ route('home') }} : {{ get_settings('system_footer_contact_email') }}">
    <meta name="description" content="{{ get_settings('contact_meta_description') }}">	

    <!-- For Open Graph -->
    <meta property="og:url" content="{{ url()->current() }}">	
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ get_settings('contact_meta_title') }}">	
    <meta property="og:description" content="{{ get_settings('contact_meta_description') }}.">	
    <meta property="og:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">	

    <!-- For Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:creator" content="{{ get_settings('system_name') }}" /> 
    <meta name="twitter:title" content="{{ get_settings('contact_meta_title') }}" />
    <meta name="twitter:description" content="{{ get_settings('contact_meta_description') }}" />	
    <meta name="twitter:site" content="{{ route('home') }}" />		
    <meta name="twitter:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">
    
    {!! get_settings('contact_meta_article_tag') !!}
@endsection

@push('breadcrumb')
    {{-- <div class="breadcrumb_section page-title-mini">
        <div class="custom-container">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">
                                <i class="linearicons-home"></i>
                            </a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1>Contact</h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">
                                Home
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Contact</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endpush

@section('content')
<div class="section pb_70">
    <div class="container">
        <div class="row">
            <div class="col-xl-4 col-md-6">
                <div class="contact_wrap contact_style3">
                    <div class="contact_icon">
                        <i class="linearicons-map2"></i>
                    </div>
                    <div class="contact_text">
                        <span>Address</span>
                        <p>{{ get_settings('system_footer_contact_address') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="contact_wrap contact_style3">
                    <div class="contact_icon">
                        <i class="linearicons-envelope-open"></i>
                    </div>
                    <div class="contact_text">
                        <span>Email Address</span>
                        <a href="mailto:{{ get_settings('system_footer_contact_email') }}">{{ get_settings('system_footer_contact_email') }} <br><br> </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="contact_wrap contact_style3">
                    <div class="contact_icon">
                        <i class="linearicons-tablet2"></i>
                    </div>
                    <div class="contact_text">
                        <span>Phone</span>
                        <p>{{ get_settings('system_footer_contact_phone') }} <br><br></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section pt-0">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="heading_s1">
                    <h2>Get In touch</h2>
                </div>
                <div class="field_form">
                    <form method="POST" id="contact_form" action="{{ route('contact.submit') }}">
                        <div class="row">
                            <div class="form-group col-md-6 mb-3">
                                <input required placeholder="Enter Name *" id="first-name" class="form-control" name="name" type="text" 
                                value="{{ auth()->guard('customer')->check() ? auth()->guard('customer')->user()->name : '' }}">
                            </div>
                            <div class="form-group col-md-6 mb-3">
                                <input required="" placeholder="Enter Email *" id="email" class="form-control" name="email" type="email" value="{{ auth()->guard('customer')->check() ? auth()->guard('customer')->user()->email : '' }}">
                            </div>
                            <div class="form-group col-md-6 mb-3">
                                <input required="" placeholder="Enter Phone No. *" id="phone" class="form-control" name="phone">
                            </div>
                            <div class="form-group col-md-6 mb-3">
                                <input placeholder="Enter Subject" required id="subject" class="form-control" name="subject">
                            </div>
                            <div class="form-group col-md-12 mb-3">
                                <textarea required="" placeholder="Message *" id="description" class="form-control" name="message" rows="4"></textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <button type="submit" style="display: none;" title="Submit Your Message!" class="btn btn-fill-out" id="contact_submit">Send Message</button>
                                <button type="button" disabled id="contact_submitting" class="btn btn-fill-out" id="contact_submitting">
                                    <i class="fas fa-spin fa-spinner text-white"></i>
                                </button>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div id="alert-msg" class="alert-msg text-center"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 pt-2 pt-lg-0 mt-4 mt-lg-0">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.027951234913!2d90.41347647411571!3d23.782018978648825!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c70066af71ad%3A0xe8d77e0731081e3!2sProfile%20House!5e0!3m2!1sen!2sbd!4v1732418647290!5m2!1sen!2sbd" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('frontend/assets/js/contact.js') }}"></script>
@endpush