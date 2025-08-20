@extends('frontend.layouts.app', ['title' => get_settings('order_confirmation_site_title')])
@section('meta')
<meta property="og:image:width" content="200">
    <meta property="og:image:height" content="200">
    <meta property="og:site_name" content="{{ get_settings('system_name') }}">

    <meta name="title" content="{{ get_settings('order_confirmation_meta_title') }}">
    <meta name="author" content="{{ get_settings('system_name') }} : {{ route('home') }} : {{ get_settings('system_footer_contact_email') }}">
    <meta name="description" content="{{ get_settings('order_confirmation_meta_description') }}">

    <!-- For Open Graph -->
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ get_settings('order_confirmation_meta_title') }}">
    <meta property="og:description" content="{{ get_settings('order_confirmation_meta_description') }}.">
    <meta property="og:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">

    <!-- For Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:creator" content="{{ get_settings('system_name') }}" />
    <meta name="twitter:title" content="{{ get_settings('order_confirmation_meta_title') }}" />
    <meta name="twitter:description" content="{{ get_settings('order_confirmation_meta_description') }}" />
    <meta name="twitter:site" content="{{ route('home') }}" />
    <meta name="twitter:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">

    {!! get_settings('order_confirmation_meta_article_tag') !!}
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
                            Order Confirmation
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endpush
@section('content')
<style>
    .fs-12 {
        font-size: 12px;
    }

    .fs-15 {
        font-size: 15px;
    }

    .name {
        margin-bottom: -2px;
    }

    .product-details {
        margin-top: 13px;
    }
</style>
<div class="main_content bg_gray py-5">

    <div class="custom-container">
        <div class="d-flex justify-content-center row">
            <div class="col-md-10">
                <div class="receipt bg-white p-3 rounded">

                    <div class="thank-you-logo">
                        <img src="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}" alt="System Logo" title="System Logo" width="120">
                    </div>

                    <h4 class="mt-2 mb-3">Your order is confirmed!</h4>

                    <h6 class="name">Hello <strong>{{ $details['user_name'] }}</strong>,</h6><span class="fs-12 text-black-50">your order has been confirmed and will be shipped in two days</span>
                    <hr>
                    <div class="d-flex flex-row justify-content-between align-items-center order-details">
                        <div><span class="d-block fs-12">Order date</span><span class="font-weight-bold">{{ $details['created_at'] }}</span></div>
                        <div><span class="d-block fs-12">Order number</span><span class="font-weight-bold">{{ strtoupper($details['unique_id']) }}</span></div>
                        <div><span class="d-block fs-12">Payment method</span><span class="font-weight-bold">{{ $details['gateway_name'] }}</span></div>
                        <div><span class="d-block fs-12">Payment Currency</span><span class="font-weight-bold text-success">{{ $details['currency'] }}</span></div>
                    </div>
                    <hr>
                    @foreach ($details['details'] as $d)
                        @php
                            $product = App\Models\Product::select('thumb_image')->find($d->id)
                        @endphp
                        <div class="d-flex justify-content-between align-items-center product-details">
                            <div class="d-flex flex-row product-name-image">
                                <img class="rounded" title="{{ $d->name }} Photo" alt="{{ $d->name }}" src="{{ asset($product->thumb_image) }}" width="80">
                                <div class="d-flex flex-column justify-content-between m-2">
                                    <div>
                                        <span class="d-block font-weight-bold p-name">
                                            {{ $d->name }}
                                        </span>
                                    </div>
                                    <span class="fs-12">Qty: {{ $d->qty }}</span>
                                    <span class="fs-12">Unit Price: {{ $d->unit_price }}</span>
                                </div>
                            </div>
                            <div class="product-price">
                                <h6>{{ $d->total_price }}</h6>
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-5 amount row">
                        <div class=" col-md-6">
                            Shipping Address
                            <address>
                                <strong>{{ $details['user_name'] }}</strong><br>
                                {!! add_line_breaks($details['billing_address']) !!} <br>
                                Phone: {{ $details['phone'] }}<br>
                                @if ($details['user_company'])
                                    Company: {{ ucfirst($details['user_company']) }} <br>
                                @endif
                                Email: {{ $details['email'] }}
                            </address>
                        </div>
                        <div class="col-md-6">
                            <div class="billing">
                                @if(isset($details['premium_user_discount_amount']) && $details['premium_user_discount_amount'])
                                    <div class="d-flex justify-content-between mt-1">
                                        <span class="font-weight-bold">Premium User Discount</span>
                                        <span class="font-weight-bold text-success">-{{ $details['premium_user_discount_amount'] }}</span>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between">
                                    <span>Subtotal</span>
                                    <span class="font-weight-bold">{{ $details['order_amount'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span>Shipping fee</span>
                                    <span class="font-weight-bold">{{@$details['shipping_charge']??0}}</span>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span>Tax</span>
                                    <span class="font-weight-bold">{{ $details['tax_amount'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span class="text-success">Discount</span>
                                    <span class="font-weight-bold text-success">{{ $details['discount_amount'] }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mt-1">
                                    <span class="font-weight-bold">Total</span>
                                    <span class="font-weight-bold text-success">{{ $details['final_amount'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <span class="d-block">Expected delivery date</span>
                    <span class="font-weight-bold text-success">12 March 2020</span>
                    <span class="d-block mt-3 text-black-50 fs-15">We will be sending a shipping confirmation email when the item is shipped!</span>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center footer">
                        <div class="thanks">
                            <span class="d-block font-weight-bold">
                                Thanks for shopping
                            </span>
                            <strong>{{ get_settings('system_name') }} team</strong>
                        </div>
                        <div class="d-flex flex-column justify-content-end align-items-end">
                            <span class="d-block font-weight-bold">Need Help?</span>
                            <span>Call - {{ get_settings('system_footer_contact_phone') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
@push('scripts')

@endpush
