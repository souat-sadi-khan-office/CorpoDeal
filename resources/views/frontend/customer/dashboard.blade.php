@extends('frontend.layouts.app', ['title' => 'My Dashboard | ' . get_settings('system_name')])
@push('page_meta_information')
    <link rel="canonical" href="{{ route('home') }}" />
    <meta name="referrer" content="origin">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">

    <meta name="title" content="My Dashboard | {{ get_settings('system_name') }}">
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
                        <li class="breadcrumb-item active">
                            Account
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endpush

@section('content')
    <div class="section bg_gray pt-4 mm">
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
                                    <h4>Dashboard</h4>
                                </div>
                                <div class="card-body">
                                    <p>From your account dashboard you can view your recent orders, manage your shipping and
                                        billing addresses, and edit your password and account details.</p>

                                    <div class="row g-4">
                                        <div class="col-md-4">
                                            <div class="total-contain">
                                                <div class="total-icon">
                                                    <i class="fas fa-shopping-basket"></i>
                                                </div>
                                                <div class="total-detail">
                                                    <span class="text">Total Order</span>
                                                    <h2 class="title">{{ $data['total_orders'] }}</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="total-contain">
                                                <div class="total-icon">
                                                    <i class="fas fa-truck"></i>
                                                </div>
                                                <div class="total-detail">
                                                    <span class="text">Total Pending Order</span>
                                                    <h2 class="title">{{ $data['pending_orders'] }}</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="total-contain">
                                                <div class="total-icon">
                                                    <i class="fas fa-solid fa-coins"></i>
                                                </div>
                                                <div class="total-detail">
                                                    <span class="text">Total Ordered Amount</span>
                                                    <h2 class="title">
                                                        {{ format_price(convert_price($data['total_amount'])) }}</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="card countries-card px-3 pt-3 pb-2 mb-2">
                                                <h6>Your Top Favourite Products</h6>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="table-responsive wishlist_table">
                                                            @if (count($models))
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="product-thumbnail">&nbsp;</th>
                                                                            <th class="product-name">Product</th>
                                                                            <th class="product-price">Price</th>
                                                                            <th class="product-stock-status">Stock Status
                                                                            </th>
                                                                            <th class="product-add-to-cart">Order</th>
                                                                            <th class="product-remove">Remove</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($models as $model)
                                                                            @php
                                                                            @endphp
                                                                            <tr>
                                                                                <td class="product-thumbnail">
                                                                                    <a href="{{ route('slug.handle',$model->product->slug) }}">
                                                                                        <img style="max-width:80px;"
                                                                                            src="{{ asset($model->product->thumb_image) }}"
                                                                                            alt="{{ $model->product->name }}">
                                                                                    </a>
                                                                                </td>
                                                                                <td class="product-name">
                                                                                    <a href="{{ route('slug.handle',$model->product->slug) }}">
                                                                                        {!! add_line_breaks($model->product->name, 3) !!}
                                                                                    </a>
                                                                                </td>
                                                                                <td class="product-price">
                                                                                    {{ home_discounted_price($model->product) }}
                                                                                </td>
                                                                                <td class="product-stock-status">
                                                                                    @if ($model->product->in_stock)
                                                                                        <span
                                                                                            class="badge rounded-pill text-bg-success">In
                                                                                            Stock</span>
                                                                                    @else
                                                                                        <span
                                                                                            class="badge rounded-pill text-bg-danger">Out
                                                                                            of Stock</span>
                                                                                    @endif
                                                                                </td>
                                                                                <td class="product-add-to-cart">
                                                                                    <button class="btn btn-fill-out btn-sm add-to-cart" data-id="{{$model->product->slug}}" type="button">
                                                                                        <i class="icon-basket-loaded"></i> Add to cart
                                                                                    </button>
                                                                                </td>
                                                                                <td class="product-remove"
                                                                                    data-title="Remove">
                                                                                    <a id="delete_item"
                                                                                        data-id="{{ $model->id }}"
                                                                                        data-url="{{ route('account.wishlist.destroy', $model->id) }}"
                                                                                        class="remove-column-{{ $model->id }}"
                                                                                        href="javascript:;">
                                                                                        <i class="fas fa-times"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            @else
                                                                <p>There is nothing to show</p>
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


                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).on('click', '#delete_item', function(e) {
            e.preventDefault();
            var row = $(this).data('id');
            var url = $(this).data('url');
            $('.remove-column-'+row).html('<i class="fas fa-spin fa-spinner"></i>');
            $.ajax({
                url: url,
                method: 'Delete',
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'JSON',
                success: function(data) {

                    if (data.status) {

                        toastr.success(data.message);
                        if (data.load) {
                            setTimeout(function() {

                                window.location.href = "";
                            }, 1000);
                        }

                    } else {
                        $('.remove-column-'+row).html('<i class="fas fa-times"></i>');
                        toastr.warning(data.message);
                    }
                },
                error: function(data) {
                    var jsonValue = $.parseJSON(data.responseText);
                    const errors = jsonValue.errors
                    var i = 0;
                    $.each(errors, function(key, value) {
                        toastr.error(value);
                        i++;
                    });
                }
            });

        });
    </script>
@endpush
