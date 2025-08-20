@extends('backend.layouts.app', ['modal' => 'md'])
@section('title', 'Activity Logs')
@push('style')
    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush
@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-8">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Activity Logs - <span class="badge bg-dark" id="count">{{$count}}</span>
                            {{isset(request()->between)? ' / '.ucwords(str_replace('_',' ',request()->between)):''}}
                            {{isset(request()->from)? '/ From:'.request()->from:''}}
                            {{isset(request()->to)? '/ Till:'.request()->to:''}}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <form id="product_search_form" action="{{ url()->current() }}" method="GET" class="row">
                    <div class="col-md-6 mb-3 form-group">
                        <div class="row">
                            <div class="col-6 form-group">
                                <label for="date_range_from">From:</label>
                                <input type="date" id="date_range_from" value="{{ request()->from }}" name="from" class="form-control">
                            </div>
                            <div class="col-6 form-group">
                                <label for="date_range_to">To:</label>
                                <input type="date" id="date_range_to" value="{{ request()->to }}" name="to" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3 form-group">
                        <label for="between">Search by Date</label>
                        <select name="between" id="between" class="form-control">
                            <option value="">All Time</option>
                            <option value="last_day" {{ request()->between === 'last_day' ? 'selected' : '' }}>Last Day</option>
                            <option value="last_week" {{ request()->between === 'last_week' ? 'selected' : '' }}>Last Week</option>
                            <option value="last_month" {{ request()->between === 'last_month' ? 'selected' : '' }}>Last Month</option>
                            <option value="last_year" {{ request()->between === 'last_year' ? 'selected' : '' }}>Last Year</option>
                        </select>
                    </div>
{{--                    <div class="col-md-2 mb-3 form-group">--}}
{{--                        <label for="paginate">Per Page</label>--}}
{{--                        <input type="number" min="1" max="1000" name="paginate" id="paginate" value="{{ request()->paginate ?? 15 }}" class="form-control">--}}
{{--                    </div>--}}
                    <div class="col-md-3 mb-3 form-group">
                        <label for="action">Search by Action</label>
                        <select name="action" id="action" class="form-control">
                            <option value="">All</option>
                            <option value="create" {{ request()->action === 'create' ? 'selected' : '' }}>Create</option>
                            <option value="update" {{ request()->action === 'update' ? 'selected' : '' }}>Update</option>
                            <option value="view" {{ request()->action === 'view' ? 'selected' : '' }}>View</option>
                            <option value="delete" {{ request()->action === 'delete' ? 'selected' : '' }}>Delete</option>
                            <option value="default" {{ request()->action === 'default' ? 'selected' : '' }}>Default</option>
                        </select>
                    </div>

                    <div class="col-md-8 mb-3 form-group">
                        <label for="activity_type">Activity Type</label>
                        <select name="activity_type" id="activity_type" class="form-control select">
                            <option value="">--Select--</option>
                            @foreach($types as $key => $type)
                                <option value="{{ $type }}" {{ request()->activity_type === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>
{{--                    <div class="col-md-5 mb-3 form-group">--}}
{{--                        <label for="search">Search by User/Admin/Activity</label>--}}
{{--                        <input type="text" name="find" id="search" value="{{ request()->find }}" class="form-control p-2 mt-1">--}}
{{--                    </div>--}}
                    <div class="col-md-4 my-auto mx-auto form-group text-center">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <button type="button" id="reset_button" class="btn btn-secondary">Reset</button>
                    </div>
                </form>
            </div>

            <div class="col-md-12 table-responsive">
                <table id="activity_log_table" class="table table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Activity Type</th>
                        <th>Activity</th>
                        <th>Created At</th>
                        <th>Action</th>

                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>

{{--                <div id="pagination-container">--}}
{{--                    @include('frontend.components.paginate', ['products' => $activitylogs,'page'=>request()->page])--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    <script>

        $(document).ready(function () {
            var table = $('#activity_log_table').DataTable({
                processing: true,
                serverSide: true,
                orderable:false,
                ajax: {
                    url: '{{ route('admin.activity.log') }}',
                    type: 'GET',
                    data: function (d) {
                        d.find = $('#search').val();
                        d.activity_type = $('#activity_type').val();
                        d.action = $('#action').val();
                        d.from = $('#date_range_from').val();
                        d.to = $('#date_range_to').val();
                        d.between = $('#between').val();
                        d.paginate = $('#paginate').val();
                    },dataSrc: function (json) {
                        $('#count').text(json.recordsTotal);
                        return json.data;
                    }
                },
                columnDefs: [
                    { targets: '_all', orderable: false }
                ],
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false },
                    { data: 'user', name: 'user' },
                    { data: 'type', name: 'type' },
                    { data: 'activity', name: 'activity' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'status', name: 'status' },
                ],order: [[0, 'desc']]

            });

            // Filter Button Click
            $('#product_search_form').on('submit', function (e) {
                e.preventDefault();
                table.ajax.reload();
            });

            // Reset Filters
            $('#reset_button').click(function () {
                $('#product_search_form')[0].reset();
                table.ajax.reload();
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            _componentRemoteModalLoadAfterAjax();
            _componentSelect();
            const between = document.getElementById('between');
            const dateFrom = document.getElementById('date_range_from');
            const dateTo = document.getElementById('date_range_to');

            between.addEventListener('change', function () {
                if (between.value) {
                    dateFrom.disabled = true;
                    dateTo.disabled = true;
                    dateFrom.value = '';
                    dateTo.value = '';
                } else {
                    dateFrom.disabled = false;
                    dateTo.disabled = false;
                }
            });

            function checkDateInputs() {
                between.disabled = !!(dateFrom.value || dateTo.value);
            }

            dateFrom.addEventListener('input', function () {
                checkDateInputs();
                if (dateTo.value && dateFrom.value > dateTo.value) {
                    dateFrom.value = '';
                    alert('The "From" date cannot be later than the "To" date.');
                }
            });

            dateTo.addEventListener('input', function () {
                checkDateInputs();
                if (dateFrom.value && dateTo.value < dateFrom.value) {
                    dateTo.value = '';
                    alert('The "To" date cannot be earlier than the "From" date.');
                }
            });

        });
    </script>
@endpush
