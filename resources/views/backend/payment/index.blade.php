@extends('backend.layouts.app', ['modal' => 'md'])
@section('title', 'SSL Payments')
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
                        <li class="breadcrumb-item active" aria-current="page">SSL Payments</li>
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
                    <table class="table table-bordered table-striped table-hover" id="data-table">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Order</th>
                                <th>Trx ID</th>
                                <th>Payer ID</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Gateway</th>
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
                ajax: "{{ route('admin.payment.index') }}",
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        order: true,
                        visible: false
                    },
                    {
                        data: 'order',
                        name: 'order',
                    },
                    {
                        data: 'trx_id',
                        name: 'trx_id'
                    },
                    {
                        data: 'payer_id',
                        name: 'payer_id'
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'gateway',
                        name: 'gateway',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [0, 'desc']
            });

            _componentRemoteModalLoadAfterAjax();
            _isfeaturedUpdate();
            _statusUpdate();

        });


    </script>
@endpush
