@extends('backend.layouts.app', ['modal' => 'xl'])
@section('title', 'Specification Key Types')
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
                        <li class="breadcrumb-item active" aria-current="page">Specification Key Types</li>
                    </ol>
                </div>

                @if (Auth::guard('admin')->user()->hasPermissionTo('specification-types.create'))
                    <div class="col-sm-6 text-end">
                        <a href="javascript:;" data-url="{{ route('admin.category.specification.type.create') }}" id="content_management" class="btn btn-sm btn-outline-dark">
                            <i class="bi bi-plus"></i>
                            Add New Type
                        </a>
                    </div>
                @endif

                <div class="col-md-12 mt-2">
                    <div class="alert alert-info shadow-sm border-0 rounded-3 d-flex align-items-start" role="alert">
                        <div class="me-2">
                            <i class="bi bi-info-circle-fill fs-4"></i>
                        </div>
                        <div>
                            <strong>Note:</strong> This section displays <strong>All Category Groups/Keys</strong>.
                            
                            To view groups/keys for a specific category:
                            <ol class="mb-0 ps-3">
                                <li>Go to the <a style="color:#000;" href="{{ route('admin.category.specification.key.index') }}" class="fw-bold text-decoration-none">Groups/Keys</a> page.</li>
                                <li>Copy the desired category name.</li>
                                <li>Paste it into the search field above to filter results.</li>
                            </ol>
                        </div>
                    </div>
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
                    <table class="table table-bordered table-striped table-hover" id="data-table">
                        <thead>
                            <tr>
                                <th>Group/Key Name</th>
                                <th style="width: 15%;">Creator</th>
                                <!-- <th style="width: 20%;">Category Name</th> -->
                                <th style="width: 13%;">Types Count</th>
                                <th style="width: 15%;text-align:center;">Actions</th>
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
                ajax: "{{ route('admin.category.specification.type.index') }}",
                columns: [
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'created_by',
                        name: 'created_by'
                    },
                    // {
                    //     data: 'category_name',
                    //     name: 'category_name'
                    // },
                    {
                        data: 'types_count',
                        name: 'types_count'
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
    <script>
        $(document).ready(function() {

            $(document).on('change', 'input[name="is_show_on_filter"]', function() {
                var status = this.checked ? 1 : 0;

                if (status) {
                    $('#FILTER').append(`
                <div class="form-group" id="filter-name-group">
                    <label for="filter_name">Filter Name <span class="text-danger">*</span></label>
                    <input type="text" name="filter_name" id="filter_name" class="form-control" required>
                </div>
            `);
                } else {
                    // Checkbox is unchecked, remove the input field if it exists
                    $('#filter-name-group').remove();
                }
            });
        });
    </script>
@endpush
