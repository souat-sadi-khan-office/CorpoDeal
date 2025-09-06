@extends('backend.layouts.app', ['modal' => 'xl'])
@section('title', 'Specification Keys')
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
                        <li class="breadcrumb-item active" aria-current="page">Category Specification Groups/Keys</li>
                    </ol>
                </div>

                @if (Auth::guard('admin')->user()->hasPermissionTo('specification-key.create'))
                    <div class="col-sm-6 text-end">
                        <a href="javascript:;" data-url="{{ route('admin.category.specification.key.create') }}" id="content_management" class="btn btn-sm btn-outline-dark">
                            <i class="bi bi-plus"></i>
                            Add New
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
                                <th width="10%">Image</th>
                                <th>Category</th>
                                <!-- <th>Parent</th> -->
                                <th class="text-center">Groups/Keys Count</th>
                                <th width="25%" class="text-center">Action</th>
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
    </style>
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
                ajax: "{{ route('admin.category.specification.key.index') }}",
                columns: [
                    {
                        data: 'photo',
                        name: 'photo',
                        order: false,
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    // {
                    //     data: 'parent_id',
                    //     name: 'parent_id'
                    // },
                    {
                        data: 'specification_keys_count',
                        name: 'specification_keys_count'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            _componentRemoteModalLoadAfterAjax();
            _isfeaturedUpdate();
            _statusUpdate();

        });


    </script>
@endpush
