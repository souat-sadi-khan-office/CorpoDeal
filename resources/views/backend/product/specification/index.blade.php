@extends('backend.layouts.app', ['modal' => 'xl'])
@section('title', 'Product Specification Control')
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
                        <li class="breadcrumb-item active" aria-current="page">Update Product Specifications</li>
                    </ol>
                </div>

                @if (Auth::guard('admin')->user()->hasPermissionTo('product.create'))
                    <div class="col-sm-6 text-end">
                        <a href="{{ route('admin.product.create') }}" class="btn btn-sm btn-outline-dark">
                            <i class="bi bi-plus" style="margin-right: 10px;"></i>
                            Add new Product
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
                <div class="col-md-12 table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th width="64">Product</th>
                                <th width="7%">Published</th>
                                <th width="7%">Featured</th>
                                <th width="10%">Specs</th>
                                <th width="10%" class="text-end">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('styleforIconPicker')
    <link href="{{ asset('backend/assets/css/bootstrapicons-iconpicker.css') }}" rel="stylesheet">
    <style>
        tr td:nth-child(3) {
            text-align: center;
        }

        tr td:nth-child(3) i {
            font-size: 25px;
        }
    </style>
    <!-- Option 1: Include in HTML -->
@endpush

@push('script')
    <script src="{{ asset('backend/assets/js/bootstrapicon-iconpicker.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(function() {
            var table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.product.specification.edit') }}",
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        order: true,
                        visible: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'is_featured',
                        name: 'is_featured',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'specifications_count',
                        name: 'specifications_count',
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [0, 'DESC']
            });

            _componentRemoteModalLoadAfterAjax();
            _isfeaturedUpdate();
            _statusUpdate();

            $(document).on('click', '.move-up, .move-down, .move-key-up, .move-key-down', function(e) {
                e.preventDefault();

                let button = $(this);
                let url = button.data('url');

                // Spinner
                let originalIcon = button.html();
                button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {_token: "{{ csrf_token() }}"},
                    success: function(res) {
                        if (res.success) {
                            $("#specificationTable").html(res.html);
                        }
                    },
                    complete: function() {
                        button.prop('disabled', false).html(originalIcon);
                    }
                });
            });


        });
    </script>
@endpush
