<!-- resources/views/emails/invoice.blade.php -->

@component('mail::message')
# Invoice {{ strtoupper($order['unique_id']) }}

@component('mail::panel')
**Date:** {{ get_system_time(now()) }}, {{ now()->format('M Y') }}
@endcomponent


@component('mail::table')
| From | To |
| ---- | --- |
| **{{get_settings('system_name')}}**<br> {{ get_settings('system_footer_contact_address') }}<br> Phone: {{get_settings('system_footer_contact_phone')}}<br> Email: {{get_settings('system_footer_contact_email')}} | **{{ $order['user_name'] }}**<br> {!! add_line_breaks($order['billing_address']) !!} <br> Phone: {{ $order['phone'] }}<br> @if ($order['user_company']) **Company:** {{ ucfirst($order['user_company']) }}<br> @endif Email: {{ $order['email'] }} |
@endcomponent

## Invoice Details
**Invoice ID:** {{ strtoupper($order['unique_id']) }}<br>
**Shipping Address:** {!! add_line_breaks($order['shipping_address']) !!}<br>
**Payment Status:** @if($order['payment_status'] == 'Paid') Paid @else Unpaid @endif <br>
**Shipping Method:** {{ $order['shipping_method'] }}

<!-- Order details table -->
@component('mail::table')
| Qty  | Product         | Unit Price    | Discount     | Tax     | Subtotal     |
| ---- | --------------- | ------------- | ------------ | ------- | ------------ |
@foreach ($order['details'] as $details)
| {{ $details->qty }} | [{{ $details->name }}]({{ route('slug.handle', $details->slug) }}) | {{ $details->unit_price }} | {{ $details->discount }} | {{ $details->tax }} | {{ $details->total_price }} |
@endforeach
@endcomponent

@if ($order['note'])
**Order Note:**
{!! add_line_breaks($order['note'], 35) !!}
@endif

**Order Date:** {{ $order['created_at'] }} <br>
**Payment Currency:** {{ $order['currency'] }} <br>
**Payment Method:** {{ $order['gateway_name'] }}

## Payment Summary
@component('mail::table')
| Item       | Amount         |
| ---------- | -------------- |
@if(isset($order['premium_user_discount_amount']) && $order['premium_user_discount_amount'])
|Premium User Discount|-{{ $order['premium_user_discount_amount'] }}|
@endif
| Subtotal   | {{ $order['order_amount'] }} |
| Tax        | {{ $order['tax_amount'] }}   |
| Shipping   | {{$order['shipping_charge']}} |
| Discount   | {{ $order['discount_amount'] }} |
| **Total**  | **{{ $order['final_amount'] }}** |
@endcomponent

@component('mail::button', ['url' => route('account.order.invoice', ['id' => encode($order['id']), 'download' => true]), 'color' => 'primary'])
Download Invoice
@endcomponent

Thank you for choosing us for your purchase!
{{ config('app.name') }}
@endcomponent
