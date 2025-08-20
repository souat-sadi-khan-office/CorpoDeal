@extends('backend.layouts.app')
@section('title', 'Stock Purchase Report')

@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-8">
                    <h1 class="h3 mb-0">Stock Purchase Report - <span
                            class="badge bg-dark">{{$amounts['count']}}</span></h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Stock Purchase Report
                            {{isset(request()->between)? ' / '.ucwords(str_replace('_',' ',request()->between)):''}}
                            {{isset(request()->from)? '/ From:'.request()->from:''}}
                            {{isset(request()->to)? '/ Till:'.request()->to:''}}
                        </li>
                    </ol>
                </div>

                <div class="col-sm-4 text-end">
                    <a href="{{ route('admin.stock.index') }}" class="btn btn-soft-success">
                        View Stocks
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
                        <label for="between">Search by Purchase  Date</label>
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
                    <div class="row my-2">
                        <div class="col-md-3">
                            <div class="info-box text-bg-info gredient-box-info">
                            <span class="info-box-icon">
                                <i class="bi bi-columns-gap"></i>
                            </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Grand Total Purchase</span>
                                    <span class="info-box-number">{{ $amounts['totalSum'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <label for="search"> Search by Product name or SKU</label>
                                <input type="text" class="form-control" placeholder="Search by Product name or SKU" value="{{request()->search}}" name="search">
                        </div>
                    </div>

                    <div class="col-md-12 form-group text-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <button type="button" id="reset_button" class="btn btn-secondary">Reset</button>
                    </div>
                </form>

            </div>

            <div class="col-md-12 table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Product</th>
                        <th scope="col">SKU</th>
                        <th scope="col">Purchase Quantity</th>
                        <th scope="col">Purchase Unit Price</th>
                        <th scope="col">Purchase Total</th>
                        <th scope="col">Current Stock</th>
                        <th scope="col">Unit Price</th>
                        <th scope="col">Is Sellable</th>
                        <th scope="col">Purchased By</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $report)
                        <tr>
                            <th scope="row">
                               {{$loop->iteration}}
                            </th>
                            <td>
                                {{$report['product_name']}}
                            </td>
                            <td>{{$report['sku']}}</td>
                            <td>{{$report['quantity']}}</td>
                            <td>{{$report['purchase_unit_price']}}</td>
                            <td>{{$report['purchase_total_price']}}</td>
                            <td>{{$report['stock']}}</td>
                            <td>{{$report['unit_price']}}</td>
                            <td>
                                <div class="text-center">
                                    <span
                                        class="badge bg-{{$report['is_sellable'] == 1 ? 'success' : 'danger'}} text-white">
                                        {{$report['is_sellable']==1?"YES":"NO" }}
                                    </span>
                                </div>
                            </td>
                            <td>{{$report['admin_name']}} <br>
                                {{$report['created_at']}} <br>
                                @if(isset($report['file']))
                                <a href="{{asset($report['file'])}}">
                                    <i class="bi bi-file-earmark-medical"></i>
                                </a>
                                @endif
                            </td>

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

