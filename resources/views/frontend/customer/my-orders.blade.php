@extends('frontend.layouts.app', ['title' => 'My Orders | '. get_settings('system_name')])

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
    <div class="section bg_gray">
        <div class="custom-container">
            <div class="row">
                @include('frontend.customer.partials.sidebar')
                <div class="col-lg-9 col-md-8 dashboard_content">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            @include('frontend.customer.partials.header')
                        </div>

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h1 class="h5">Order History</h1>
                                </div>
                                <div class="card-body">
                                    @if (!isset($models) || (is_array($models) && count($models) <= 0))
                                        <p>You have not made any previous orders!</p>
                                    @else
                                        <div class="col-md-12 table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Invoice ID</th>
                                                        <th>Date</th>
                                                        <th>Payment</th>
                                                        <th>Status</th>
                                                        <th>Total</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($models as $model)
                                                        <tr>
                                                            <td>{{ strtoupper($model['unique_id']) }}</td>
                                                            <td>
                                                                {{ get_system_date($model['created_at']) }}
                                                                {{ get_system_time($model['created_at']) }}
                                                            </td>
                                                            <td>{{ str_replace('_', ' ', ucfirst($model['payment_status'])) }}-{{ ucfirst($model['gateway_name']) }}
                                                            </td>
                                                            <td>{{ ucfirst($model['status']) }}</td>
                                                            <td>{{ $model['currency_symbol'] }}{{ round($model['amount'], 2) }}
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('account.my_order_details', encode($model['id'])) }}" class="btn btn-fill-out btn-sm">
                                                                    View
                                                                </a>
                                                                <button type="button" id="order_{{ (str_replace('#', '', $model['unique_id'])) }}" data-id="{{ (str_replace('#', '', $model['unique_id'])) }}" class="btn re-order btn-dark btn-sm">
                                                                    Re-Order
                                                                </a>
                                                                <button type="button" disabled style="display: none;" id="order_processing_{{ (str_replace('#', '', $model['unique_id'])) }}" data-id="{{ (str_replace('#', '', $model['unique_id'])) }}" class="btn btn-dark btn-sm">
                                                                    <i class="fas fa-spin fa-spinner"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $(document).on('click', '.re-order', function() {
                let orderId = $(this).data('id');
                $('#order_'+orderId).hide();
                $('#order_processing_'+orderId).show();

                $.ajax({
                    url: '/order/re-order',
                    type: 'POST',
                    data: {
                        order_id: orderId
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        $('#order_'+orderId).show();
                        $('#order_processing_'+orderId).hide();

                        if(!data.status) {
                            toastr.error(data.message);
                        } else {
                            toastr.success(data.message);

                            setTimeout(() => {
                                window.location.href="/cart";
                            }, 2000);
                        }
                    }
                });
            })
        })
    </script>
@endpush