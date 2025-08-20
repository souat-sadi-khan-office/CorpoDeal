@extends('backend.layouts.app', ['modal' => 'lg'])
@section('title', 'QR Cart')
@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">QR Cart</li>
                    </ol>
                </div>
                <div class="col-sm-6 text-end form-group mt-4 mb-3">
                    <a id="content_management" href="javascript:;"
                       data-url="{{ route('admin.customer.create') }}?from=offline-sale"
                       class="btn btn-soft-success">
                        <i class="bi bi-plus"></i>
                        Create Customer
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('content')

    <style>
        .text-right {
            text-align: right;
        }
    </style>
    <form action="{{ route("admin.qr.generate") }}" method="POST" class="content_form">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 form-group mb-3">
                        <label for="user_id">Customer</label>
                        <select name="user_id" id="customer_id" class="form-control" required></select>
                    </div>

                    <div class="col-md-4 form-group mt-2">
                        <div class="callout callout-success">
                            <strong>Send QR</strong> to Customers with <strong>Cart Items</strong><br>
                            - Add to Cart QR
                        </div>
                    </div>
                    <div class="col-md-12 mb-3 form-group">
                        <label for="product_id">Products </label>
                        <select multiple id="product_id" class="form-control"></select>
                        <small class="text-muted">For selecting multiple product at a time, use your keyboard
                            <b>Control</b> key and click on the products that you want to add. </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 form-group mb-4">
            <div class="card">
                <div class="card-header">
                    <h2 class="h5 mb-0">Product Information</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 product_area mb-3 table-responsive"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 form-group mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6 d-grid gap-2 mx-auto mt-3">
                            <button class="btn btn-outline-success btn-block" type="submit" id="submit">
                                <i class="bi bi-qr-code-scan"></i>
                                Send QR
                            </button>
                            <button class="btn btn-outline-warning btn-block" style="display: none;" id="submitting"
                                    type="button" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading...
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
@push('styleforIconPicker')

@endpush

@push('script')
    <script>
        _formValidation();
        $('#customer_id').select2({
            width: '100%',
            placeholder: 'Select Customer',
            ajax: {
                url: '/search/customers',
                method: 'POST',
                dataType: 'JSON',
                delay: 250,
                cache: true,
                data: function (data) {
                    return {
                        searchTerm: data.term
                    };
                },

                processResults: function (response) {
                    return {
                        results: response
                    };
                }
            }
        });

        _componentRemoteModalLoadAfterAjax();

        $(document).on('change', '#product_id', function () {
            let value = $(this).val();
            $('.product-area').html("");
            $('.product_area').html(`
                <div class="d-flex justify-content-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `);
            $.ajax({
                url: '/search/product-data',
                method: 'POST',
                data: {
                    data: value
                },
                dataType: 'JSON',
                cache: true,
                success: function (data) {
                    let content = `
                        <table class="table table-bordered table-hover">
                            <thead>
                                <th>Product</th>
                                <th>Base Price</th>
                                <th>Quantity</th>
                                <th>Sub Total</th>
                                <th>Current Stock</th>
                                <th>Remove</th>
                            </thead>
                            <tbody>
                    `;

                    $.each(data, function (index, product) {

                        var row = `
                            <tr class="main_row_` + index + `">
                                <td>
                                    <div class="row">
                                        <div class="col-auto">
                                            <img src="` + product.thumb_image + `" alt="` + product.name + `" width="50">
                                        </div>
                                        <div class="col">` + product.name + `</div>
                                    </div>
                                    <input type="hidden" name="product[` + index + `][id]" value="` + product.id + `">
                                </td>
                                <td>
                                    ` + product.unit_price + `
                                    <input type="hidden" id="product_price_` + index + `" name="product[` + index + `][unit_price]" value="` + product.unit_price + `">
                                </td>
                                <td>
                                    <input type="number" data-id="` + index + `" name="product[` + index + `][quantity]" value="1" id="quantity_` + index + `" max="` + product.stock + `" min="1" class="quantity form-control number">
                                </td>
                                <td>
                                    <span class="product_sub_total_price_` + index + `">
                                        ` + product.unit_price + `
                                    </span>
                                </td>
                                <td>
                                    <span class="product_current_stock` + index + ` ` + (product.stock > 0 ? '' : 'text-danger') + `">
                                        ` + product.stock + `
                                    </span>
                                </td>
                                <td>
                                    <a data-id=` + index + ` href="javascript:;" class="remove_item btn btn-sm btn-outline-danger">
                                        <i class="bi bi-x"></i>
                                    </a>
                                </td>
                            </tr>`;

                        content = content.concat(row);
                    });

                    footer = `
                            </tbody>
                        </table>
                    `;
                    content = content.concat(footer);

                    $('.product_area').html("");
                    $('.product_area').html(content);

                }
            })
        });

        $(document).on('click', '.remove_item', function () {
            let id = $(this).data('id');
            $('.main_row_' + id).remove();

        });

        $(document).on('keyup', '.quantity', function () {
            let id = $(this).data('id');
            let quantity = parseInt($(this).val());
            let price = parseInt($('#product_price_' + id).val());
            let sub_total = quantity * price;
            $('.product_sub_total_price_' + id).html(parseInt(sub_total).toFixed(2));

        });


        // for froducts
        $('#product_id').select2({
            width: '100%',
            placeholder: 'Select products',
            templateResult: formatProductOption,
            templateSelection: formatProductSelection,
            ajax: {
                url: '/search/product',
                method: 'POST',
                dataType: 'JSON',
                delay: 250,
                cache: true,
                data: function (data) {
                    return {
                        searchTerm: data.term
                    };
                },

                processResults: function (response) {
                    return {
                        results: response
                    };
                }
            }
        });

        function formatProductOption(product) {
            if (!product.id) {
                return product.text;
            }

            var productImage = '<img src="' + product.image_url + '" class="img-flag" style="height: 20px; width: 20px; margin-right: 10px;" />';
            var productOption = $('<span>' + productImage + product.text + '</span>');
            return productOption;
        }

        function formatProductSelection(product) {
            if (!product.id) {
                return product.text;
            }

            var productImage = '<img src="' + product.image_url + '" class="img-flag" style="height: 20px; width: 20px; margin-right: 10px;" />';
            return $('<span>' + productImage + product.text + '</span>');
        }

    </script>
@endpush
