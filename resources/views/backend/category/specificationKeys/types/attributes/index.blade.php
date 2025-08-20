@extends('backend.layouts.app', ['modal' => 'xl'])
@section('title', 'Specification Key Type Attributes')
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
                        <li class="breadcrumb-item active" aria-current="page">Specification Key Type Attributes</li>
                    </ol>
                </div>

                @if (Auth::guard('admin')->user()->hasPermissionTo('specification-type-attribute.create'))
                    <div class="col-sm-6 text-end">
                        <a href="javascript:;" data-url="{{ route('admin.category.specification.type.attribute.create') }}"
                            id="content_management" class="btn btn-soft-success">
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
                <div class="col-md-12 table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th style="width: 15%;">Created By</th>
                                <th style="width: 13%;">Filter Name</th>
                                <th style="width: 13%;">Total Attributes</th>
                                <th style="width: 12%;">Actions</th>
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
                ajax: "{{ route('admin.category.specification.type.attribute.index') }}",
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'created_by',
                        name: 'created_by'
                    },
                    {
                        data: 'filter_name',
                        name: 'filter_name'
                    },
                    {
                        data: 'attributes_count',
                        name: 'attributes_count'
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
            _statusUpdate();

            $(document).on('click', '.add-new', function() {
                let randomNum = getRandomInt(1, 1000);
                let model = `
                    <div class="row mt-2" id="model_`+ randomNum +`">
                        <div class="col-md-6 form-group">
                            <label for="name_`+ randomNum +`">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name[]" id="name_`+ randomNum +`" class="form-control mt-3 py-2" required>
                        </div>

                        <div class="col-md-5 form-group">
                            <label for="extra_`+ randomNum +`">Extra Words</label>
                            <input type="text" name="extra[]" id="extra_`+ randomNum +`" class="form-control mt-3 py-2">
                        </div>
                        <div class="col-md-1 pt-5">
                            <button type="button" data-id="`+ randomNum +`" class="remove-model-data btn btn-sm btn-outline-danger">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>
                `;

                $('.model-data').append(model);
            });

        });

        $(document).on('click', '.remove-model-data', function() {
            let id = $(this).data('id');
            $('#model_'+id).remove();
        })

        function getRandomInt(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }
    </script>

@endpush
