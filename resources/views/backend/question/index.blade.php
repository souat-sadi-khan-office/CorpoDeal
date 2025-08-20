@extends('backend.layouts.app', ['modal' => 'lg'])
@section('title', 'Customer Question Management')
@push('style')
    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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
                        <li class="breadcrumb-item active" aria-current="page">Customer Question Management</li>
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
                <div class="col-md-12 mb-3">
                    <div class="row">
                        <div class="col-md-10 mb-3 form-group">
                            <label for="product_id">Search Question by Products</label>
                            <input type="hidden" id="selected_product_id" value="{{ $product_id == null ? '' : $product_id }}">
                            <input type="hidden" id="default_product_image" value="">
                            <select id="product_id" class="form-control"></select>
                        </div>
                        <div class="col-md-2 form-group mb-3 mt-4 text-end">
                            <button type="button" id="search_button" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" data-bs-placement="Top" title="Search">
                                <i class="bi bi-search"></i>
                            </button>
                            <button type="button" style="display: none;" id="reset_button" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" data-bs-placement="Top" title="Reset">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="data-table">
                        <thead>
                            <tr>
                                @if ($product_id == null)
                                    <th>Product</th>
                                @endif
                                <th>Date</th>
                                <th>User</th>
                                <th>Question</th>
                                <th>Status</th>
                                <th width="10%">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(function () {

            let selected_product_id = $('#selected_product_id').val();

            if(selected_product_id != '') {
                $('#reset_button').show();
            } else {
                $('#reset_button').hide();
            }

            if(selected_product_id != '') {
                $.ajax({
                    url: '/search/product-by-id',
                    method: 'POST',
                    data: { product_id: selected_product_id },
                    dataType: 'JSON',
                    success: function (data) {
                        if(data.status) {
                            $('#default_product_image').val(data.thumb_image);
                            let option = new Option(data.text, data.id, true, true);
                            $('#product_id').append(option).trigger('change');
                        } else {
                            toastr.warning(data.message)
                        }
                    }
                });

                var table = $('#data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('admin.customer.question.index', ['product_id' => $product_id]) }}",
                    columns: [
                        {data: 'date', name: 'date'},
                        {data: 'name', name: 'name'},
                        {data: 'question', name: 'question'},
                        {data: 'status', name: 'status'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });
            } else {
                var table = $('#data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('admin.customer.question.index', ['product_id' => $product_id]) }}",
                    columns: [
                        {data: 'product', name: 'product'},
                        {data: 'date', name: 'date'},
                        {data: 'name', name: 'name'},
                        {data: 'question', name: 'question'},
                        {data: 'status', name: 'status'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });
            }



            _componentRemoteModalLoadAfterAjax();
            _statusUpdate();

            // for category searching
            $('#product_id').select2({
                width: '100%',
                placeholder: 'Select Product',
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
                            results:response
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

                var defaultImageUrl = $('#default_product_image').val();
                var image_url = product.image_url ? product.image_url : defaultImageUrl;

                var productImage = '<img src="' + image_url + '" class="img-flag" style="height: 20px; width: 20px; margin-right: 10px;" />';
                return $('<span>' + productImage + product.text + '</span>');
            }

            $(document).on('click', '#search_button', function() {
                let product_id = $('#product_id').val();

                if(product_id == null) {
                    toastr.warning("Plaese select product first.");
                    return false;
                }

                let url = '?';
                if(product_id != null) {
                    url += 'product_id=' + product_id;
                }

                window.location.href= "/admin/customer/question" + url;
            });

            // reset button click
            $(document).on('click', '#reset_button', function() {
                let url = '/admin/customer/question';
                window.location.href = url;
            })
        });
    </script>
@endpush
