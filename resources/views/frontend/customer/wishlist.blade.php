@extends('frontend.layouts.app', ['title' => 'My Wishlist | ', get_settings('system_name')])

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
                        My Wish List
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
                                <h1 class="h5">My Wish List</h1>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive wishlist_table">
                                            @if (count($models))
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th class="product-thumbnail">&nbsp;</th>
                                                            <th class="product-name">Product</th>
                                                            <th class="product-price">Price</th>
                                                            <th class="product-stock-status">Stock Status</th>
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