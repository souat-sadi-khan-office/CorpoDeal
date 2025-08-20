@extends('frontend.layouts.app', ['title' => get_settings('compare_site_title')])
@section('meta')
<meta property="og:image:width" content="200">
    <meta property="og:image:height" content="200">
    <meta property="og:site_name" content="{{ get_settings('system_name') }}">
    
    <meta name="title" content="{{ get_settings('compare_meta_title') }}">
    <meta name="author" content="{{ get_settings('system_name') }} : {{ route('home') }} : {{ get_settings('system_footer_contact_email') }}">
    <meta name="description" content="{{ get_settings('compare_meta_description') }}">	

    <!-- For Open Graph -->
    <meta property="og:url" content="{{ url()->current() }}">	
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ get_settings('compare_meta_title') }}">	
    <meta property="og:description" content="{{ get_settings('compare_meta_description') }}.">	
    <meta property="og:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">	

    <!-- For Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:creator" content="{{ get_settings('system_name') }}" /> 
    <meta name="twitter:title" content="{{ get_settings('compare_meta_title') }}" />
    <meta name="twitter:description" content="{{ get_settings('compare_meta_description') }}" />	
    <meta name="twitter:site" content="{{ route('home') }}" />		
    <meta name="twitter:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">
    
    {!! get_settings('compare_meta_article_tag') !!}
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
                            Coupon Codes
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endpush

@section('content')
<style>
    .banner_section.slide_medium, .banner_section.slide_medium .carousel-item, .banner_section.slide_medium .banner_content_wrap, .banner_section.slide_medium .banner_content_wrap .carousel-item, .banner_section.shop_el_slider, .banner_section.shop_el_slider .carousel-item, .banner_section.shop_el_slider .banner_content_wrap, .banner_section.shop_el_slider .banner_content_wrap .carousel-item {
        height: 300px;
    }
</style>
<div class="banner_section slide_medium shop_banner_slider staggered-animation-wrap">
    <div id="carouselExampleControls" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active background_bg" data-img-src="{{ asset('frontend/assets/images/2.webp') }}"></div>
            <div class="carousel-item active background_bg" data-img-src="{{ asset('frontend/assets/images/3.webp') }}"></div>
        </div>

        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-bs-slide="prev">
            <i class="fas fa-arrow-left"></i>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-bs-slide="next">
            <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</div>

<div class="main_content bg_gray py-5">
    <div class="custom-container">
        <div class="row justify-content-center">
            @if (count($lifeTimeCoupon) > 0)
                <div class="col-md-12 content">
                    <h1>Life Time Free Coupon</h1>
                    <p>Inspired by Lazada - Southeast Asia's leading eCommerce platform</p>
                </div>
                @foreach ($lifeTimeCoupon as $coupon)
                    <div class="col-md-4">
                        <div class="pricing-tier-discount mb-3">
                            <div class="coupon__wrap" id="coupon_area_{{ $coupon->id }}">
                                <div class="coupon__title">
                                    <div class="couple__category text-center">{{ $coupon->coupon_code }}</div>
                                </div>
                                <div class="coupon__detail">
                                    <div class="coupon__price">
                                        @if ($coupon->discount_type == 'amount')
                                            Flat {{ format_price(convert_price($coupon->discount_amount)) }} Discount
                                        @else    
                                            {{ $coupon->discount_amount }}% Discount
                                        @endif
                                    </div>
                                    <div class="coupon__info">
                                        <span>or Maximum {{ format_price(convert_price($coupon->maximum_discount_amount)) }} Discount </span>
                                        <span>Minimum Shopping Amount: <strong>{{ format_price(convert_price($coupon->minimum_shipping_amount)) }}</strong>.</span>
                                    </div>
                                    <div class="coupon__footer text-end">
                                        <div class="coupon__condition ">
                                            <a id="collected_tier_{{ $coupon->id }}" style="cursor: auto;display: none;" href="javascript:;">Collected</a>
                                        </div>
                                        <div class="coupon__btn">
                                            <a data-name="{{ $coupon->coupon_code }}" data-id="{{ $coupon->id }}" class="copy_tier btn btn-sm btn-fill-out rounded" href="javascript:;">
                                                <i class="fas fa-copy"></i>
                                                Copy
                                            </a>
                                        </div>
                                    </div>
                                    <div class="coupon__border"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            @if (count($limitedTimeFreeCoupon) > 0)
                <div class="col-md-12 mt-5 content">
                    <h1>Limited Time Free Coupon</h1>
                    <p>Inspired by Lazada - Southeast Asia's leading eCommerce platform</p>
                </div>
                @foreach ($limitedTimeFreeCoupon as $coupon)
                    <div class="col-md-4">
                        <div class="pricing-tier-discount mb-3">
                            <div class="coupon__wrap">
                                <div class="coupon__title">
                                    <div class="couple__category text-center">{{ $coupon->coupon_code }}</div>
                                </div>
                                <div class="coupon__detail">
                                    <div class="coupon__price">
                                        @if ($coupon->discount_type == 'amount')
                                            Flat {{ format_price(convert_price($coupon->discount_amount)) }} Discount
                                        @else    
                                            {{ $coupon->discount_amount }}% Discount
                                        @endif
                                    </div>
                                    <div class="coupon__info">
                                        <span>or Maximum {{ format_price(convert_price($coupon->maximum_discount_amount)) }} Discount </span>
                                        <span>Minimum Shopping Amount: <strong>{{ format_price(convert_price($coupon->minimum_shipping_amount)) }}</strong>.</span>
                                        <span>From <strong>{{ get_system_date($coupon->start_date) }}</strong> - To {{ get_system_date($coupon->end_date) }}</span>
                                    </div>
                                    <div class="coupon__footer text-end">
                                        <div class="coupon__condition ">
                                            <a id="collected_tier" style="cursor: auto;display: none;" href="javascript:;">Collected</a>
                                        </div>
                                        <div class="coupon__btn">
                                            <a id="collect_tier" class="btn btn-sm btn-fill-out rounded" href="javascript:;">
                                                <i class="fas fa-copy"></i>
                                                Copy
                                            </a>
                                        </div>
                                    </div>
                                    <div class="coupon__border"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            @if (count($lifeTimeSellableCoupon) > 0)
                <div class="col-md-12 mt-5 content">
                    <h1>Life Time Buyable Coupons with Star Points</h1>
                    <p>Inspired by Lazada - Southeast Asia's leading eCommerce platform</p>
                </div>
                @foreach ($lifeTimeSellableCoupon as $coupon)
                    <div class="col-md-4">
                        <div class="pricing-tier-discount mb-3">
                            <div class="coupon__wrap">
                                <div class="coupon__title">
                                    <div class="couple__category text-center">{{ $coupon->coupon_code }}</div>
                                </div>
                                <div class="coupon__detail">
                                    <div class="coupon__price">
                                        @if ($coupon->discount_type == 'amount')
                                            Flat {{ format_price(convert_price($coupon->discount_amount)) }} Discount
                                        @else    
                                            {{ $coupon->discount_amount }}% Discount
                                        @endif
                                    </div>
                                    <div class="coupon__info">
                                        <span>or Maximum {{ format_price(convert_price($coupon->maximum_discount_amount)) }} Discount </span>
                                        <span>Minimum Shopping Amount: <strong>{{ format_price(convert_price($coupon->minimum_shipping_amount)) }}</strong>.</span>
                                        <span>You Need <strong>{{ $coupon->points_to_buy }} Star Points</strong> to buy</span>
                                    </div>
                                    <div class="coupon__footer text-end">
                                        <div class="coupon__condition ">
                                            <a id="collected_tier" style="cursor: auto;display: none;" href="javascript:;">Collected</a>
                                        </div>
                                        <div class="coupon__btn">
                                            <a data-code="{{ $coupon->coupon_code }}" data-id="{{ ($coupon->id) }}" id="collect_tier_{{ $coupon->id }}" class="collect_tier btn btn-sm btn-fill-out rounded" href="javascript:;">
                                                Collect
                                            </a>
                                        </div>
                                    </div>
                                    <div class="coupon__border"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            @if (count($limitedTimeSellableCoupon) > 0)
                <div class="col-md-12 mt-5 content">
                    <h1>Limited Time Buyable Coupons with Star Points</h1>
                    <p>Inspired by Lazada - Southeast Asia's leading eCommerce platform</p>
                </div>
                @foreach ($limitedTimeSellableCoupon as $coupon)
                    <div class="col-md-4">
                        <div class="pricing-tier-discount mb-3">
                            <div class="coupon__wrap">
                                <div class="coupon__title">
                                    <div class="couple__category text-center">{{ $coupon->coupon_code }}</div>
                                </div>
                                <div class="coupon__detail">
                                    <div class="coupon__price">
                                        @if ($coupon->discount_type == 'amount')
                                            Flat {{ format_price(convert_price($coupon->discount_amount)) }} Discount
                                        @else    
                                            {{ $coupon->discount_amount }}% Discount
                                        @endif
                                    </div>
                                    <div class="coupon__info">
                                        <span>or Maximum {{ format_price(convert_price($coupon->maximum_discount_amount)) }} Discount </span>
                                        <span>Minimum Shopping Amount: <strong>{{ format_price(convert_price($coupon->minimum_shipping_amount)) }}</strong>.</span>
                                        <span>From <strong>{{ get_system_date($coupon->start_date) }}</strong> <br>To <strong>{{ get_system_date($coupon->end_date) }}</strong></span>
                                        <span>You Need <strong>{{ $coupon->points_to_buy }} Star Points</strong> to buy</span>

                                    </div>
                                    <div class="coupon__footer text-end">
                                        <div class="coupon__condition ">
                                            <a id="collected_tier" style="cursor: auto;display: none;" href="javascript:;">Collected</a>
                                        </div>
                                        <div class="coupon__btn">
                                            <a data-code="{{ $coupon->coupon_code }}" data-id="{{ ($coupon->id) }}" id="collect_tier_{{ $coupon->id }}" class="collect_tier btn btn-sm btn-fill-out rounded" href="javascript:;">
                                                Collect
                                            </a>
                                        </div>
                                    </div>
                                    <div class="coupon__border"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
            
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        $(document).on('click', '.copy_tier', function() {
            let code = $(this).data('name');
            let id = $(this).data('id');
            
            let tempInput = $("<input>");
            $("body").append(tempInput);
            tempInput.val(code).select();
            document.execCommand("copy"); 
            tempInput.remove();
            
            $(this).remove();
            $('#collected_tier_'+id).show();
            $('#coupon_area_'+id).addClass('success');
        });

        $(document).on('click', '.collect_tier', function() {
            let id = $(this).data('id');
            let coupon_code = $(this).data('code');
            $(this).html('<i class="fas fa-spin fa-spinner"></i>');

            $.ajax({
                url: '/coupon/buy',
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
                    }

                    $('#collect_tier_'+id).html('Collect');
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
    </script>
@endpush