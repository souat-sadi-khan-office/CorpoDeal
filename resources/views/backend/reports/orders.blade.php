@extends('backend.layouts.app')
@section('title', 'Orders Report')

@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-8">
                    <h1 class="h3 mb-0">Orders Report - <span class="badge bg-dark">{{$amounts['count']}}</span></h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Product Sell Report
                            {{isset(request()->between)? ' / '.ucwords(str_replace('_',' ',request()->between)):''}}
                            {{isset(request()->payment_status)? ' / Payment:'.ucwords(str_replace('_',' ',request()->payment_status)):''}}
                            {{isset(request()->status)? ' / Status:'.ucwords(str_replace('_',' ',request()->status)):''}}
                            {{isset(request()->from)? '/ From:'.request()->from:''}}
                            {{isset(request()->to)? '/ Till:'.request()->to:''}}
                        </li>
                    </ol>
                </div>

                <div class="col-sm-4 text-end">
                    <a href="{{ route('admin.order.index') }}" class="btn btn-soft-success">
                        View Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 mb-3">
                <form id="product_search_form" action="{{ url()->current() }}" method="GET" class="row">
                    <div class="col-md-4 mb-3 form-group">
                        <label for="payment_method">Search by Payment Method</label>
                        <select name="payment_method" id="payment_method" class="form-control">
                            <option disabled selected>Search by Payment Method</option>
                            <option value="cod" {{ request()->payment_method === 'cod' ? 'selected' : '' }}>Cash On
                                Delivery
                            </option>
                            <option value="Gateway" {{ request()->payment_method === 'Gateway' ? 'selected' : '' }}>
                                Gateway
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3 form-group">
                        <label for="payment_status">Search by Payment Status</label>
                        <select name="payment_status" id="payment_status" class="form-control">
                            <option disabled selected>Search by Payment Status</option>
                            <option value="Paid" {{ request()->payment_status === 'Paid' ? 'selected' : '' }}>Paid
                            </option>
                            <option value="Not_Paid" {{ request()->payment_status === 'Not_Paid' ? 'selected' : '' }}>
                                UnPaid
                            </option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3 form-group">
                        <label for="between">Search by Date</label>
                        <select name="between" id="between" class="form-control">
                            <option value="">All Time</option>
                            <option value="last_day" {{ request()->between === 'last_day' ? 'selected' : '' }}>Last
                                day
                            </option>
                            <option value="last_week" {{ request()->between === 'last_week' ? 'selected' : '' }}>Last
                                Week
                            </option>
                            <option value="last_month" {{ request()->between === 'last_month' ? 'selected' : '' }}>Last
                                Month
                            </option>
                            <option value="last_year" {{ request()->between === 'last_year' ? 'selected' : '' }}>Last
                                Year
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3 form-group">
                        <label for="status">Search by Order Status</label>
                        <select name="status" id="payment_status" class="form-control">
                            <option disabled selected>Search by Order Status</option>
                            <option value="pending" {{ request()->status === 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="packaging" {{ request()->status === 'packaging' ? 'selected' : '' }}>
                                Packaging
                            </option>
                            <option value="shipping" {{ request()->status === 'shipping' ? 'selected' : '' }}>
                                Shipping
                            </option>
                            <option
                                value="out_of_delivery" {{ request()->status === 'out_of_delivery' ? 'selected' : '' }}>
                                Out Of Delivery
                            </option>
                            <option value="delivered" {{ request()->status === 'delivered' ? 'selected' : '' }}>
                                Delivered
                            </option>
                            <option value="returned" {{ request()->status === 'returned' ? 'selected' : '' }}>
                                Returned
                            </option>
                            <option value="failed" {{ request()->status === 'failed' ? 'selected' : '' }}>
                                Failed
                            </option>
                            <option
                                value="refund_requested" {{ request()->status === 'refund_requested' ? 'selected' : '' }}>
                                Refund Requested
                            </option>
                        </select>
                    </div>
                    <div class="col-md-8 mb-3 form-group">
                        <div class="row">
                            <div class="col-6 form-group">
                                <label for="date_range_from">From:</label>
                                <input type="date" id="date_range_from" value="{{ request()->from }}" name="from"
                                       class="form-control">
                            </div>
                            <div class="col-6 form-group">
                                <label for="date_range_to">To:</label>
                                <input type="date" id="date_range_to" value="{{ request()->to }}" name="to"
                                       class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 form-group text-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <button type="button" id="reset_button" class="btn btn-secondary">Reset</button>
                    </div>
                </form>

                <div class="row my-2">
                    <div class="col-md-3">
                        <div class="info-box shadow-sm"
                             style="background: repeating-linear-gradient(149deg, #2336b8, black 1000px); color:white">
                            <span class="info-box-icon">
                                <i class="bi bi-columns-gap"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Order Amount</span>
                                <span class="info-box-number">{{ $amounts['orderSum'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box  text-bg-primary gredient-box-primary">
                            <span class="info-box-icon">
                                <i class="bi bi-grid-1x2"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Tax Amount</span>
                                <span class="info-box-number">{{ $amounts['taxSum'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box text-bg-secondary gredient-box-secondary">
                            <span class="info-box-icon">
                                <i class="bi bi-columns"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Discount Amount</span>
                                <span class="info-box-number">{{ $amounts['discountSum'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box text-bg-info gredient-box-info">
                            <span class="info-box-icon">
                                <i class="bi bi-columns-gap"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Grand Total</span>
                                <span class="info-box-number">{{ $amounts['totalSum'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Order</th>
                        <th scope="col">Payment Method</th>
                        <th scope="col">Payment Status</th>
                        <th scope="col">Order Status</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Discount</th>
                        <th scope="col">Tax</th>
                        <th scope="col">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $report)
                        <tr>
                            <th scope="row">
                                <a class="dropdown-item" href="{{ route('admin.order.details', $report['id']) }}"
                                   target="_blank">
                                    {{strtoupper($report['unique_id'])}}

                                </a>
                            </th>
                            <td>
                                {{$report['gateway_name']}}
                            </td>
                            <td>
                                <div class="text-center">
                                    <span
                                        class="badge bg-{{$report['payment_status'] == 'Paid' ? 'success' : 'danger'}} text-white">
                                        {{$report['payment_status'] }}
                                    </span>
                                </div>

                            </td>
                            <td>
                                <div class="text-center">

                                    <span
                                        class="badge bg-{{$report['status_badge']}} text-white">
                                        {{$report['status'] }}
                                    </span>
                                </div>
                            </td>
                            <td>{{$report['order_amount']}}</td>
                            <td>{{$report['discount_amount']}}</td>
                            <td>{{$report['tax_amount']}}</td>
                            <td>{{$report['final_amount']}}</td>

                        </tr>
                    @endforeach

                    </tbody>
                </table>

                @include('frontend.components.paginate',['products'=>$data])
            </div>
        </div>
    </div>


@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const between = document.getElementById('between');
            const dateFrom = document.getElementById('date_range_from');
            const dateTo = document.getElementById('date_range_to');

            // Enable or disable date range fields based on 'between' selection
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

            // Enable or disable 'between' select based on date range inputs
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

            document.getElementById('reset_button').addEventListener('click', function () {
                // Clear all form inputs
                document.getElementById('product_search_form').reset();

                // Remove any query parameters in the URL by reloading the current page without them
                window.location.href = "{{ url()->current() }}";
            });
        });
    </script>

@endpush

