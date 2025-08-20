<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Invoice {{ strtoupper($order['unique_id']) }}
        | {{ get_settings('system_name') ? get_settings('system_name') : 'Project Alpha' }}</title>
    <link rel="stylesheet" href="{{ asset('backend/assets/css/font_source_sans3.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/adminlte.css') }}">

    @stack('style')

    <style>
        @media print {
            /* Hide any unwanted elements during printing */
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>
<div class="invoice p-3 mb-3">
    <!-- title row -->
    <div class="row pb-2">
        <div class="col-12">
            <h4 class="text-center">
                <a href="{{ route('admin.dashboard') }}" class="brand-link" style="text-decoration: none">
                    <img style="height: 40px"
                         src="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}"
                         alt="App Logo" class="brand-image">
                </a>
                <br>
                <p class="float-right">{{ get_system_time(now()) }}, {{ now()->format('M Y') }}</p>
            </h4>
        </div>
        <hr>
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
        <div class="col-sm-4 invoice-col">
            <b>Invoice {{ strtoupper($order['unique_id']) }}</b><br><br>
            <b>Shipping Address:</b> {!! add_line_breaks($order['shipping_address']) !!}<br>
            <b>Payment Status:</b> <span
                class="py-1 badge text-bg-{{ $order['payment_status'] == 'Paid' ? 'success' : 'danger' }}">{{ str_replace('-', ' ', $order['payment_status']) }}</span><br>
            <b>Shipping Method:</b> <span class="badge text-bg-info"> {{ $order['shipping_method'] }}</span>
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
    </div>

    <div class="row">
        <div class="col-6">
            @if ($order['note'])
                <p><span class="lead">Order Note:</span>{!! add_line_breaks($order['note'], 35) !!}</p>
            @endif
            <p>Order Date: {{ $order['created_at'] }}</p>
            <p>Payment Currency: {{ $order['currency'] }}</p>
            <p class="lead">Payment Method: {{ $order['gateway_name'] }}</p>
        </div>
        <div class="col-6">
            <p class="lead">Payment : {{ $order['payment_status'] }}</p>
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                    @if(isset($order['premium_user_discount_amount']) && $order['premium_user_discount_amount'])
                        <tr>
                            <th>Premium User Discount:</th>
                            <td class="text-success"><strong>-{{ $order['premium_user_discount_amount'] }}</strong></td>
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
    </div>
</div>

<script>
    // Check if the request has 'download' parameter set to true
    @if ($request->has('download') && $request->input('download') === 'true')
        window.location.href = "{{ route('admin.order.invoice', ['id' => $order['id'], 'download' => true]) }}";
    @else
    window.print();
    @endif

    // Navigate back after printing
    window.onafterprint = function () {
        window.history.back();
    };
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
