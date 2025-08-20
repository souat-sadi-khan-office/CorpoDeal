@extends('backend.layouts.app')
@section('title', 'Order Management')
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
                        <li class="breadcrumb-item active" aria-current="page">Order Details
                            -{{ strtoupper($order['unique_id']) }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="invoice p-3 mb-3 row">
        <!-- title row -->
        <div class="row">
            <div class="col-12">
                <h4>
                    <a href="{{ route('admin.dashboard') }}" class="brand-link" style="text-decoration: none">
                        <img style="height: 40px"
                             src="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-white.png') }}"
                             alt="App Logo" class="brand-image">
                    </a>
                    <small class="float-right">- {{ get_system_time(now()) }},{{ now()->format('M Y') }}</small>
                </h4>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                From
                <address>
                    <strong>{{ get_settings('system_name') }}.</strong><br>
                    {{ get_settings('system_footer_contact_address') }}<br>
                    Phone: {{ get_settings('system_footer_contact_phone') }}<br>
                    Email: {{ get_settings('system_footer_contact_email') }}
                </address>
            </div>
            <div class="col-sm-4 invoice-col">
                To
                <address>
                    <strong>{{ $order['user_name'] }}</strong><br>
                    {!! add_line_breaks($order['billing_address']) !!} <br>
                    Phone: {{ $order['phone'] }}<br>
                    @if ($order['user_company'])
                        Company: {{ ucfirst($order['user_company']) }} <br>
                    @endif
                    Email: {{ $order['email'] }}
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <b>Invoice {{ strtoupper($order['unique_id']) }}</b><br>
                <br>
                <b>Shipping Address:</b> {!! add_line_breaks($order['shipping_address']) !!}<br>
                <b>Payment Status:</b> <span
                    class="py-1 badge text-bg-{{ $order['payment_status'] == 'Paid' ? 'success' : 'danger' }}">{{ str_replace('-', ' ', $order['payment_status']) }}</span><br>
                <b>Shipping Method:</b> <span class="badge text-bg-info"> {{ $order['shipping_method'] }}</span>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="col-md-9">
            <!-- Table row -->
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Discount</th>
                            <th>Tax</th>
                            <th>Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($order['details'] as $details)
                            <tr>
                                <td><a style="text-decoration:none;color: var(--bs-table-color-type);"
                                       href="{{ route('slug.handle', $details->slug) }}">{{ $details->name }}</a></td>
                                <td>{{ $details->qty }}</td>
                                <td>{{ $details->unit_price }}</td>
                                <td>{{ $details->discount }}</td>
                                <td>{{ $details->tax }}</td>
                                <td>{{ $details->total_price }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">
                    @if ($order['note'])
                        <p><span class="lead">Order Note:</span>{!! add_line_breaks($order['note'], 35) !!}</p>
                    @endif
                    <p class="lead">Order Date: {{ $order['created_at'] }}</p>
                    <p class="lead">Payment Currency: {{ $order['currency'] }}</p>
                    <p class="lead">Payment Method: {{ $order['gateway_name'] }}</p>


                </div>
                <!-- /.col -->
                <div class="col-6">
                    <p class="lead">Payment : {{ $order['payment_status'] }}</p>

                    <div class="table-responsive">
                        <table class="table">
                            <tbody>

                            @if(isset($order['premium_user_discount_amount']) && $order['premium_user_discount_amount'])
                                <tr>
                                    <th>Premium User Discount:</th>
                                    <td class="text-success">
                                        <strong>-{{ $order['premium_user_discount_amount'] }}</strong></td>
                                </tr>
                            @endif
                            <tr>
                                <th style="width:50%">Subtotal:</th>
                                <td>{{ $order['order_amount'] }}</td>
                            </tr>
                            <tr>
                                <th>Tax</th>
                                <td>{{ $order['tax_amount'] }}</td>
                            </tr>
                            <tr>
                                <th>Shipping:</th>
                                <td>{{$order['shipping_charge']??0}}</td>

                            </tr>
                            <tr>
                                <th>Discount:</th>
                                <td>{{ $order['discount_amount'] }}</td>
                            </tr>
                            <tr>
                                <th>Total:</th>
                                <td>{{ $order['final_amount'] }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- this row will not appear when printing -->
            <div class="row no-print">
                <div class="col-12">
                    <a href="{{route('admin.order.invoice',$order['id'])}}" rel="noopener" class="btn btn-success istiyak"><i
                            class="bi bi-receipt"></i> Print</a>

                    <a href="{{route('admin.order.invoice',['id' => $order['id'], 'download' => true])}}"
                       class="btn btn-primary istiyak float-right" style="margin-right: 5px;">
                        <i class="bi bi-box-arrow-down"></i>Download Invoice
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            @if (Auth::guard('admin')->user()->hasPermissionTo('all-order.update'))
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0">Order Status</h2>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <select name="order_status" class="form-control" data-order-id="{{ $order['id'] }}"
                                    id="order_status">
                                <option value="pending" {{ $order['status'] == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="packaging" {{ $order['status'] == 'packaging' ? 'selected' : '' }}>Packaging
                                </option>
                                <option value="shipping" {{ $order['status'] == 'shipping' ? 'selected' : '' }}>Shipping
                                </option>
                                <option
                                    value="out_of_delivery" {{ $order['status'] == 'out_of_delivery' ? 'selected' : '' }}>
                                    Out for Delivery
                                </option>
                                <option value="delivered" {{ $order['status'] == 'delivered' ? 'selected' : '' }}>Delivered
                                </option>
                                <option value="returned" {{ $order['status'] == 'returned' ? 'selected' : '' }}>Returned
                                </option>
                                <option value="failed" {{ $order['status'] == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0">Payment Status</h2>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <select name="payment_status"
                                    data-stock-ids-and-qtys="{{ json_encode($order['stock_ids_and_qtys']) }}"
                                    data-order-id={{ $order['id'] }} class="form-control" id="payment_status">
                                <option value="Paid" {{ $order['payment_status'] == 'Paid' ? 'selected' : '' }}>Paid
                                </option>
                                <option value="Not_Paid" {{ $order['payment_status'] == 'Not Paid' ? 'selected' : '' }}>
                                    Unpaid
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const orderStatusSelect = document.getElementById('order_status');
            const paymentStatusSelect = document.getElementById('payment_status');
            const currentStatus = orderStatusSelect.value;

            // Define restricted status transitions
            const restrictions = {
                "packaging": ["pending"],
                "shipping": ["pending", "packaging"],
                "out_of_delivery": ["pending", "packaging", "shipping"],
                "delivered": ["pending", "packaging", "shipping", "out_of_delivery"],
                "returned": ["pending", "packaging", "shipping", "out_of_delivery", "delivered"]
            };

            // Apply restrictions based on current status
            Array.from(orderStatusSelect.options).forEach(option => {
                if (restrictions[currentStatus]?.includes(option.value)) {
                    option.disabled = true;
                }
                // Enable "failed" as it has no restriction
                if (option.value === "failed") {
                    option.disabled = false;
                }
            });

            // Handle change event for order status with AJAX call
            orderStatusSelect.addEventListener('change', function () {
                const orderId = orderStatusSelect.getAttribute('data-order-id');
                const selectedStatus = orderStatusSelect.value;
                console.log(1);

                $.ajax({
                    url: `{{ route('admin.order.update.status', ':orderId') }}`.replace(':orderId',
                        orderId),
                    type: 'POST',
                    data: {
                        type: 'order_status',
                        value: selectedStatus,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        if (data.status) {
                            toastr.success(data.message);
                        } else {
                            toastr.warning(data.message);
                        }
                    },
                    error: function (error) {
                        toastr.error('Failed to update order status.');
                    }
                });
            });

            // Handle change event for payment status with AJAX call
            paymentStatusSelect.addEventListener('change', function () {
                const orderId = paymentStatusSelect.getAttribute('data-order-id');
                const selectedPaymentStatus = paymentStatusSelect.value;
                const stockIdsAndQtys = JSON.parse(paymentStatusSelect.getAttribute(
                    'data-stock-ids-and-qtys'));

                $.ajax({
                    url: `{{ route('admin.order.update.status', ':orderId') }}`.replace(':orderId',
                        orderId),
                    type: 'POST',
                    data: {
                        type: 'payment_status',
                        value: selectedPaymentStatus,
                        stock_ids_and_qtys: stockIdsAndQtys,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        if (data.status) {
                            toastr.success(data.message);
                        } else {
                            toastr.warning(data.message);
                        }
                    },
                    error: function (error) {
                        toastr.error('Failed to update payment status.');
                    }
                });
            });
        });
    </script>
@endpush
