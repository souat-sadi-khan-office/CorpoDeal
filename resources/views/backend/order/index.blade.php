@extends('backend.layouts.app')
@section('title', 'Product Management')
@push('style')
    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .dropdown-toggle::after {
            display: none !important;
        }
    </style>
@endpush
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
                        <li class="breadcrumb-item active" aria-current="page">Order Management - {{request()->status?ucwords(str_replace('_',' ',request()->status)):'All'}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="Orders">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th width="5%" class="text-center">#</th>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Order Amount</th>
                                <th>Payment</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th style="width:15%!important;text-align:center;">Created</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection
@push('style')
<style>
    tr td:nth-child(1) ,
    tr td:nth-child(3) ,
    tr td:nth-child(4) ,
    tr td:nth-child(5) ,
    tr td:nth-child(6) ,
    tr td:nth-child(7) {
        text-align: center;
        padding-top: 20px!important;
    }
</style>
@endpush
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    <script>

        $(function () {
            var table = $('#Orders').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.order.index', ['status' => $status, 'payment_status' => $payment_status,'refund_requested'=>$refund_requested]) }}",
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        order: true,
                        visible: false
                    },
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                    {data: 'unique_id', name: 'unique_id'},
                    {data: 'customer', name: 'customer'},
                    {data: 'phone', name: 'phone'},
                    {data: 'amount', name: 'amount'},
                    {data: 'payment_status', name: 'payment_status'},
                    {data: 'gateway_name', name: 'gateway_name'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                ],
                order: [[0, 'desc']]
            });

            _statusUpdate();

            // For duplicate items
            $(document).on('click', '#duplicate_item', function(e) {
                e.preventDefault();
                var row = $(this).data('id');
                var url = $(this).data('url');
                Swal.fire({
                    title: 'Duplicate Product?',
                    icon: 'warning',
                    html: `
                        <p>It will create the same product. Check items that will copy: </p>
                        <table class="table table-bordered">
                            <tr>
                                <th>
                                    Modules
                                </th>
                                <th>
                                    Selection
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    Product Taxes
                                </td>
                                <td class="text-center">
                                    <div class="text-center form-check form-switch">
                                        <input checked class="form-check-input" type="checkbox" role="switch" id="product_taxes">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Product Stock Purchase
                                </td>
                                <td>
                                    <div class="text-center form-check form-switch">
                                        <input checked class="form-check-input" type="checkbox" role="switch" id="stock_purchase">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Product Images
                                </td>
                                <td>
                                    <div class="text-center form-check form-switch">
                                        <input checked class="form-check-input" type="checkbox" role="switch" id="product_images">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Product Specifications
                                </td>
                                <td>
                                    <div class="text-center form-check form-switch">
                                        <input checked class="form-check-input" type="checkbox" role="switch" id="product_specifications">
                                    </div>
                                </td>
                            </tr>
                        </table>' +
                    `,

                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, duplicate',
                    cancelButtonText: 'No, cancel',
                    preConfirm: () => {
                        var product_taxes = Swal.getPopup().querySelector('#product_taxes').checked
                        var stock_purchase = Swal.getPopup().querySelector('#stock_purchase').checked
                        var product_images = Swal.getPopup().querySelector('#product_images').checked
                        var product_specifications = Swal.getPopup().querySelector('#product_specifications').checked

                        return {
                            product_taxes: product_taxes,
                            stock_purchase: stock_purchase,
                            product_images: product_images,
                            product_specifications: product_specifications,
                        }
                    }
                }).then((result) => {
                    let formData = new FormData();
                    formData.append('stock_purchase', result.value.stock_purchase);
                    formData.append('product_taxes', result.value.product_taxes);
                    formData.append('product_images', result.value.product_images);
                    formData.append('product_specifications', result.value.product_specifications);

                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            method: 'POST',
                            contentType: false,
                            data: formData,
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
                    }
                });
            });

            // for category searching
            $('#category_id').select2({
                width: '100%',
                placeholder: 'Select category',
                templateResult: formatCategoryOption,
                templateSelection: formatCategorySelection,
                ajax: {
                    url: '/search/category',
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
                            results:response
                        };
                    }
                }
            });

            function formatCategoryOption(category) {
                if (!category.id) {
                    return category.text;
                }

                var categoryImage = '<img src="' + category.image_url + '" class="img-flag" style="height: 20px; width: 20px; margin-right: 10px;" />';
                var categoryOption = $('<span>' + categoryImage + category.text + '</span>');
                return categoryOption;
            }

            function formatCategorySelection(category) {

                if (!category.id) {
                    return category.text;
                }

                var defaultImageUrl = $('#default_category_image').val();
                var image_url = category.image_url ? category.image_url : defaultImageUrl;

                var categoryImage = '<img src="' + image_url + '" class="img-flag" style="height: 20px; width: 20px; margin-right: 10px;" />';
                return $('<span>' + categoryImage + category.text + '</span>');
            }

            // for brands
            $('#brand_id').select2({
                width: '100%',
                placeholder: 'Select Brand',
                templateResult: formatBrandOption,
                templateSelection: formatBrandSelection,
                ajax: {
                    url: '/search/brands',
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
                            results:response
                        };
                    }
                }
            });

            function formatBrandOption(brand) {
                if (!brand.id) {
                    return brand.text;
                }

                var brandImage = '<img src="' + brand.image_url + '" class="img-flag" style="height: 20px; width: 20px; margin-right: 10px;" />';
                var brandOption = $('<span>' + brandImage + brand.text + '</span>');
                return brandOption;
            }

            function formatBrandSelection(brand) {
                if (!brand.id) {
                    return brand.text;
                }

                var defaultImageUrl = $('#default_brand_image').val();
                var image_url = brand.image_url ? brand.image_url : defaultImageUrl;

                var brandImage = '<img src="' + image_url + '" class="img-flag" style="height: 25px; width: 25px; margin-right: 10px;" />';
                return $('<span>' + brandImage + brand.text + '</span>');
            }

            $(document).on('click', '#search_button', function() {
                let category_id = $('#category_id').val();
                let brand_id = $('#brand_id').val();

                if(category_id == null && brand_id == null) {
                    toastr.warning("Plaese select any searching option first.");
                    return false;
                }

                let url = '?';
                if(category_id != null) {
                    url += 'category_id=' + category_id;
                }
                if(brand_id != null) {
                    if(url != '?') {
                        url += '&brand_id=' + brand_id;
                    } else {
                        url += 'brand_id=' + brand_id;
                    }
                }

                window.location.href= "/admin/product" + url;
            });

            // reset button click
            $(document).on('click', '#reset_button', function() {
                let url = '/admin/product';
                window.location.href = url;
            })
        });
    </script>
@endpush
