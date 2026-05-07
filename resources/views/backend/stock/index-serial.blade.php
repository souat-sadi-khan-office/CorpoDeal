@extends('backend.layouts.app', ['modal' => 'lg'])
@section('title', 'Stock Serial Management')
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
                        <li class="breadcrumb-item active" aria-current="page">Stock Serial Management</li>
                    </ol>
                </div>

                @if (Auth::guard('admin')->user()->hasPermissionTo('stock.create'))
                    <div class="col-sm-6 text-end">
                        <a href="{{ route('admin.stock.create') }}" class="btn btn-sm btn-outline-dark">
                            <i class="bi bi-plus"></i>
                            Add New Stock
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
                                <th>Product</th>
                                <th>Supplier</th>
                                <th>Serial</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    <script>

        $(function () {
            var table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.product.serials') }}",
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        order: true,
                        visible: false
                    },
                    {data: 'product', name: 'product'},
                    {data: 'supplier', name: 'supplier'},
                    {data: 'serial', name: 'serial'},
                ],
                order: [0, 'DESC']
            });

            _componentRemoteModalLoadAfterAjax();
            _statusUpdate();
        });
    </script>
@endpush
