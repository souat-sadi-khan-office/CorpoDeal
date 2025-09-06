@extends('backend.layouts.app', ['modal' => 'lg'])
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
                <b>Invoice {{ strtoupper($order['unique_id']) }}</b>
                <button class="btn btn-xs p-0 ms-1" onclick="copyUniqueId('{{ strtoupper(str_replace('#', '', $order['unique_id'])) }}')">
                    <i class="bi bi-copy"></i>
                </button><br>
                <br>
                <b>Shipping Address:</b> {!! add_line_breaks($order['shipping_address']) !!}<br>
                <b>Payment Status:</b> <span
                    class="py-1 badge text-bg-{{ $order['payment_status'] == 'Paid' ? 'success' : 'danger' }}">{{ str_replace('-', ' ', $order['payment_status']) }}</span><br>
                <b>Shipping Method:</b> <span class="badge text-bg-info"> {{ $order['shipping_method'] }}</span>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="col-md-12">
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
                        @if($order['payment_slip'])
                        <!-- Trigger Button -->
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#slipModal">
                                <i class="bi bi-receipt"></i>

                                View Payment Info
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="slipModal" tabindex="-1" aria-labelledby="slipModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5 class="modal-title" id="slipModalLabel">Payment Slip Information</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="input-group mb-3">
                                                <label class="input-group-text" for="payment_status_slip">Update Payment Status</label>
                                                <select name="payment_status_slip" class=" y-3 form-select" id="payment_status_slip"
                                                        data-stock-ids-and-qtys="{{ json_encode($order['stock_ids_and_qtys']) }}"
                                                        data-order-id="{{ $order['id'] }} ">
                                                    <option value="Paid" {{ $order['payment_status'] == 'Paid' ? 'selected' : '' }}>Paid
                                                    </option>
                                                    <option value="Not_Paid" {{ $order['payment_status'] == 'Not Paid' ? 'selected' : '' }}>
                                                        Unpaid
                                                    </option>
                                                </select>
                                            </div>


                                            <p><strong>Slip Number:</strong> {{ $order['slip_number'] ?? 'N/A' }}</p>

                                            @if(!empty($order['payment_slip']))
                                                <div class="text-center">
                                                    <img src="{{ asset($order['payment_slip']) }}" alt="Payment Slip" class="img-fluid rounded border" style="max-height: 400px;">
                                                </div>
                                            @else
                                                <p class="text-muted">No payment slip uploaded.</p>
                                            @endif

                                        </div>

                                    </div>
                                </div>
                            </div>

                        @endif

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

        {{-- <div class="col-md-12 mt-3">
            <div class="card">
                <div class="card-header">
                    <h2 class="h5 mb-0">Order Status</h2>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h2 class="h5 mb-0">Payment Status</h2>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        
                    </div>
                </div>
            </div>
        </div> --}}

        @if (Auth::guard('admin')->user()->hasPermissionTo('all-order.update'))
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="card-title">Order Notes</h4>
                            </div>
                            {{-- <button type="button" data-url="{{ route('admin.order-log.create', $order['id']) }}" id="content_management" class="btn btn-sm btn-outline-dark">Add New</button> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.order.update.with.note', $order['id']) }}" id="order_status_form" method="POST">
                            <div class="row">
                                <div class="col-md-12 form-group mb-3">
                                    <label for="order_status">Order Status</label>
                                    <select name="order_status" class="form-control" data-order-id="{{ $order['id'] }}" id="order_status">
                                        <option value="new_order" {{ $order['status'] == 'new_order' ? 'selected' : '' }}>New Order
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
                                        <option value="cancelled" {{ $order['status'] == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>

                                <div class="col-md-12 form-group mb-3">
                                    <label for="payment_status">Payment Status:</label>
                                    <input type="hidden" name="stock_ids_and_qtys" value="{{ json_encode($order['stock_ids_and_qtys']) }}">
                                    <select name="payment_status"
                                            data-stock-ids-and-qtys=""
                                            data-order-id={{ $order['id'] }} class="form-control" id="payment_status">
                                        <option value="Paid" {{ $order['payment_status'] == 'Paid' ? 'selected' : '' }}>Paid
                                        </option>
                                        <option value="Not_Paid" {{ $order['payment_status'] == 'Unpaid' ? 'selected' : '' }}>
                                            Unpaid
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="content">Notes <span class="text-danger">*</span></label>
                                    <textarea name="content" id="content" cols="30" rows="3" class="form-control" required></textarea>
                                </div>

                                <div class="col-md-12 mb-3 text-center">
                                    <button class="btn btn-block btn-soft-success" name="submit_btn" type="submit" id="submit">
                                        <i class="bi bi-send"></i>
                                        Update Status
                                    </button>
                                    <button class="btn btn-soft-warning" style="display: none;" id="submitting" type="button" disabled>
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Processing...
                                    </button>
                                </div>
                            </div>
                        </form>

                        @if (count($order['logs']) > 0)
                            <ul class="list-group">
                                @foreach ($order['logs'] as $log)
                                    <li class="list-group-item pb-3">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <p><b>{{ $log->user ? $log->user->name : 'No User' }}</b></p>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <small class="text-muted">{{ date('d F, Y h:i:s', strtotime($log->created_at)) }}</small> 
                                            </div>
                                        </div>
                                        <hr>
                                        <b>{{ $log->subject }}</b> <br>
                                        {!! nl2br($log->content) !!}
                                    </li>
                                @endforeach
                            </ul>
                        @else    
                            <p>Nothing to show.</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection

@push('script')
    <style>
        .is-invalid {
            border-color: #dc3545 !important;
        }
    </style>

    <script>
        _componentRemoteModalLoadAfterAjax();

        function copyUniqueId(uniqueId) {
            const tempInput = document.createElement('input');
            tempInput.value = uniqueId;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            toastr.success('Order ID Copied: ' + uniqueId);
        }

        // document.addEventListener('DOMContentLoaded', function () {
        //     const orderStatusSelect = document.getElementById('order_status');
        //     const paymentStatusSelect = document.getElementById('payment_status');
        //     const paymentStatusSLIPSelect = document.getElementById('payment_status_slip');
        //     const currentStatus = orderStatusSelect.value;

        //     // Define restricted status transitions
        //     const restrictions = {
        //         "pending": ["new_order"],
        //         "packaging": ["new_order", "pending"],
        //         "shipping": ["pending", "packaging"],
        //         "out_of_delivery": ["pending", "packaging", "shipping"],
        //         "delivered": ["pending", "packaging", "shipping", "out_of_delivery"],
        //         "returned": ["pending", "packaging", "shipping", "out_of_delivery", "delivered"]
        //     };

        //     // Apply restrictions based on current status
        //     Array.from(orderStatusSelect.options).forEach(option => {
        //         if (restrictions[currentStatus]?.includes(option.value)) {
        //             option.disabled = true;
        //         }
        //         // Enable "failed" as it has no restriction
        //         if (option.value === "failed") {
        //             option.disabled = false;
        //         }
        //     });

        //     // Handle change event for order status with AJAX call
        //     orderStatusSelect.addEventListener('change', function () {
        //         const orderId = orderStatusSelect.getAttribute('data-order-id');
        //         const selectedStatus = orderStatusSelect.value;
        //         console.log(1);

        //         $.ajax({
        //             url: `{{ route('admin.order.update.status', ':orderId') }}`.replace(':orderId',
        //                 orderId),
        //             type: 'POST',
        //             data: {
        //                 type: 'order_status',
        //                 value: selectedStatus,
        //                 _token: '{{ csrf_token() }}'
        //             },
        //             success: function (data) {
        //                 if (data.status) {
        //                     toastr.success(data.message);
        //                 } else {
        //                     toastr.warning(data.message);
        //                 }
        //             },
        //             error: function (error) {
        //                 toastr.error('Failed to update order status.');
        //             }
        //         });
        //     });

        //     // Handle change event for payment status with AJAX call
        //     paymentStatusSelect.addEventListener('change', function () {
        //         const orderId = paymentStatusSelect.getAttribute('data-order-id');
        //         const selectedPaymentStatus = paymentStatusSelect.value;
        //         const stockIdsAndQtys = JSON.parse(paymentStatusSelect.getAttribute(
        //             'data-stock-ids-and-qtys'));

        //         $.ajax({
        //             url: `{{ route('admin.order.update.status', ':orderId') }}`.replace(':orderId',
        //                 orderId),
        //             type: 'POST',
        //             data: {
        //                 type: 'payment_status',
        //                 value: selectedPaymentStatus,
        //                 stock_ids_and_qtys: stockIdsAndQtys,
        //                 _token: '{{ csrf_token() }}'
        //             },
        //             success: function (data) {
        //                 if (data.status) {
        //                     toastr.success(data.message);
        //                 } else {
        //                     toastr.warning(data.message);
        //                 }
        //             },
        //             error: function (error) {
        //                 toastr.error('Failed to update payment status.');
        //             }
        //         });
        //     });

        //     // Handle change event for payment status with AJAX call
        //     paymentStatusSLIPSelect.addEventListener('change', function () {
        //         const orderId = paymentStatusSLIPSelect.getAttribute('data-order-id');
        //         const selectedPaymentStatus = paymentStatusSLIPSelect.value;
        //         const stockIdsAndQtys = JSON.parse(paymentStatusSLIPSelect.getAttribute(
        //             'data-stock-ids-and-qtys'));

        //         $.ajax({
        //             url: `{{ route('admin.order.update.status', ':orderId') }}`.replace(':orderId',
        //                 orderId),
        //             type: 'POST',
        //             data: {
        //                 type: 'payment_status',
        //                 value: selectedPaymentStatus,
        //                 stock_ids_and_qtys: stockIdsAndQtys,
        //                 _token: '{{ csrf_token() }}'
        //             },
        //             success: function (data) {
        //                 if (data.status) {
        //                     toastr.success(data.message);
        //                 } else {
        //                     toastr.warning(data.message);
        //                 }
        //             },
        //             error: function (error) {
        //                 toastr.error('Failed to update payment status.');
        //             }
        //         });
        //     });
        // });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const orderStatusSelect = document.getElementById('order_status');
            const paymentStatusSelect = document.getElementById('payment_status');
            const originalOrderStatus = orderStatusSelect.value;
            const originalPaymentStatus = paymentStatusSelect.value;

            const restrictions = {
                "pending": ["new_order"],
                "packaging": ["new_order", "pending"],
                "shipping": ["pending", "packaging"],
                "out_of_delivery": ["pending", "packaging", "shipping"],
                "delivered": ["pending", "packaging", "shipping", "out_of_delivery"],
                "returned": ["pending", "packaging", "shipping", "out_of_delivery", "delivered"]
            };

            // Disable invalid options
            Array.from(orderStatusSelect.options).forEach(option => {
                if (restrictions[originalOrderStatus]?.includes(option.value)) {
                    option.disabled = true;
                }
                if (option.value === "failed") {
                    option.disabled = false;
                }
            });

            $(document).on('submit', '#order_status_form', function (e) {
                e.preventDefault();

                const form = this;
                const formData = new FormData(form);

                const newOrderStatus = $('#order_status').val();
                const newPaymentStatus = $('#payment_status').val();
                const notes = $('#content').val().trim();

                let isValid = true;

                // Validation
                if (!notes) {
                    $('#content').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#content').removeClass('is-invalid');
                }

                if (!newOrderStatus) {
                    $('#order_status').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#order_status').removeClass('is-invalid');
                }

                if (!newPaymentStatus) {
                    $('#payment_status').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#payment_status').removeClass('is-invalid');
                }

                if (!isValid) {
                    toastr.error('Please fill all required fields properly.');
                    return;
                }

                const orderStatusChanged = newOrderStatus !== originalOrderStatus;
                const paymentStatusChanged = newPaymentStatus !== originalPaymentStatus;

                const sendAjax = () => {
                    $('#submit').hide();
                    $('#submitting').show();

                    $.ajax({
                        url: $(form).attr('action'),
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: 'JSON',
                        success: function (data) {
                            $('#submit').show();
                            $('#submitting').hide();

                            if (data.status) {
                                toastr.success(data.message);
                                if (data.load) {
                                    setTimeout(() => window.location.reload(), 1000);
                                }
                                if (data.goto) {
                                    setTimeout(() => window.location.href = data.goto, 1000);
                                }
                            } else {
                                toastr.error(data.message);
                            }
                        },
                        error: function (data) {
                            $('#submit').show();
                            $('#submitting').hide();

                            const json = data.responseJSON;
                            if (json?.errors) {
                                $.each(json.errors, (key, messages) => {
                                    $(`#${key}`).addClass('is-invalid');
                                    toastr.error(messages[0]);
                                });
                            } else {
                                toastr.error(json?.message || 'An unexpected error occurred.');
                            }
                        }
                    });
                };

                if (orderStatusChanged || paymentStatusChanged) {
                    let message = '';
                    if (orderStatusChanged) {
                        message += `<b>Order Status:</b> ${originalOrderStatus} → ${newOrderStatus}<br>`;
                        formData.append('type', 'order_status');
                        formData.append('value', newOrderStatus);
                    }
                    if (paymentStatusChanged) {
                        message += `<b>Payment Status:</b> ${originalPaymentStatus} → ${newPaymentStatus}<br>`;
                        formData.append('type', 'payment_status');
                        formData.append('value', newPaymentStatus);
                        const stockIdsAndQtys = $('#payment_status').data('stock-ids-and-qtys');
                        // formData.append('stock_ids_and_qtys', JSON.stringify(stockIdsAndQtys));
                    }
                    message += `<b>Notes:</b> ${notes}`;

                    Swal.fire({
                        title: 'Confirm Status Change',
                        html: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, change it',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            sendAjax();
                        }
                    });
                } else {
                    toastr.warning("No status change detected.");
                }
            });
        });

    </script>
@endpush
