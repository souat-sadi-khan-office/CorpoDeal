@extends('frontend.layouts.app', ['title' => 'Order Details | '. get_settings('system_name')])

@push('breadcrumb')
    <div class="breadcrumb_section page-title-mini">
        <div class="custom-container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">
                                <i class="linearicons-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                Account
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Order History
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endpush

@section('content')
    <div class="section bg_gray pt-2">
        <div class="custom-container">
            <div class="row">
                <div class="col-lg-12 dashboard_content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    @if($order['is_manual_pay'] && $order['payment_status'] != 'Paid' && !$order['payment_slip'])
                                        <div class="alert alert-success alert-dismissible fade show my-2">
                                            Your order has been placed. <br>
                                            Now make the payment to the bank within the next 7 days. <br>
                                            Upload the payment slip from Upload Slip button. <br>
                                            Once uploaded, we’ll process your order next. <br>
                                            After Approving payment slip your delivery date Countdown will be started.
                                        </div>
                                    @endif

                                    @if(session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                        </div>
                                    @endif

                                    @if(session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{ session('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                        </div>
                                    @endif
                                    <div class="row no-print text-right">
                                        <div class="col-12">
                                            @if($order['is_manual_pay'] && $order['payment_status'] != 'Paid' && !$order['payment_slip'])
                                                @push('styles')
                                                    <link rel="stylesheet"
                                                          href="{{asset('backend/assets/css/custom-animate.css')}}">
                                                @endpush
                                            <!-- Button trigger modal -->
                                                <button type="button" class="btn btn-success border-2 btn-sm"
                                                        data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                    <i class="fas fa-upload"></i>
                                                    Upload Slip
                                                </button>

                                            @endif
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <a href="{{ route('account.order.invoice', ['id' => encode($order['id']), 'download' => true]) }}"
                                                   class="btn btn-outline-dark btn-sm">
                                                    <i class="fas fa-print"></i>
                                                    Print
                                                </a>

                                                <a href="/order/track/{{ str_replace('#', '', $order['unique_id']) }}"
                                                   class="btn btn-outline-dark btn-sm" target="_blank">
                                                    Track Order
                                                </a>

                                                <a href="{{ route('account.my_orders') }}"
                                                   class="btn btn-outline-dark btn-sm">
                                                    All Orders
                                                </a>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="invoice p-3 mb-3">
                                        <!-- info row -->
                                        <div class="row invoice-info">
                                            <div class="col-sm-4 invoice-col">
                                                From
                                                <address>
                                                    <strong>{{ get_settings('system_name') }}.</strong><br>
                                                    {{ get_settings('system_footer_contact_address') }} <br>
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
                                            <div class="col-sm-4 invoice-col">
                                                <b>Invoice {{ strtoupper($order['unique_id']) }}</b><br><br>
                                                <b>Shipping
                                                    Address:</b> {!! add_line_breaks($order['shipping_address']) !!}<br>
                                                <b>Payment Status:</b> <span
                                                    class="py-1 badge text-bg-{{ $order['payment_status'] == 'Paid' ? 'success' : 'danger' }}">{{ str_replace('-', ' ', $order['payment_status']) }}</span><br>
                                                <b>Shipping Method:</b> <span class="badge text-bg-info">
                                                    {{ $order['shipping_method'] }}</span>
                                            </div>
                                        </div>

                                    <!-- Modal -->
                                        <!-- Modal for Upload Payment Slip -->
                                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Upload Payment Slip {{ strtoupper($order['unique_id']) }}</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Tab navigation -->
                                                        <ul class="nav nav-tabs nav-justified custom-tab" id="paymentTab" role="tablist">
                                                            <li class="nav-item" role="presentation">
                                                                <a class="nav-link active" id="uploadSlipTab" data-bs-toggle="tab" href="#uploadSlip" role="tab" aria-controls="uploadSlip" aria-selected="true">
                                                                    Upload Slip
                                                                </a>
                                                            </li>
                                                            <li class="nav-item" role="presentation">
                                                                <a class="nav-link" id="paymentInstructionsTab" data-bs-toggle="tab" href="#paymentInstructions" role="tab" aria-controls="paymentInstructions" aria-selected="false">
                                                                    Payment Instructions
                                                                </a>
                                                            </li>
                                                        </ul>

                                                        <!-- Tab content -->
                                                        <div class="tab-content" id="paymentTabContent">
                                                            <!-- Upload Slip Tab -->
                                                            <div class="tab-pane fade show active" id="uploadSlip" role="tabpanel" aria-labelledby="uploadSlipTab">
                                                                <form action="{{ route('payment-slip.upload', $order['id']) }}" method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('POST')
                                                                    <div class="row">
                                                                        <div class="col-md-12 form-group mb-3">
                                                                            <label for="slip_number" class="form-label">Transaction Number</label>
                                                                            <input type="text" class="form-control" id="slip_number" name="slip_number" required>
                                                                        </div>
                                                                        <div class="col-md-12 form-group mb-3">
                                                                            <label for="slip" class="form-label">Choose Payment Slip (Image)</label>
                                                                            <input type="file" class="form-control dropify" id="slip" name="slip" required>
                                                                        </div>

                                                                        <div class="col-md-6 mx-auto">
                                                                            <button type="submit" class="btn btn-sm btn-block btn-primary ">Upload Slip</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                            <!-- Payment Instructions Tab -->
                                                            <div class="tab-pane fade" id="paymentInstructions" role="tabpanel" aria-labelledby="paymentInstructionsTab">
                                                                <div class="payment_instruction_data">
                                                                    {!!get_settings('manual_payment_instruction')!!}
                                                                </div>
                                                                {{-- <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                                    Read More
                                                                </button> --}}
                                                            </div>
                                                        </div>
                                                    </div>
{{--                                                    <div class="modal-footer">--}}
{{--                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>--}}
{{--                                                    </div>--}}
                                                </div>
                                            </div>
                                        </div>

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
                                                            <td>
                                                                <a style="text-decoration:none;color: var(--bs-table-color-type);"
                                                                   href="{{ route('slug.handle', $details->slug) }}">{{ $details->name }}</a>
                                                            </td>
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
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                @if ($order['note'])
                                                    <p><span
                                                            class="lead">Order Note:</span>{!! add_line_breaks($order['note'], 35) !!}
                                                    </p>
                                                @endif
                                                <p>Order Date: {{ $order['created_at'] }}</p>
                                                <p>Payment Currency: {{ $order['currency'] }}</p>
                                                <p>Payment Method: {{ $order['gateway_name'] }}</p>
                                                @if($order['payment_slip'])
                                                <!-- Trigger Button -->
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                            data-bs-toggle="modal" data-bs-target="#slipModal">
                                                        <i class="fas fa-paper-plane"></i>
                                                        View Payment Info
                                                    </button>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="slipModal" tabindex="-1"
                                                         aria-labelledby="slipModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                                            <div class="modal-content">

                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="slipModalLabel">Payment
                                                                        Slip Information</h5>
                                                                    <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                </div>

                                                                <div class="modal-body">

                                                                    <p><strong>Slip
                                                                            Number:</strong> {{ $order['slip_number'] ?? 'N/A' }}
                                                                    </p>

                                                                    @if(!empty($order['payment_slip']))
                                                                        <div class="text-center">
                                                                            <img
                                                                                src="{{ asset($order['payment_slip']) }}"
                                                                                alt="Payment Slip"
                                                                                class="img-fluid rounded border"
                                                                                style="max-height: 400px;">
                                                                        </div>
                                                                    @else
                                                                        <p class="text-muted">No payment slip
                                                                            uploaded.</p>
                                                                    @endif

                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                @endif
                                            </div>
                                            <div class="col-6">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <tbody>
                                                        @if(isset($order['premium_user_discount_amount']) && $order['premium_user_discount_amount'])
                                                            <tr>
                                                                <th>Premium User Discount:</th>
                                                                <td class="text-success">
                                                                    <strong>-{{ $order['premium_user_discount_amount'] }}</strong>
                                                                </td>
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
                                                            <td>{{$order['shipping_charge']}}</td>
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
                                        </div>
                                        <!-- this row will not appear when printing -->

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/dropify.min.css') }}">
    <style>
        #imagePreview img {
            max-width: 150px;
            margin: 10px;
            border: 2px solid #ddd;
        }

        .payment_instruction_data table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            overflow: hidden;
        }

        .payment_instruction_data table td, table th {
            padding: 14px 18px;
            border-bottom: 1px solid #eaeaea;
            vertical-align: top;
            font-size: 15px;
            color: #333;
        }

        .payment_instruction_data table tr:first-child td {
            background-color: #2a9d8f;
            color: #fff;
            font-weight: 600;
            font-size: 16px;
        }

        .payment_instruction_data table tr:last-child td {
            border-bottom: none;
        }

        .payment_instruction_data table td:first-child {
            background-color: #f9f9f9;
            font-weight: 500;
            width: 35%;
            color: #555;
        }

        .payment_instruction_data table td:last-child {
            color: #222;
        }

        .custom-tab .nav-link {
            background-color: #f5f7fa;
            color: #495057;
            border: 1px solid #dee2e6;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .custom-tab .nav-link.active {
            background-color: var(--primary-color);
            color: #fff;
            border-color: var(--primary-color) var(--primary-color) #fff;
        }

        .custom-tab .nav-link:hover {
            background-color: #e0f2f1;
            color: var(--primary-color);
        }

        .nav-tabs.nav-justified .nav-link {
            border-radius: 0;
        }

        .tab-content {
            background-color: #fff;
        }

    </style>
@endpush
@push('scripts')
    <script src="{{ asset('backend/assets/js/dropify.min.js') }}"></script>
    <script>
        $('.dropify').dropify({
            imgFileExtensions: ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp']
        });
    </script>
@endpush
