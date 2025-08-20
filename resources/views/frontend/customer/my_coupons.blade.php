@extends('frontend.layouts.app', ['title' => 'My Coupon History | ', get_settings('system_name')])
@push('page_meta_information')
    
    <link rel="canonical" href="{{ route('home') }}" />
    <meta name="referrer" content="origin">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">

    <meta name="title" content="My Coupon History | {{ get_settings('system_name') }}">
@endpush
@push('breadcrumb')
    <div class="breadcrumb_section page-title-mini">
        <div class="custom-container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">
                                <i class="linearicons-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                Account
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            My Coupon Points
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endpush

@section('content')
    <div class="section bg_gray">
        <div class="custom-container">
            <div class="row">
                @include('frontend.customer.partials.sidebar')
                <div class="col-lg-9 col-md-8 dashboard_content">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            @include('frontend.customer.partials.header')
                        </div>

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h1 class="h5">My Coupon History</h1>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 table-responsive">
                                            @if (count($models) > 0)
                                                <div class="row justify-content-center">
                                                    @foreach ($models as $coupon)
                                                        @if ($coupon->coupon)
                                                            <div class="col-md-6 mb-3">
                                                                <div class="coupon__wrap" id="coupon_area_{{ $coupon->coupon->id }}">
                                                                    <div class="coupon__title">
                                                                        <div class="couple__category text-center">{{ $coupon->coupon->coupon_code }}</div>
                                                                    </div>
                                                                    <div class="coupon__detail">
                                                                        <div class="coupon__price">
                                                                            @if ($coupon->coupon->discount_type == 'amount')
                                                                                Flat {{ format_price(convert_price($coupon->coupon->discount_amount)) }} Discount
                                                                            @else    
                                                                                {{ $coupon->coupon->discount_amount }}% Discount
                                                                            @endif
                                                                        </div>
                                                                        <div class="coupon__info">
                                                                            <span>or Maximum {{ format_price(convert_price($coupon->coupon->maximum_discount_amount)) }} Discount </span>
                                                                            <span>Minimum Shopping Amount: <strong>{{ format_price(convert_price($coupon->coupon->minimum_shipping_amount)) }}</strong>.</span>
                                                                        </div>
                                                                        <div class="coupon__footer text-end">
                                                                            <div class="coupon__condition ">
                                                                                <a id="collected_tier_{{ $coupon->coupon->id }}" style="cursor: auto;display: none;" href="javascript:;">Collected</a>
                                                                            </div>
                                                                            <div class="coupon__btn">
                                                                                <a data-name="{{ $coupon->coupon->coupon_code }}" data-id="{{ $coupon->coupon->id }}" class="copy_tier btn btn-sm btn-fill-out rounded" href="javascript:;">
                                                                                    <i class="fas fa-copy"></i>
                                                                                    Copy
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                        <div class="coupon__border"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <ul class="pagination mt-3 pagination_style1">
                                                            @if ($models->onFirstPage())
                                                                <li class="page-item disabled"><span class="page-link">«</span></li>
                                                            @else
                                                                <li class="page-item"><a class="page-link" href="{{ $models->previousPageUrl() }}">«</a></li>
                                                            @endif
                                                
                                                            @for ($i = 1; $i <= $models->lastPage(); $i++)
                                                                <li class="page-item {{ $i === $models->currentPage() ? 'active' : '' }}">
                                                                    <a class="page-link" href="{{ $models->url($i) }}">{{ $i }}</a>
                                                                </li>
                                                            @endfor
                                                
                                                            @if ($models->hasMorePages())
                                                                <li class="page-item"><a class="page-link" href="{{ $models->nextPageUrl() }}"> »</a></li>
                                                            @else
                                                                <li class="page-item disabled"><span class="page-link">»</span></li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endif

                                            
                                        </div>
                                    </div>
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
    </script>
@endpush