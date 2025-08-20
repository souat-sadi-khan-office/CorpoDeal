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
                        <li class="breadcrumb-item active" aria-current="page">Product Management</li>
                    </ol>
                </div>

                @if (Auth::guard('admin')->user()->hasPermissionTo('product.create'))
                    <div class="col-sm-6 text-end">
                        <a href="{{ route('admin.product.create') }}" class="btn btn-soft-success istiyak bw-2">
                            <i class="bi bi-plus"></i>
                            Create New
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('content')

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="row">
                        <div class="col-md-5 mb-3 form-group">
                            <label for="category_id">Search Products by Category</label>
                            <input type="hidden" id="selected_category_id" value="{{ $category_id == null ? '' : $category_id }}">
                            <input type="hidden" id="default_category_image" value="">
                            <select id="category_id" class="form-control"></select>
                        </div>
                        <div class="col-md-5 mb-3 form-group">
                            <label for="brand_id">Search Products by Brand</label>
                            <input type="hidden" id="selected_brand_id" value="{{ $brand_id == null ? '' : $brand_id }}">
                            <input type="hidden" id="default_brand_image" value="">
                            <select id="brand_id" class="form-control"></select>
                        </div>
                        <div class="col-md-2 form-group mb-3 mt-4 text-end">
                            <button type="button" id="search_button" class="btn btn-sm btn-outline-success optimize" data-bs-toggle="tooltip" data-bs-placement="Top" title="Search">
                                <i class="bi bi-search"></i>
                            </button>
                            <button type="button" style="display: none;" id="reset_button" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" data-bs-placement="Top" title="Reset">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 table-responsive">
                    <table class="table table-bordered table-striped" id="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th width="5%" class="text-center">#</th>
                                <th width="40%">Name</th>
                                <th>Info</th>
                                <th>Publish</th>
                                <th>Featured</th>
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
    tr td:nth-child(7) {
        text-align: center;
        padding-top: 20px!important;
    }
</style>
@endpush
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    <script>

        $(function () {

            let selected_category_id = $('#selected_category_id').val();
            let selected_brand_id = $('#selected_brand_id').val();

            if(selected_brand_id != '' || selected_category_id != '') {
                $('#reset_button').show();
            } else {
                $('#reset_button').hide();
            }

            if(selected_category_id != '') {
                $.ajax({
                    url: '/search/category-by-id',
                    method: 'POST',
                    data: { category_id: selected_category_id },
                    dataType: 'JSON',
                    success: function (data) {
                        if(data.status) {
                            $('#default_category_image').val(data.thumb_image);
                            let option = new Option(data.text, data.id, true, true);
                            $('#category_id').append(option).trigger('change');
                        } else {
                            toastr.warning(data.message)
                        }
                    }
                });
            }

            if(selected_brand_id != '') {
                $.ajax({
                    url: '/search/brand-by-id',
                    method: 'POST',
                    data: { brand_id: selected_brand_id },
                    dataType: 'JSON',
                    success: function (data) {
                        if(data.status) {
                            $('#default_brand_image').val(data.thumb_image);
                            let option = new Option(data.text, data.id, true, true);
                            $('#brand_id').append(option).trigger('change');
                        } else {
                            toastr.warning(data.message)
                        }
                    }
                });
            }

            var table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.product.index', ['category_id' => $category_id, 'brand_id' => $brand_id]) }}",
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        order: true,
                        visible: false
                    },
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                    {data: 'product_name', name: 'product_name'},
                    {data: 'info', name: 'info'},
                    {data: 'status', name: 'status'},
                    {data: 'featured', name: 'featured'},
                ],
                order: [0, 'DESC']
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

        $(document).on('change', '.product-publish', function() {
            let id = $(this).data('id');
            if(!$(this).is(':checked')) {
                $('.featured_'+id).removeAttr('checked');
                var url = $('.featured_'+id).data('url');
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        status: 0
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('An error occurred while updating the status.');
                    }
                });
            }
        })
    </script>
@endpush
