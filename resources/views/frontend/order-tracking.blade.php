@extends('frontend.layouts.app', ['title' => "" ])
@push('styles')
<link rel="stylesheet" href="{{asset('frontend/assets/css/order-tracking.min.css')}}">
@endpush
@push('breadcrumb')
    <div class="breadcrumb_section page-title-mini">
        <div class="custom-container">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">
                                <i class="linearicons-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Order Confirmation
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endpush
@section('content')
<div class="breadcrumb_section border border-top page-title-mini">
    <div class="custom-container">
        <div class="row align-items-center">
            <div class="col-md-12 text-center">
                <h1 class="h3">Track Your Package</h1>
                <h6>
                    <b>Order ID</b>: {{ strtoupper($details['unique_id']) }}
                </h6>
                <h6>
                    <b>Estimated Delivery</b>: 19 July, 2024
                </h6>
                @switch($order->status)
                    @case("pending")
                        <div >
                            <img width="150" height="150" title="Order Pending Picture" src="{{ asset('pictures/order/order-pending.gif') }}" alt="Order Pending">
                            <h4 class="primary-color"><b>Order is Pending</b></h4>
                        </div>
                        @break
                    @case("packaging")
                        <div>
                            <img width="150" height="150" title="Order Packaging Picture" src="{{ asset('pictures/order/order-packaging.gif') }}" alt="Order in Packaging">
                            <h4 class="primary-color"><b>Order in Packaging</b></h4>
                        </div>
                        @break
                    @case("shipping")
                        <div>
                            <img width="150" height="150" title="Order in Shipping Picture" src="{{ asset('pictures/order/order-in-shipping.gif') }}" alt="Order in Shipping">
                            <h4 class="primary-color"><b>Order in Shipping</b></h4>
                        </div>
                        @break
                    @case("out_of_delivery")
                        <div>
                            <img width="150" height="150" title="Order in Delivery Picture" src="{{ asset('pictures/order/order-in-delivery.gif') }}" alt="Order in Delivery">
                            <h4 class="primary-color"><b>Order in Delivery</b></h4>
                        </div>
                        @break
                    @case("delivered")
                        <div>
                            <img width="150" height="150" title="Order Delivered Picture" src="{{ asset('pictures/order/delivery-completed.gif') }}" alt="Order Delivered">
                            <h4 class="primary-color"><b>Order Delivered</b></h4>
                        </div>
                        @break
                    @case("returned")
                        <div>
                            <img width="150" height="150" title="Order Return Picture" src="{{ asset('pictures/order/delivery-returned.gif') }}" alt="Order Return">
                            <h4 class="primary-color"><b>Order is Returned</b></h4>
                        </div>
                        @break
                    @default
                        <div>
                            <img width="150" height="150" title="Order Cancelled Picture" src="{{ asset('pictures/order/order-cancelled.gif') }}" alt="Order Cancelled">
                            <h4 class="primary-color"><b>Order is Cancelled</b></h4>
                        </div>
                @endswitch

            </div>
        </div>
    </div>
</div>
<div class="main_content bg_gray py-5">

    <div class="custom-container">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-white pb-0">
                                <h4 class="h6 card-title">
                                    Shipping Information
                                </h4>
                            </div>
                            <div class="card-body">
                                <address>
                                    <strong>{{ $details['user_name'] }}</strong><br>
                                    {!! add_line_breaks($details['billing_address']) !!} <br>
                                    Phone: {{ $details['phone'] }}<br>
                                    @if ($details['user_company'])
                                        Company: {{ ucfirst($details['user_company']) }} <br>
                                    @endif
                                    Email: {{ $details['email'] }}
                                </address>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header bg-white pb-0">
                                <h4 class="h6 card-title">
                                    Item Ordered
                                </h4>
                            </div>
                            <div class="card-body">
                                @foreach ($details['details'] as $d)
                                    @php
                                        $product = App\Models\Product::select('thumb_image')->find($d->id)
                                    @endphp
                                    <div class="d-flex justify-content-between align-items-center product-details my-2">
                                        <div class="d-flex flex-row product-name-image">
                                            <img class="rounded" title="{{ $d->name }} Photo" alt="{{ $d->name }}" src="{{ asset($product->thumb_image) }}" width="80">
                                            <div class="d-flex flex-column justify-content-between mx-2">

                                                <div>
                                                    <span class="d-block font-weight-bold p-name product_title">
                                                        <a href="{{route('slug.handle',$d->slug)}}">{{ $d->name }}</a>
                                                    </span>
                                                </div>
                                                <span class="fs-12">Qty: {{ $d->qty }}</span>
                                                <span class="fs-12">Unit Price: {{ $d->unit_price }}</span>
                                            </div>
                                        </div>
                                        <div class="product-price">
                                            <h6>{{ $d->total_price }}</h6>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header bg-white pb-0">
                                <h4 class="h6 card-title">
                                    Billing Information
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="billing">
                                    @if(isset($details['premium_user_discount_amount']) && $details['premium_user_discount_amount'])
                                        <div class="d-flex justify-content-between mt-1">
                                            <span class="font-weight-bold">Premium User Discount</span>
                                            <span class="font-weight-bold text-success">-{{ $details['premium_user_discount_amount'] }}</span>
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-between">
                                        <span>Subtotal</span>
                                        <span class="font-weight-bold">{{ $details['order_amount'] }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <span>Shipping fee</span>
                                        <span class="font-weight-bold">{{$details['shipping_charge']}}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <span>Tax</span>
                                        <span class="font-weight-bold">{{ $details['tax_amount'] }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <span class="text-success">Discount</span>
                                        <span class="font-weight-bold text-success">{{ $details['discount_amount'] }}</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between mt-1">
                                        <span class="font-weight-bold">Total</span>
                                        <span class="font-weight-bold text-success">{{ $details['final_amount'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-white">
                        <h4 class="h6 card-title mb-0">
                            Tracking History
                        </h4>
                    </div>
                    <div class="card-body">

                        @if ($order->status != 'failed')
                            <div id="tracking-pre"></div>
                            <div id="tracking">
                                <div class="tracking-list">
                                    <div class="tracking-item">
                                        <div class="tracking-icon status-intransit {{ $order->status == 'pending' ? 'status-current blinker' : '' }}">
                                            <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                                <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                                            </svg>
                                        </div>

                                        <div class="tracking-date">
                                            <img width="35" title="Order Placed Photo" src="{{ asset('pictures/svg/order-placed.png') }}" class="img-responsive" alt="order-placed" />
                                        </div>
                                        <div class="tracking-content {{ $order->statusHistory && $order->statusHistory->pending_time ? '' : 'text-muted' }}">
                                            Order Placed
                                            @if ($order->statusHistory && $order->statusHistory->pending_time)
                                                <span>
                                                    {{ @get_system_date($order->statusHistory->pending_time) }} {{ @get_system_time($order->statusHistory->pending_time) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="{{ in_array('packaging', $statusArray) ? 'tracking-item' : 'tracking-item-pending' }}">
                                        <div class="tracking-icon status-intransit">
                                            <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                                <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                                            </svg>
                                        </div>
                                        <div class="tracking-date">
                                            <img src="{{ asset('pictures/svg/order-confirm.png') }}" width="35" title="Order Placed Picture" class="img-responsive" alt="order-placed" />
                                        </div>
                                        <div class="tracking-content {{ $order->statusHistory && $order->statusHistory->packaging_time ? '' : 'text-muted' }}">
                                            Order Confirmed

                                            @if ($order->statusHistory && $order->statusHistory->packaging_time)
                                                <span>
                                                    {{ @get_system_date($order->statusHistory->packaging_time) }} {{ @get_system_time($order->statusHistory->packaging_time) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="{{ in_array('packaging', $statusArray) ? 'tracking-item' : 'tracking-item-pending' }}">
                                        <div class="tracking-icon status-intransit {{ $order->status == 'packaging' ? 'status-current blinker' : '' }}">
                                            <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                                <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                                            </svg>
                                        </div>
                                        <div class="tracking-date">
                                            <img src="{{ asset('pictures/svg/order-packaging.png') }}" class="img-responsive" alt="order-placed" width="35" title="Order Packaging Picture"/>
                                        </div>
                                        <div class="tracking-content {{ $order->statusHistory && $order->statusHistory->packaging_time ? '' : 'text-muted' }}">
                                            Packed the product

                                            @if ($order->statusHistory && $order->statusHistory->packaging_time)
                                                <span>
                                                    {{ @get_system_date($order->statusHistory->packaging_time) }} {{ @get_system_time($order->statusHistory->packaging_time) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="{{ in_array('shipping', $statusArray) ? 'tracking-item' : 'tracking-item-pending' }}">
                                        <div class="tracking-icon status-intransit {{ $order->status == 'shipping' ? 'status-current blinker' : '' }}">
                                            <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                                <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                                            </svg>
                                        </div>
                                        <div class="tracking-date">
                                            <img src="{{ asset('pictures/svg/order-in-shipping.png') }}" class="img-responsive" alt="order-in-shipping" width="35" title="Order in Shipping" />
                                        </div>
                                        <div class="tracking-content {{ $order->statusHistory && $order->statusHistory->shipping_time ? '' : 'text-muted' }}">
                                            Arrived in the warehouse

                                            @if ($order->statusHistory && $order->statusHistory->shipping_time)
                                                <span>
                                                    {{ @get_system_date($order->statusHistory->shipping_time) }} {{ @get_system_time($order->statusHistory->shipping_time) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="{{ in_array('out_of_delivery', $statusArray) ? 'tracking-item' : 'tracking-item-pending' }}">
                                        <div class="tracking-icon status-intransit">
                                            <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                                <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                                            </svg>
                                        </div>
                                        <div class="tracking-date">
                                            <img src="{{ asset('pictures/svg/order-in-delivery.svg') }}" class="img-responsive" alt="order-in-delivery" width="35" title="Order In Delivery" />
                                        </div>
                                        <div class="tracking-content {{ $order->statusHistory && $order->statusHistory->out_for_delivery_time ? '' : 'text-muted' }}">
                                            Near by Courier facility

                                            @if ($order->statusHistory && $order->statusHistory->out_for_delivery_time)
                                                <span>
                                                    {{ @get_system_date($order->statusHistory->out_for_delivery_time) }} {{ @get_system_time($order->statusHistory->out_for_delivery_time) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="{{ in_array('out_of_delivery', $statusArray) ? 'tracking-item' : 'tracking-item-pending' }}">
                                        <div class="tracking-icon status-intransit {{ $order->status == 'out_of_delivery' ? 'status-current blinker' : '' }}">
                                            <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                                <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                                            </svg>
                                        </div>
                                        <div class="tracking-date">
                                            <img src="{{ asset('pictures/svg/curier-boy.png') }}" class="img-responsive" alt="order-out-delivery" width="35" title="Order Out for Delivery"  />
                                        </div>
                                        <div class="tracking-content {{ $order->statusHistory && $order->statusHistory->out_for_delivery_time ? '' : 'text-muted' }}">
                                            Out for Delivery

                                            @if ($order->statusHistory && $order->statusHistory->out_for_delivery_time)
                                                <span>
                                                    {{ @get_system_date($order->statusHistory->out_for_delivery_time) }} {{ @get_system_time($order->statusHistory->out_for_delivery_time) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="{{ in_array('delivered', $statusArray) ? 'tracking-item' : 'tracking-item-pending' }}">
                                        <div class="tracking-icon status-intransit {{ $order->status == 'delivered' ? 'status-current blinker' : '' }}">
                                            <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                                <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                                            </svg>
                                        </div>
                                        <div class="tracking-date">
                                            <img src="{{ asset('pictures/svg/delivered.png') }}" class="img-responsive" alt="delivered" width="35" title="Order is Delivered"  />
                                        </div>
                                        <div class="tracking-content {{ $order->statusHistory && $order->statusHistory->delivered_time ? '' : 'text-muted' }}">
                                            Delivered

                                            @if ($order->statusHistory && $order->statusHistory->delivered_time)
                                                <span>{{ @get_system_date($order->statusHistory->delivered_time) }} {{ @get_system_time($order->statusHistory->delivered_time) }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if (in_array('returned', $statusArray))
                                        <div class="{{ in_array('returned', $statusArray) ? 'tracking-item' : 'tracking-item-pending' }}">
                                            <div class="tracking-icon status-intransit {{ $order->status == 'returned' ? 'status-current blinker' : '' }}">
                                                <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                                    <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                                                </svg>
                                            </div>
                                            <div class="tracking-date">
                                                <img src="{{ asset('pictures/svg/return.png') }}" class="img-responsive" alt="return" width="35" title="Order is Returned"  />
                                            </div>
                                            <div class="tracking-content {{ $order->statusHistory && $order->statusHistory->returned_time ? '' : 'text-muted' }}">
                                                Returned

                                                @if ($order->statusHistory && $order->statusHistory->returned_time)
                                                    <span>{{ @get_system_date($order->statusHistory->returned_time) }} {{ @get_system_time($order->statusHistory->returned_time) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        @else
                            <div class="col-md-12 py-4 text-center">
                                <img src="{{ asset('pictures/svg/cancelled.png') }}" alt="Cancelled" width="150" title="This order is Cancelled Picture">
                                <h4 class="h6 mt-5">This Order is Cancelled</h4>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
@push('scripts')

@endpush
