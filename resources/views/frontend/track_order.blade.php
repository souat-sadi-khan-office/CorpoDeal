@extends('frontend.layouts.app', ['title' => get_settings('track_order_site_title')])
@section('meta')
<meta property="og:image:width" content="200">
    <meta property="og:image:height" content="200">
    <meta property="og:site_name" content="{{ get_settings('system_name') }}">
    
    <meta name="title" content="{{ get_settings('track_order_meta_title') }}">
    <meta name="author" content="{{ get_settings('system_name') }} : {{ route('home') }} : {{ get_settings('system_footer_contact_email') }}">
    <meta name="description" content="{{ get_settings('track_order_meta_description') }}">	

    <!-- For Open Graph -->
    <meta property="og:url" content="{{ url()->current() }}">	
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ get_settings('track_order_meta_title') }}">	
    <meta property="og:description" content="{{ get_settings('track_order_meta_description') }}.">	
    <meta property="og:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">	

    <!-- For Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:creator" content="{{ get_settings('system_name') }}" /> 
    <meta name="twitter:title" content="{{ get_settings('track_order_meta_title') }}" />
    <meta name="twitter:description" content="{{ get_settings('track_order_meta_description') }}" />	
    <meta name="twitter:site" content="{{ route('home') }}" />		
    <meta name="twitter:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">
    
    {!! get_settings('track_order_meta_article_tag') !!}
@endsection

@push('breadcrumb')
    <div class="breadcrumb_section page-title-mini">
        <div class="custom-container">
            <div class="row align-items-center">
                <div class="col-md-12 mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">
                                <i class="linearicons-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Track Your Order
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endpush

@section('content')
<div class="main_content bg_gray py-5">
    <div class="custom-container">
        <div class="login_register_wrap section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-6 col-md-10">
                        <div class="login_wrap">
                            <form action="" id="orderTrackingForm" method="POST">
                                <div class="padding_eight_all bg-white">
                                    <div class="heading_s1">
                                        <h3>Order Tracking</h3>
                                        <p>To track your order please enter your Order ID in the box below and press the "Track Order" button. This was given to you on your receipt and in the confirmation email you should have received.</p>
                                    </div>
                                    <div class="row">
                                        <div>
                                            <div class="alert alert-danger" style="display: none;" role="alert"></div>
                                        </div>
                                        <div>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" id="order_id" placeholder="Order ID" maxlength="14" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <button style="display: none;" type="submit" id="submit" class="btn btn-fill-out btn-block" name="login">Track Order</button>
                                    </div>
                                    <div class="form-group mb-3">
                                        <button class="btn btn-dark btn-block" disabled id="submitting" type="button">
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            Loading...
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#submitting').hide();
            $('#submit').show();

            $('#orderTrackingForm').on('submit', function (e) {
                e.preventDefault(); 

                $('.alert-danger').hide();
                
                var orderId = $('#order_id').val().trim(); 
                var errorMessage = $('#error_message');

                var orderIdPattern = /^#[\S]{11,13}$/; 
                if (orderId === '') {
                    $('.alert-danger').show();
                    $('.alert-danger').html("Order ID is required");
                } else if (!orderIdPattern.test(orderId)) {
                    $('.alert-danger').show();
                    $('.alert-danger').html("Order ID must start with <b>#</b> and contain 11 to 13 digits.");
                } else {
                    $('.alert-danger').hide();

                    $.ajax({
                        url: '/order/validate',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            order_id: orderId
                        },
                        success: function (response) {
                            if (response.valid) {
                                var cleanOrderId = orderId.replace('#', '');
                                window.location.href="/order/track/"+cleanOrderId;
                            } else {
                                $('.alert-danger').show();
                                $('.alert-danger').html("Invalid Order Id.");
                            }
                        },
                        error: function (xhr) {
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.errors; 
                                if (errors.order_id) {
                                    $('.alert-danger').show();
                                    $('.alert-danger').html(errors.order_id[0]);
                                }
                            } else {
                                $('.alert-danger').show();
                                $('.alert-danger').html("Server cannot handle this request. Please try again later.");
                            }
                        }
                    });
                }
            });
        });

    </script>
@endpush