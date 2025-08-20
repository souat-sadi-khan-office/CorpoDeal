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
                <div class="col-md-12 mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">
                                <i class="linearicons-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Product Comparison
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endpush
@push('styles')
    <style>
        p {
            margin-bottom: 10px;
            font-size: 15px;
            color: #666;
            line-height: 26px;
        }

        .product-compare-dropdown {
            max-height: 400px;
            overflow-y: auto; 
            display: none;
            background-color: white;
            z-index: 1000;
            box-shadow: 0 10px 15px rgba(0,0,0,.2) , 0 1px 0 rgba(0,0,0,.05) inset , 0 -5px 0 0 #fff
        }

        .product-compare-dropdown div {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #ddd;
        }

        .product-compare-dropdown div:hover {
            background-color: var(--primary-color);
            color: #fff;
            text-decoration: underline;
        }
    </style>
@endpush
@section('content')
<div class="main_content bg_gray py-5">
    @if (count($product_id_array) > 0)
        <div class="custom-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="compare_box">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                    <tr class="pr_image text-center">
                                        <td class="row_title">
                                            <h5>Product Comparison {{ count($product_id_array) }} </h5>
                                            <p>Find and select products to see the differences and similarities between them</p>
                                        </td>
                                        @foreach ($models as $key => $model)
                                            <td rowspan="" class="row_img">
                                                <img style="width: 250px;" src="{{ $model['image'] }}" alt="{{ $model['name'] }}">
                                                <br>
                                                <div class="mb-3">
                                                    <a href="{{ route('slug.handle', $model['slug']) }}">{{ $model['name'] }}</a>
                                                </div>
                                                <div class="mt-3">
                                                    @if (isset($model['discount_type']))
                                                        <span class="price">{{ format_price(convert_price($model['discounted_price'])) }}</span>
                                                        <del>{{ format_price(convert_price($model['unit_price'])) }}</del>
                                                    @else
                                                        <span class="price">{{ format_price(convert_price($model['unit_price'])) }}</span>
                                                    @endif
                                                </div>
                                                <div class="mt-4">
                                                    <a class="mt-4" href="{{ route('compare.remove', $model['slug']) }}"><span>Remove</span> <i class="fa fa-times"></i></a>
                                                </div>
                                            </td>
                                            
                                        @endforeach
                                        @if (count($product_id_array) < 2)
                                            <td style="vertical-align: top;" rowspan="5" class="row_img">
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" id="ongoing-search-product-one" placeholder="Search & Select Product" autocomplete="off">
                                                    <span class="bg_white input-group-text">
                                                        <i class="fas fa-search"></i>
                                                    </span>
                                                </div>
                                                <div id="ongoing-product-list-one" class="product-compare-dropdown"></div>
                                            </td>
                                        @endif
                                        @if (count($product_id_array) < 3)
                                            <td style="vertical-align: top;" rowspan="5" class="row_img">
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" id="ongoing-search-product-two" placeholder="Search & Select Product" autocomplete="off">
                                                    <span class="bg_white input-group-text">
                                                        <i class="fas fa-search"></i>
                                                    </span>
                                                </div>
                                                <div id="ongoing-product-list-two" class="product-compare-dropdown"></div>
                                            </td>
                                        @endif
                                    </tr>
                                    
                                    <tr class="pr_title">
                                        <td class="row_title">Brand</td>
                                        @foreach ($models as $model)
                                            <td class="product_name">
                                                <a href="{{ $model['brand_slug'] }}">{{ $model['brand'] }}</a>
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr class="pr_rating">
                                        <td class="row_title">Rating</td>
                                        @foreach ($models as $model)
                                            <td>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:{{ $model['average_rating_percent'] }}%"></div>
                                                    </div> <br>
                                                    <span class="rating_num">Based on ({{ $model['average_rating_count'] }}) reviews</span>
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr class="pr_stock">
                                        <td width="15%" class="row_title">Item Availability</td>
                                        @foreach ($models as $model)
                                            @if ($model['stock'] == 'In Stock')
                                                <td class="row_stock">
                                                    <span class="in-stock">
                                                        @if ($model['stage'] == 'normal')
                                                            In Stock
                                                        @elseif ($model['stage'] == 'pre-order')   
                                                            Pre Order
                                                        @elseif ($model['stage'] == 'upcoming')   
                                                            Upcoming
                                                        @endif
                                                    </span>
                                                </td>
                                            @else 
                                                <td class="row_stock"><span class="out-stock">Out Of Stock</span></td>
                                            @endif
                                        @endforeach
                                    </tr>
                                    <tr class="description">
                                        <td class="row_title">Summery</td>
                                        @foreach ($models as $model)
                                            <td class="row_text">
                                                <p>
                                                    @foreach ($model['summery'] as $type)
                                                        {{ $type['type_name'] }} : {!! nl2br($type['attr_name']) !!} <br>
                                                    @endforeach
                                                </p>
                                            </td>
                                        @endforeach
                                    </tr>

                                    @foreach($specifications as $key => $item)
                                        <tr class="pr_spec_key">
                                            <td colspan="4" class="row_title">{{ $key }}</td>
                                        </tr>
                                        @foreach ($item as $type => $value)
                                            
                                            <tr class="pr_add_to_cart">
                                                <td>{{ $type }}</td>
                                                @foreach ($models as $model)
                                                    @if (isset($value[$model['id']]))
                                                        <td>{{ $value[$model['id']] }}</td>
                                                    @else    
                                                        <td></td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                        @endforeach
                                            
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else    
        <div class="custom-container">
            <div class="login_register_wrap section">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-6 col-md-10">
                            <div class="login_wrap">
                                <div class="padding_eight_all bg-white">
                                    <div class="heading_s1">
                                        <h3>Product Comparison</h3>
                                        <p>You have not chosen any products to compare.</p>
                                    </div>
                                    <div class="row">

                                        <div>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" id="search-product-one" placeholder="Search & Select Product" autocomplete="off">
                                                <span class="bg_white input-group-text">
                                                    <i class="fas fa-search"></i>
                                                </span>
                                            </div>
                                            <div id="product-list-one" class="product-compare-dropdown"></div>
                                        </div>

                                        <div>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" id="search-product-two" placeholder="Search & Select Product" autocomplete="off">
                                                <span class="bg_white input-group-text">
                                                    <i class="fas fa-search"></i>
                                                </span>
                                            </div>
                                            <div id="product-list-two" class="product-compare-dropdown"></div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <button style="display: none;" type="submit" id="submit" class="btn btn-fill-out btn-block" name="login">View Comparison</button>
                                    </div>
                                    <div class="form-group mb-3">
                                        <button class="btn btn-dark btn-block" disabled id="submitting" type="button">
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            Loading...
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
<input type="hidden" id="compare_product_id_one" value="">
<input type="hidden" id="compare_product_id_two" value="">
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#submitting').hide();
            $('#submit').show();

            $(document).on('keyup', '#search-product-one', function() {
                var query = $(this).val();

                if (query.length > 0) {
                    $.ajax({
                        url: '/ajax-product-search',
                        method: 'GET',
                        data: { search: query },
                        success: function(data) {
                            $('#product-list-one').html('').hide();
                            if (data.length > 0) {
                                $.each(data, function(index, product) {
                                    $('#product-list-one').append('<div data-id="'+ product.slug +'">' + product.name + '</div>');
                                });
                                $('#product-list-one').show();
                            } else {
                                $('#product-list-one').hide(); 
                            }
                        }
                    });
                } else {
                    $('#product-list-one').html('').hide();
                }
            });

            $(document).on('click', '#product-list-one div', function() {
                let id = $(this).data('id');
                $('#search-product-one').val($(this).text());
                $('#compare_product_id_one').val(id);
                $('#product-list-one').hide();
            });

            $(document).on('keyup', '#search-product-two', function() {
                var query = $(this).val();

                if (query.length > 0) {
                    $.ajax({
                        url: '/ajax-product-search',
                        method: 'GET',
                        data: { search: query },
                        success: function(data) {
                            $('#product-list-two').html('').hide();
                            if (data.length > 0) {
                                $.each(data, function(index, product) {
                                    $('#product-list-two').append('<div data-id="'+ product.slug +'">' + product.name + '</div>');
                                });
                                $('#product-list-two').show();
                            } else {
                                $('#product-list-two').hide(); 
                            }
                        }
                    });
                } else {
                    $('#product-list-two').html('').hide();
                }
            });

            $(document).on('click', '#product-list-two div', function() {
                let id = $(this).data('id');

                $('#search-product-two').val($(this).text());
                $('#compare_product_id_two').val(id);
                $('#product-list-two').hide();
            });

            $(document).on('click', '#submit', function() {
                let id_one = $('#compare_product_id_one').val();
                let id_two = $('#compare_product_id_two').val();

                if(id_one === '' && id_two === '') {
                    toastr.warning('Please select product first');
                    return false;
                }

                $('#submitting').show();
                $('#submit').hide();

                if(id_one) {
                    $.ajax({
                        url: '/add-to-compare-list',
                        method: 'POST',
                        data: {
                            id: id_one 
                        },
                        dataType: 'JSON',
                        success: function(data) {
                            if(data.status) {
                                toastr.success(data.message);
                            } else {
                                toastr.warning(data.message);
                            }
                        }
                    });
                }
                
                if(id_two) {
                    $.ajax({
                        url: '/add-to-compare-list',
                        method: 'POST',
                        data: {
                            id: id_two 
                        },
                        dataType: 'JSON',
                        success: function(data) {
                            if(data.status) {
                                toastr.success(data.message);

                                window.location.href="/compare";

                            } else {
                                toastr.warning(data.message);
                            }
                        }
                    });
                }

                window.location.href="/compare";
            });

            $(document).on('keyup', '#ongoing-search-product-two', function() {
                var query = $(this).val();

                if (query.length > 0) {
                    $.ajax({
                        url: '/ajax-product-search',
                        method: 'GET',
                        data: { search: query },
                        success: function(data) {
                            $('#ongoing-product-list-two').html('').hide();
                            if (data.length > 0) {
                                $.each(data, function(index, product) {
                                    $('#ongoing-product-list-two').append('<div data-id="'+ product.slug +'">' + product.name + '</div>');
                                });
                                $('#ongoing-product-list-two').show();
                            } else {
                                $('#ongoing-product-list-two').hide(); 
                            }
                        }
                    });
                } else {
                    $('#product-list-two').html('').hide();
                }
            });

            $(document).on('click', '#ongoing-product-list-two div', function() {
                let id = $(this).data('id');

                $('#ongoing-search-product-two').val($(this).text());
                $('#compare_product_id_two').val(id);
                $('#ongoing-product-list-two').hide();

                if(id) {
                    $.ajax({
                        url: '/add-to-compare-list',
                        method: 'POST',
                        data: {
                            id: id 
                        },
                        dataType: 'JSON',
                        success: function(data) {
                            if(data.status) {
                                toastr.success(data.message);

                                setTimeout(() => {
                                    window.location.href="/compare";
                                }, 2500);
                            } else {
                                toastr.warning(data.message);
                            }
                        }
                    });
                }
            });

            $(document).on('keyup', '#ongoing-search-product-one', function() {
                var query = $(this).val();

                if (query.length > 0) {
                    $.ajax({
                        url: '/ajax-product-search',
                        method: 'GET',
                        data: { search: query },
                        success: function(data) {
                            $('#ongoing-product-list-one').html('').hide();
                            if (data.length > 0) {
                                $.each(data, function(index, product) {
                                    $('#ongoing-product-list-one').append('<div data-id="'+ product.slug +'">' + product.name + '</div>');
                                });
                                $('#ongoing-product-list-one').show();
                            } else {
                                $('#ongoing-product-list-one').hide(); 
                            }
                        }
                    });
                } else {
                    $('#product-list-one').html('').hide();
                }
            });

            $(document).on('click', '#ongoing-product-list-one div', function() {
                let id = $(this).data('id');

                $('#ongoing-search-product-one').val($(this).text());
                $('#compare_product_id_one').val(id);
                $('#ongoing-product-list-one').hide();

                if(id) {
                    $.ajax({
                        url: '/add-to-compare-list',
                        method: 'POST',
                        data: {
                            id: id 
                        },
                        dataType: 'JSON',
                        success: function(data) {
                            if(data.status) {
                                toastr.success(data.message);

                                setTimeout(() => {
                                    window.location.href="/compare";
                                }, 2500);
                            } else {
                                toastr.warning(data.message);
                            }
                        }
                    });
                }
            });
        })
    </script>
@endpush