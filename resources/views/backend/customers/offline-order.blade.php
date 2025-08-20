@extends('backend.layouts.app', ['modal' => 'lg'])
@section('title', 'Offline Orders')
@push('style')
    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush
@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 mx-auto">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Offline Orders</li>
                    </ol>
                </div>
                <div class="col-sm-6 text-end form-group">
                    <a id="content_management" href="javascript:;"
                       data-url="{{ route('admin.customer.create') }}?from=offline-sale"
                       class="btn btn-soft-success istiyak bw-2">
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
    <form action="{{ route("admin.offline-order-create") }}" method="POST" class="content_form">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 form-group mb-3">
                        <label for="user_id">Customer</label>
                        <select name="user_id" id="customer_id" class="form-control" required></select>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label for="customer_company">Customer Company</label>
                        <input type="text" name="customer_company" id="customer_company" class="form-control"/>
                    </div>

                    <div class="col-md-12 mb-3 form-group">
                        <label for="product_id">Products </label>
                        <select id="product_id" class="form-control"></select>
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
                        <div class="col-md-12 mb-3 table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <th>Product</th>
                                    <th>Base Price</th>
                                    <th>Quantity</th>
                                    <th>Sub Total</th>
                                    <th>Current Stock</th>
                                    <th>Remove</th>
                                </thead>
                                <tbody id="product_data">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 form-group mb-4">
            <div class="card">
                <div class="card-header">
                    <h2 class="h5 mb-0">Manage SubTotal</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td>Sub Total</td>
                                    <td class="text-right">
                                        <div id="total_sub_total">0</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Shipping</td>
                                    <td class="text-right">
                                        <div id="total_shipping">0</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Discount</td>
                                    <td class="text-right">
                                        <div id="total_discount">0</div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <th class="text-right">
                                        <div id="total_amount">0</div>
                                        <input type="hidden" name="total_amount_value" id="total_amount_value"
                                               value="0">
                                    </th>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-4 form-group mt-3">
                            <label for="shipping_charge">Shipping Charge Flat</label>
                            <input type="text" name="shipping_charge" id="shipping_charge" class="form-control number"
                                   value="0">
                        </div>

                        <div class="col-md-4 form-group mt-3">
                            <label for="discount">Discount Flat</label>
                            <input type="text" name="discount" id="discount" class="form-control number" value="0">
                        </div>

                        <div class="col-md-4 mt-3 form-group">
                            <label for="payment_option">Payment Type</label>
                            <select name="payment_option" id="payment_option" class="form-control">
                                <option value="offline_payment">Offline Payment</option>
                                <option selected value="cod">Confirm with COD</option>
                                <option value="cash">Confirm with Cash</option>
                            </select>
                        </div>
                        <div class="col-md-12 mt-3 form-group">
                            <label for="shipping">Shipping Address</label>
                            <input type="text" name="shipping" id="shipping" class="form-control"
                                   placeholder="Address,Area,City-zip,Country" required>

                        </div>
                        <div class="col-md-12 mt-3 form-group">
                            <label for="billing">Billing Address</label>
                            <p>Leave Empty if Shipping and Billing Address are Same</p>
                            <input type="text" name="billing" id="billing" class="form-control"
                                   placeholder="Address,Area,City-zip,Country">

                        </div>
                        <div class="col-md-6 d-grid gap-2 mx-auto mt-3">
                            <button class="btn btn-outline-success btn-block" type="submit" id="submit">
                                <i class="bi bi-send"></i>
                                Create
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

            if (!value || value === '' || value === null) {
                return false;
            }
            // $('.product-area').html("");
            // $('.product_area').html(`
            //     <div class="d-flex justify-content-center">
            //         <div class="spinner-border" role="status">
            //             <span class="visually-hidden">Loading...</span>
            //         </div>
            //     </div>
            // `);
            $.ajax({
                url: '/search/product-data',
                method: 'POST',
                data: {
                    data: value
                },
                dataType: 'JSON',
                cache: true,
                success: function (product) {
                    // $.each(data, function (index, product) {

                        var row = `
                            <tr class="main_row_` + product.id + `">
                                <td>
                                    <div class="row">
                                        <div class="col-auto">
                                            <img src="` + product.thumb_image + `" alt="` + product.name + `" width="50">
                                        </div>
                                        <div class="col">` + product.name + `</div>
                                    </div>
                                    <input type="hidden" name="product[` + product.id + `][id]" value="` + product.id + `">
                                </td>
                                <td>
                                    ` + product.unit_price + `
                                    <input type="hidden" id="product_price_` + product.id + `" name="product[` + product.id + `][unit_price]" value="` + product.unit_price + `">
                                </td>
                                <td>
                                    <input type="number" data-id="` + product.id + `" name="product[` + product.id + `][quantity]" value="1" id="quantity_` + product.id + `" max="` + product.stock + `" min="1" class="quantity form-control number">
                                </td>
                                <td>
                                    <span class="product_sub_total_price_` + product.id + `">
                                        ` + product.unit_price + `
                                    </span>
                                </td>
                                <td>
                                    <span class="product_current_stock` + product.id + ` ` + (product.stock > 0 ? '' : 'text-danger') + `">
                                        ` + product.stock + `
                                    </span>
                                </td>
                                <td>
                                    <a data-id=` + product.id + ` href="javascript:;" class="remove_item btn btn-sm btn-outline-danger">
                                        <i class="bi bi-x"></i>
                                    </a>
                                </td>
                            </tr>`;

                        // content = content.concat(row);
                    // });

                    
                    $('#product_data').append(row);

                    calculateSubTotal();
                    $('#product_id').val("").trigger('change');
                }
            })
        });

        $(document).on('click', '.remove_item', function () {
            let id = $(this).data('id');
            $('.main_row_' + id).remove();

            calculateSubTotal();
        });

        $(document).on('keyup', '.quantity', function () {
            let id = $(this).data('id');
            let quantity = parseInt($(this).val());
            let price = parseInt($('#product_price_' + id).val());
            let sub_total = quantity * price;
            $('.product_sub_total_price_' + id).html(parseInt(sub_total).toFixed(2));

            calculateSubTotal();
        });

        $(document).on('keyup', '#shipping_charge', function () {
            changeTotalAmount();
        })

        $(document).on('keyup', '#discount', function () {
            changeTotalAmount();
        })

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

        function changeTotalAmount() {
            calculateSubTotal();

            let total_sub_total = parseFloat($('#total_sub_total').text()) || 0;
            let shipping_charge = parseFloat($('#shipping_charge').val()) || 0;
            let discount = parseFloat($('#discount').val()) || 0;

            let total = parseInt(total_sub_total) + parseInt(shipping_charge) - parseInt(discount);
            $('#total_amount_value').val(total);
            $('#total_amount').html(total.toFixed(2));

            $('#total_shipping').html(parseInt(shipping_charge).toFixed(2));
            $('#total_discount').html(parseInt(discount).toFixed(2));
        }

        function calculateSubTotal() {
            var total = 0;

            // Loop through each product's sub-total and add it to the total
            $('[class^="product_sub_total_price_"]').each(function () {
                var subTotal = parseFloat($(this).text()) || 0;
                total += subTotal;
            });

            // Update the total in the designated area
            $('#total_sub_total').text(total.toFixed(2));

            calculateTotal(total);
        }

        function calculateTotal(sub_total_amount) {
            let total = 0;
            let sub_total = parseFloat(sub_total_amount);
            let shipping = 0;
            let discount = 0;

            if(parseFloat($('#shipping_charge').val) > 0) {
                shipping = parseFloat($('#shipping_charge').val);
            }

            if(parseFloat($('#discount').val) > 0) {
                discount = parseFloat($('#discount').val);
            }

            if(parseFloat($('#total_sub_total').text) > 0) {
                sub_total = parseFloat($('#total_sub_total').text);
            }

            total = sub_total + shipping - discount;
            $('#total_amount').html(total.toFixed(2));
            $('#total_amount_value').val(total.toFixed(2));
        }

    </script>
@endpush
