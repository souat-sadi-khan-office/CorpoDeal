@extends('frontend.layouts.app', ['title' => 'Checkout'])

@section('meta')
<meta property="og:image:width" content="200">
    <meta property="og:image:height" content="200">
    <meta property="og:site_name" content="{{ get_settings('system_name') }}">
    
    <meta name="title" content="{{ get_settings('checkout_meta_title') }}">
    <meta name="author" content="{{ get_settings('system_name') }} : {{ route('home') }} : {{ get_settings('system_footer_contact_email') }}">
    <meta name="description" content="{{ get_settings('checkout_meta_description') }}">	

    <!-- For Open Graph -->
    <meta property="og:url" content="{{ url()->current() }}">	
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ get_settings('checkout_meta_title') }}">	
    <meta property="og:description" content="{{ get_settings('checkout_meta_description') }}.">	
    <meta property="og:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">	

    <!-- For Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:creator" content="{{ get_settings('system_name') }}" /> 
    <meta name="twitter:title" content="{{ get_settings('checkout_meta_title') }}" />
    <meta name="twitter:description" content="{{ get_settings('checkout_meta_description') }}" />	
    <meta name="twitter:site" content="{{ route('home') }}" />		
    <meta name="twitter:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">
    
    {!! get_settings('checkout_meta_article_tag') !!}
@endsection
@push('breadcrumb')
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1>Checkout</h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Checkout</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endpush

@section('content')
    <div class="main_content">
        <div class="section">
            <div class="container">
                <div class="row">
                    @if (Auth::guard('customer')->check())
                        
                        <div class="col-lg-8 mx-auto">
                            <div class="toggle_info">
                                <span id="copoun-show-area">
                                    <i class="fas fa-tag"></i>
                                    Have a coupon?
                                    <a href="#coupon" data-bs-toggle="collapse" class="collapsed" aria-expanded="false">Click here to enter your code</a>
                                </span>
                            </div>
                            <div class="panel-collapse collapse coupon_form" id="coupon">
                                <div class="panel-body">
                                    <p>If you have a coupon code, please apply it below.</p>
                                    <div class="coupon field_form input-group">
                                        <input type="text" value="" id="coupon_code" class="form-control"
                                               placeholder="Enter Coupon Code..">
                                        <div class="input-group-append">
                                            <button class="btn btn-fill-out btn-sm" id="coupon_submit"
                                                    style="display:none;" type="button">Apply Coupon
                                            </button>
                                            <button class="btn btn-dark btn-block" id="coupon_submitting" type="button">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                                                Loading...
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-lg-8 mx-auto">
                            <span>
                                <i class="fas fa-tag"></i>
                                Want to apply a coupon?
                                <a href="{{ route('login') }}">Login to apply your coupon</a>
                            </span>
                        </div>
                    @endif

                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="medium_divider"></div>
                        <div class="divider center_icon">
                            <i class="linearicons-credit-card"></i>
                        </div>
                        <div class="medium_divider"></div>
                    </div>
                </div>
                <form action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="coupon_code" id="ori_coupon_code" value="">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="heading_s1">
                                <h4>Billing Details</h4>
                            </div>

                            @if (isset($userInfo['addresses']) && count($userInfo['addresses']) > 0)
                            <div class="toggle_info">
                                <a href="#address-book" data-bs-toggle="collapse" class="collapsed" aria-expanded="false">
                                    <i class="fas fa-map-alt"></i>
                                    Click here to select from your address book
                                </a>
                            </div>
                            <div class="panel-collapse collapse coupon_form" id="address-book">
                                <div class="panel-body">
                                    <div class="row">
                                        @foreach ($userInfo['addresses'] as $address)
                                            <div class="col-md-12 mb-2 choose-address" data-id="{{ $address['id'] }}">
                                                <label data-id="{{ $address['id'] }}" class="h-100 address-card">
                                                    <input name="plan" class="radio" type="radio" {{ $address['is_default'] == 1 ? 'checked' : '' }}>
                                                
                                                    <span class="plan-details">
                                                        <span class="plan-type">{{ $address['name'] }}</span>
                                                        <span>{{ $address['address'] }}</span>
                                                    </span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="form-group mb-3">
                                @if (isset($userInfo['addresses']) && count($userInfo['addresses']) > 0)
                                    {{-- <div class="custom_select" id="addressSelect">
                                        <select class="form-control">
                                            <option value="" disabled selected>Select Saved Details</option>
                                            @foreach ($userInfo['addresses'] as $address)
                                                <option value="{{ $address['id'] }}" data-option={{ $address['id'] }}>
                                                    {{ $address['address'] }}</option>
                                            @endforeach

                                        </select>
                                    </div> --}}
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-md-12 form-group mb-3">
                                    <label for="customer_name">Full Name <span class="text-danger">*</span></label>
                                    @if ($defaultAddress)
                                        <input type="text" required class="form-control" value="{{ $defaultAddress->first_name . ' ' . $defaultAddress->last_name }}" name="customer_name" id="customer_name" placeholder="">
                                    @else    
                                        <input type="text" required class="form-control" value="{{ $userInfo['name'] }}" name="customer_name" id="customer_name" placeholder="">
                                    @endif
                                </div>
    
                                <div class="col-md-6 form-group mb-3">
                                    <label for="customer_email">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" required class="form-control" value="{{ $userInfo['email'] }}" name="customer_email" id="customer_email"  placeholder="">
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="customer_phone">Phone Number <span class="text-danger">*</span></label>
                                    <input type="text" required class="form-control" value="{{ @$userInfo['phones']->phone_number }}" name="customer_phone" id="customer_phone" placeholder="">
                                </div>
    
                                <div class="col-md-6 form-group mb-3">
                                    <label for="billing_country">Country </label>
                                    <input type="text" name="billing_country" class="form-control" value="{{ $countryName }}" readonly>
                                </div>
    
                                <div class="col-md-6 form-group mb-3">
                                    <label for="billing_area">State</label>
                                    <input class="form-control" id="billing_area" type="text" name="billing_area" value="{{ $defaultAddress && $defaultAddress->city ? $defaultAddress->city->name : '' }}">
                                </div>
    
                                <div class="col-md-12 form-group mb-3">
                                    <div class="custom_select">
                                        <label for="billing_city">City</label>
                                        <select class="form-control" name="billing_city">
                                            <option value="" disabled selected>Select City</option>
                                            @foreach ($cities as $city)
                                                @if ($defaultAddress && $defaultAddress->city_id != null)
                                                    <option {{ $defaultAddress->city_id == $city->id ? 'selected' : '' }} value="{{ $city->id }}">{{ $city->name }}</option>
                                                @else   
                                                    <option {{ Session::has('user_city') && Session::get('user_city') == $city->name ? 'selected' : '' }} value="{{ $city->id }}">{{ $city->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
    
                                <div class="col-md-12 form-group mb-3">
                                    <label for="billing_address">Address <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="billing_address" name="billing_address" required placeholder="Address" value="{{ $defaultAddress ? $defaultAddress->address : '' }}">
                                </div>
    
                                <div class="col-md-12 form-group mb-3">
                                    <input type="text" class="form-control" name="billing_address2" placeholder="Address line 2" value="{{ $defaultAddress ? $defaultAddress->address_line_2 : '' }}">
                                </div>
    
                                <div class="ship_detail">
                                    <div class="col-md-12 form-group mb-3">
                                        <div class="chek-form">
                                            <div class="custome-checkbox">
                                                <input class="form-check-input" type="checkbox"
                                                       name="different_shipping_address" id="differentaddress">
                                                <label class="form-check-label label_info" for="differentaddress"><span>Ship
                                                        to a different address?</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row different_address">
                                        <div class="col-md-6 form-group mb-3">
    
                                            <input type="text" name="country" class="form-control" value="{{$countryName}}"
                                                   disabled>
                                            <input type="hidden" name="country_name" class="form-control"
                                                   value="{{$countryName}}">
                                            <input type="hidden" name="country_id" class="form-control"
                                                   value="{{$countryID}}">
    
                                        </div>

                                        <div class="col-md-6 form-group mb-3">
                                            <input class="form-control" type="text" name="shipping_area" placeholder="State">
                                        </div>
    
                                        <div class="col-md-12 form-group mb-3">
    
                                            <div class="custom_select">
                                                <select class="form-control" name="shipping_city">
                                                    <option value="" disabled selected>Select City</option>
                                                    @foreach ($cities as $city)
                                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                                    @endforeach
    
                                                </select>
                                            </div>
    
                                        </div>
                                        
                                        <div class="col-md-12 form-group mb-3">
                                            <input type="text" class="form-control" name="shipping_address"
                                                   placeholder="Address *">
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <input type="text" class="form-control" name="shipping_address2"
                                                   placeholder="Address line2">
                                        </div>
                                    </div>
                                </div>
                                <div class="heading_s1">
                                    <h4>Additional information</h4>
                                </div>
                                <div class="col-md-12 form-group mb-0">
                                    <textarea rows="5" class="form-control" placeholder="Order notes"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="order_review">
                                <div class="heading_s1">
                                    <h4>Your Orders</h4>
                                </div>
                                <div class="table-responsive order_table">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $sub_total = 0;
                                            $tierTotalPrice = 0;
                                            $tierTotalTax = 0;
                                        @endphp
                                        @if (count($models) > 0)
                                            @foreach ($models as $key => $model)
                                                @php
                                                    $sub_total += ($model['price'] * $model['quantity']);
                                                    $tierTotalPrice += ($model['price'] * $model['quantity']);
                                                    $tierTotalTax += ($model['tax'] * $model['quantity']);

                                                @endphp
                                                <tr>
                                                    <td>{{ $model['name'] }} <span
                                                            class="product-qty">x {{ $model['quantity'] }}</span></td>
                                                    <td class="text-right">{{ format_price(convert_price($model['price'] * $model['quantity'])) }}</td>
                                                    <input type="hidden" name="product[{{ $key }}][slug]"
                                                           value="{{ $model['slug'] }}">
                                                    <input type="hidden" name="product[{{ $key }}][qty]"
                                                           value="{{ $model['quantity'] }}">
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            @if($total_price!=premium_user_price($total_price) || $total_price>premium_user_price($total_price))

                                                <th width="75%">SubTotal
                                                    <del style="font-size: small"
                                                         class="text-success">{{format_price(convert_price($sub_total))}}</del>
                                                    <i class="fas fa-solid fa-crown"></i></th>
                                            @else
                                                <th width="75%">SubTotal</th>
                                            @endif

                                            <td class="text-right product-subtotal">
                                                {{ format_price(convert_price(premium_user_price($sub_total))) }}
                                            </td>
                                            <input type="hidden" name="subtotal_main" id="subtotal_main"
                                                   value="{{ round(convert_price(premium_user_price($sub_total)), 2) }}">
                                        </tr>


                                        <tr>
                                            <th>Shipping</th>
                                            <td class="text-danger text-right">
                                                + {{ format_price(convert_price($shipping_charge)) }}</td>
                                            <input type="hidden" id="shipping_charge_main" name="shipping_charge"
                                                   value="{{ convert_price($shipping_charge) }}">
                                        </tr>

                                        <tr>
                                            <th>Tax Total</th>
                                            <td class="text-danger text-right">
                                                + {{ format_price(convert_price($tax_amount)) }}</td>
                                            <input type="hidden" id="total_tax_main" name="total_tax"
                                                   value="{{ convert_price($tax_amount) }}">
                                        </tr>
                                        <tr>
                                            <th>Discount</th>
                                            <td class="text-success text-right">-
                                                <span id="discount_amount_show">{{ session()->get('currency_symbol') }}0.00</span>
                                            </td>
                                            <input type="hidden" name="discount" id="discount_amount_main" value="0">
                                            <input type="hidden" id="discount_amount_for_tier" value="0">
                                        </tr>
                                        <tr id="pricing_tier_area">
                                            <th>Pricing Tier Discount</th>
                                            <td class="text-success text-right">-
                                                <span id="pricing_tier_amount_show">{{ session()->get('currency_symbol') }}0.00</span>
                                            </td>
                                            <input type="hidden" id="currency_symbol"
                                                   value="{{ session()->get('currency_symbol') }}">
                                            <input type="hidden" id="pricing_tier_amount_main" value="0">
                                        </tr>


                                        <tr>
                                            <th>Total</th>
                                            <td class="product-subtotal text-right"
                                                id="product-total">{{ format_price(convert_price(premium_user_price($total_price))) }} </td>
                                            <input type="hidden" name="totalAmount" id="totalAmount"
                                                   value="{{ convert_price(premium_user_price($total_price)) }}">
                                        </tr>

                                        @if($total_price!=premium_user_price($total_price) || $total_price>premium_user_price($total_price))
                                            <tr>
                                                <th class="bg-primary-subtle">
                                                    <span>This is Premium User Price <i
                                                            class="fas fa-solid fa-crown"></i></span>
                                                </th>
                                                <td class="text-right text-bg-success">
                                                    <small>Saved</small> {{session()->get('currency_symbol'). round(convert_price($total_price)-convert_price(premium_user_price($total_price)),2)}}
                                                </td>
                                            </tr>
                                            {{--                                            <tr>--}}
                                            {{--                                                <th>Premium User Price</th>--}}
                                            {{--                                                <td class="premium_user_price text-success"--}}
                                            {{--                                                    id="product-premium_user_price"> <strong>{{ format_price(convert_price(premium_user_price($total_price))) }}</strong> </td>--}}
                                            <input type="hidden" name="saved" id="saved"
                                                   value="{{ $total_price-premium_user_price($total_price) }}">
                                            {{--                                            </tr>--}}
                                        @endif
                                        </tfoot>
                                    </table>
                                </div>

                                @if (get_settings('pricing_tier') == 1 && $tier != null)
                                    <input type="hidden" name="multi_tier_applier" id="multi_tier_applier" value="0">
                                    <div class="pricing-tier-discount mb-3">
                                        <div class="coupon__wrap">
                                            <div class="coupon__title">
                                                <div class="couple__category">{{ $tier->name }}</div>
                                                <div class="coupon__max">You can collect</div>
                                            </div>
                                            <div class="coupon__detail">
                                                <div class="coupon__price">
                                                    @if ($tier->discount_type == 'percent')
                                                        {{ round($tier->discount_amount) }}% Discount
                                                    @else
                                                        Flat {{ format_price(round($tier->discount_amount)) }} Discount
                                                    @endif
                                                </div>
                                                <div class="coupon__info">
                                                    <span>For ordering above {{ format_price(round($tier->threshold)) }}  </span>
                                                    <span>Product tax {{ $tier->with_product_tax == 'yes' ? 'will' : "won't" }} counted for getting this offer.</span>
                                                </div>
                                                <div class="coupon__footer text-end">
                                                    <div class="coupon__condition ">
                                                        <a id="collected_tier" style="cursor: auto;display: none;"
                                                           href="javascript:;">Collected</a>
                                                    </div>
                                                    <div class="coupon__btn">
                                                        <a id="collect_tier" class="btn btn-sm btn-fill-out rounded"
                                                           href="javascript:;">Collect</a>
                                                    </div>
                                                </div>
                                                <div class="coupon__border"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="tier_discount_type" id="tier_discount_type"
                                           value="{{ $tier->discount_type }}">
                                    <input type="hidden" name="tier_discount_amount" id="tier_discount_amount"
                                           value="{{ $tier->discount_amount }}">
                                    <input type="hidden" name="tier_total_price" id="tier_total_price"
                                           value="{{ round(convert_price(premium_user_price($tierTotalPrice)), 2) }}">
                                    <input type="hidden" name="tier_total_tax" id="tier_total_tax"
                                           value="{{ round(convert_price($tierTotalTax), 2) }}">
                                @endif

                                <div class="payment_method">
                                    <div class="heading_s1">
                                        <h4>Payment</h4>
                                    </div>
                                    <div class="payment_option">
                                        {{-- <div class="custome-radio">
                                            <input class="form-check-input" type="radio" name="payment_option"
                                                id="exampleRadios5" value="paypal" checked>
                                            <label class="form-check-label" for="exampleRadios5">Paypal</label>
                                            <p data-method="paypal" class="payment-text">Pay via PayPal; you can pay with
                                                your credit card if you don't have a PayPal account.</p>
                                        </div> --}}

                                        @if (env('SSLCOMMERZ_SANDBOX') != 'true')
                                            <div class="custome-radio">
                                                <input class="form-check-input" type="radio" name="payment_option"
                                                       id="exampleRadios3" value="sslcommerz">
                                                <label class="form-check-label" for="exampleRadios3">SslCommerz</label>
                                                <p data-method="sslcommerz" class="payment-text">Pay via SslCommerz; you
                                                    can pay with your credit card if you don't have a SslCommerz
                                                    account.</p>
                                            </div>
                                        @endif

                                        <div class="custome-radio">
                                            <input class="form-check-input" type="radio" name="payment_option"
                                                   id="cash_on_delivery" value="cash_on_delivery" checked>
                                            <label class="form-check-label" for="cash_on_delivery">Cash on
                                                Delivery</label>
                                            <p data-method="cash_on_delivery" class="payment-text"> You Have to Pay
                                                Delivery Charge First. </p>
                                        </div>

                                        @if(negative_balance()>=convert_price($total_price))
                                            <div class="custome-radio">
                                                <input class="form-check-input" type="radio" name="payment_option"
                                                       id="exampleRadios2" value="negative_balance">
                                                <label class="form-check-label" for="exampleRadios2">Via Available
                                                    Negative
                                                    Balance</label>
                                                <p data-method="negative_balance" class="payment-text"> You Have <span
                                                        class="amount">{{negative_balance()}} {{session()->get('currency_code')}}</span>
                                                    and You Can Continue With This.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <input type="hidden" name="currency_code" value="{{$currencyCode}}">
                                {{-- <button type="submit" class="btn btn-fill-out btn-block">Place Order</button> --}}
                                <button type="button" id="place_order" class="btn btn-fill-out btn-block">
                                    Place Order
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .left {
            padding-left: 0px !important;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{asset('backend/assets/js/sweetalert2@11.js')}}"></script>
    <script>
        $(document).on('click', '#place_order', function() {
            Swal.fire({
                icon: "info",
                title: "Thank You!",
                html: "Thank you for visiting! Our website will open soon. Stay tuned for something great!"
            });
        });

        $(document).ready(function () {
            _newsletterFormValidation();

            $('#coupon_submitting').hide();
            $('#coupon_submit').show();

            $(document).on('click', '#coupon_submit', function () {
                let couponField = $('#coupon_code');
                if (!couponField.length) {
                    toastr.error('Error: Coupon code input field not found.');
                    return false;
                }

                let coupon_code = $('#coupon_code').val().trim();
                if (coupon_code === undefined || coupon_code === '') {
                    toastr.warning('Warning: Please enter a coupon code.');
                    return false;
                }

                $('#coupon_submitting').show();
                $('#coupon_submit').hide();

                $.ajax({
                    url: '/coupon/check',
                    type: 'POST',
                    data: {
                        coupon: coupon_code
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        if (!data.status) {
                            if (data.validator) {
                                for (const [key, messages] of Object.entries(data.message)) {
                                    messages.forEach(message => {
                                        toastr.warning(message);
                                    });
                                }
                            } else {
                                toastr.warning(data.message);
                            }
                        } else {
                            toastr.success(data.message);

                            $('.coupon_form').hide();
                            $('#copoun-show-area').addClass('text-success');
                            $('#copoun-show-area').html('<i class="fas fa-tag"></i> <b>' + coupon_code + '</b> coupon is added.');
                            $('#product-total').html(data.total_amount);
                            $('#discount_amount_show').html(data.formatted_amount);
                            $('#discount_amount_main').val(data.discount_amount);
                            $('#discount_amount_for_tier').val(data.discount_amount);
                            $('#ori_coupon_code').val(coupon_code);
                        }

                        $('#coupon_submitting').hide();
                        $('#coupon_submit').show();
                    },
                    error: function (data) {
                        var jsonValue = $.parseJSON(data.responseText);
                        const errors = jsonValue.errors;
                        if (errors) {
                            var i = 0;
                            $.each(errors, function (key, value) {
                                const first_item = Object.keys(errors)[i]
                                const message = errors[first_item][0];
                                if ($('#' + first_item).length > 0) {
                                    $('#' + first_item).parsley().removeError(
                                        'required', {
                                            updateClass: true
                                        });
                                    $('#' + first_item).parsley().addError(
                                        'required', {
                                            message: value,
                                            updateClass: true
                                        });
                                }
                                toastr.error(value);
                                i++;

                            });
                        } else {
                            toastr.warning(jsonValue.message);
                        }

                        $('#coupon_submitting').hide();
                        $('#coupon_submit').show();
                    }
                });

            })

            $(document).on('click', '.choose-address', function () {
                const addressId = $(this).data("id");

                $.ajax({
                    url: '/order/get_address/' + addressId,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            const address = response.address;

                            $('input[name="customer_name"]').val(address.first_name + ' ' +
                                address.last_name);
                            $('input[name="customer_company"]').val(address.company_name);
                            $('select[name="billing_city"]').val(address.city_id);
                            $('input[name="billing_area"]').val(address.area);
                            $('input[name="billing_address"]').val(address.address);
                            $('input[name="billing_address2"]').val(address.address_line_2 ||
                                '');
                        } else {
                            alert('Failed to load address details.');
                        }
                    },
                    error: function (xhr) {
                        console.error(xhr);
                        alert('An error occurred while fetching address details.');
                    }
                });
            });

            $(document).on('click', '#collect_tier', function () {
                $('.toggle_info').remove();
                $('.panel-collapse').remove();
                let tier_discount_type = $('#tier_discount_type').val();
                let tier_discount_amount = parseFloat($('#tier_discount_amount').val());

                let discount_amount = parseFloat($('#discount_amount_for_tier').val()) || 0;
                let shipping_charge = parseFloat($('#shipping_charge_main').val()) || 0;
                let total_tax = parseFloat($('#total_tax_main').val()) || 0;
                let sub_total = parseFloat($('#subtotal_main').val()) || 0;
                let currency_symbol = $('#currency_symbol').val();

                let final_total = 0;

                if (tier_discount_type === 'flat') {
                    final_total = sub_total - tier_discount_amount;
                    $('#pricing_tier_amount_show').text(currency_symbol + tier_discount_amount.toFixed(2))
                } else if (tier_discount_type === 'percent') {
                    let discount_value = (sub_total * tier_discount_amount) / 100;
                    final_total = sub_total - discount_value;
                    $('#pricing_tier_amount_show').text(currency_symbol + discount_value.toFixed(2))
                } else {
                    // If no valid discount type
                    final_total = sub_total;
                    $('#pricing_tier_amount_show').text(currency_symbol + "0.00");
                }

                // Add tax and shipping charge to final total
                final_total += total_tax + shipping_charge;

                final_total -= discount_amount;

                // Update the total on the UI
                $('#product-total').text(currency_symbol + final_total.toFixed(2));

                $('#multi_tier_applier').val(1);

                $(this).hide();
                $('.coupon__wrap').addClass('success');
                $('.coupon__btn').remove();
                $('#collected_tier').show();
            });
        });

    </script>
@endpush
