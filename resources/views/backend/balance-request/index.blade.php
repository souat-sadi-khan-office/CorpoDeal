@extends('backend.layouts.app')
@section('title', 'Balance Requests')
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
                        <li class="breadcrumb-item active" aria-current="page">Balance Requests</li>
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
                    <table class="table table-bordered table-striped table-hover" id="Requests">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Updated By</th>
                            <th>Installment</th>
                            <th>Documents</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection
{{--@push('style')--}}
{{--<style>--}}
{{--    tr td:nth-child(1) ,--}}
{{--    tr td:nth-child(3) ,--}}
{{--    tr td:nth-child(4) ,--}}
{{--    tr td:nth-child(5) ,--}}
{{--    tr td:nth-child(6) ,--}}
{{--    tr td:nth-child(7) {--}}
{{--        text-align: center;--}}
{{--        padding-top: 20px!important;--}}
{{--    }--}}
{{--</style>--}}
{{--@endpush--}}
@push('script')
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    <script>

        $(function () {
            var table = $('#Requests').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.balance.request') }}",
                columns: [
                    {data: 'id', name: 'id'}, // Corresponds to "#"
                    {data: 'customer', name: 'customer'}, // Corresponds to "Customer"
                    {data: 'status', name: 'status'}, // Corresponds to "Status"
                    {data: 'amount', name: 'amount'}, // Corresponds to "Amount"
                    {data: 'created_at', name: 'created_at'}, // Corresponds to "Created At"
                    {data: 'updated_at', name: 'updated_at'}, // Corresponds to "Updated At"
                    {data: 'updated_by', name: 'updated_by'}, // Corresponds to "Updated By"
                    {data: 'installment', name: 'installment'}, // Corresponds to "Installment"
                    {
                        data: null,
                        name: 'documents',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            let links = '';
                            if (row.document) {
                                links += `<a target="_blank" href="${row.document}">
                               <i class="bi bi-file-binary-fill"></i>
                              </a> `;
                            }
                            if (row.document_2) {
                                links += `<a target="_blank" href="${row.document_2}">
                               <i class="bi bi-file-earmark-ruled"></i>
                              </a> `;
                            }
                            if (row.additional_documents) {
                                JSON.parse(row.additional_documents).forEach(function (doc) {
                                    links += `<a target="_blank" href="${doc}">
                                  <i class="bi bi-file-earmark-ruled"></i>

                                  </a> `;
                                });
                            }
                            return links || 'N/A';
                        }
                    },
                    {data: 'action', name: 'action', orderable: false, searchable: false}, // Corresponds to "Action"
                ],
                order: [[0, 'desc']]
            });

            // for brands
            // $('#brand_id').select2({
            //     width: '100%',
            //     placeholder: 'Select Brand',
            //     templateResult: formatBrandOption,
            //     templateSelection: formatBrandSelection,
            //     ajax: {
            //         url: '/search/brands',
            //         method: 'POST',
            //         dataType: 'JSON',
            //         delay: 250,
            //         cache: true,
            //         data: function (data) {
            //             return {
            //                 searchTerm: data.term
            //             };
            //         },
            //
            //         processResults: function (response) {
            //             return {
            //                 results:response
            //             };
            //         }
            //     }
            // });

        });
    </script>
@endpush
