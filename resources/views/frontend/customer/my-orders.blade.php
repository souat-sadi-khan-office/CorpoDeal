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
    <div class="section bg_gray pt-4 mm">
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
                                    @if (!isset($models) || count($models) < 1)
                                        <p>You have not made any previous orders!</p>
                                    @else
                                        <div class="col-md-12 table-responsive">
                                            <table class="table">
                                                <thead>

                                                <tr>
                                                    <th>Invoice ID</th>
                                                    <th>Date</th>
                                                    <th>Order Status</th>
                                                    <th>Total</th>
                                                    <th>Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($models as $model)
                                                    <tr>
                                                        <td>
                                                            {{ strtoupper($model['unique_id']) }} <br>
                                                            <small>
                                                                @if($model['payment_status'] === 'Paid'||$model['payment_status'] === 'VALID' ||$model['payment_status'] === 'VALIDATED')
                                                                    <span class="badge bg-success">{{ str_replace('_', ' ', ucfirst($model['payment_status'])) }}</span>
                                                                @elseif($model['payment_status'] === 'Unpaid')
                                                                    <span class="badge bg-secondary">Unpaid</span>
                                                                @else
                                                                    <span
                                                                        class="badge bg-danger"> {{ str_replace('_', ' ', ucfirst($model['payment_status'])) }}</span>

                                                                @endif
                                                                -
                                                                {{ substr(ucfirst($model['gateway_name']), 0, 15) }}
                                                            </small>
                                                        </td>
                                                        <td>
                                                            {{ get_system_date($model['created_at']) }} <br>
                                                            {{ get_system_time($model['created_at']) }}
                                                        </td>

                                                        @php
                                                            $statusStyles = [
                                                                'pending' => ['label' => 'Pending', 'bg' => '#6c757d', 'text' => '#fff'],
                                                                'packaging' => ['label' => 'Packaging', 'bg' => '#17a2b8', 'text' => '#fff'],
                                                                'shipping' => ['label' => 'Shipping', 'bg' => '#007bff', 'text' => '#fff'],
                                                                'out_of_delivery' => ['label' => 'Out for Delivery', 'bg' => '#ffc107', 'text' => '#000'],
                                                                'delivered' => ['label' => 'Delivered', 'bg' => '#28a745', 'text' => '#fff'],
                                                                'returned' => ['label' => 'Returned', 'bg' => '#343a40', 'text' => '#fff'],
                                                                'failed' => ['label' => 'Failed', 'bg' => '#dc3545', 'text' => '#fff'],
                                                            ];

                                                            $status = $model['status'];
                                                            $label = $statusStyles[$status]['label'] ?? ucfirst($status);
                                                            $bg = $statusStyles[$status]['bg'] ?? '#6c757d';
                                                            $text = $statusStyles[$status]['text'] ?? '#fff';
                                                        @endphp

                                                        <td>
                                                                <span style="
                                                                    background-color: {{ $bg }};
                                                                    color: {{ $text }};
                                                                    padding: 4px 5px;
                                                                    border-radius: 6px;
                                                                    font-size: 12px;
                                                                    font-weight: 400;
                                                                    display: inline-block;
                                                                    min-width: 90px;
                                                                    text-align: center;
                                                                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                                                                    ">
                                                                    {{ $label }}
                                                                </span>
                                                        </td>

                                                        <td>{{ $model['currency_symbol'] }}{{ round($model['amount'], 2) }}
                                                        </td>
                                                        <td>
                                                            <div class="btn-group" role="group"
                                                                 aria-label="Basic example">
                                                                <a href="{{ route('account.my_order_details', encode($model['id'])) }}"
                                                                   class="btn btn-outline-dark btn-sm">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="/order/track/{{ str_replace('#', '', $model['unique_id']) }}"
                                                                   class="btn btn-outline-dark btn-sm" target="_blank">
                                                                    Track
                                                                </a>

                                                                @if($model['status']==='failed' && $model['payment_status']!='Paid')
                                                                    <a href="{{route('account.repay',str_replace('#', '', $model['unique_id']))}}"
                                                                       class="btn btn-outline-dark btn-sm">
                                                                        Re-Pay
                                                                    </a>
                                                                @else
                                                                    <a href="javascript:;"
                                                                       id="order_{{ (str_replace('#', '', $model['unique_id'])) }}"
                                                                       data-id="{{ (str_replace('#', '', $model['unique_id'])) }}"
                                                                       class="btn re-order btn-outline-dark btn-sm">
                                                                        Re-Order
                                                                    </a>

                                                                @endif
                                                                <button type="button" disabled style="display: none;"
                                                                        id="order_processing_{{ (str_replace('#', '', $model['unique_id'])) }}"
                                                                        data-id="{{ (str_replace('#', '', $model['unique_id'])) }}"
                                                                        class="btn btn-outline-dark btn-sm">
                                                                    <i class="fas fa-spin fa-spinner"></i>
                                                                </button>
                                                            </div>

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
        $(function () {
            $(document).on('click', '.re-order', function () {
                let orderId = $(this).data('id');
                $('#order_' + orderId).hide();
                $('#order_processing_' + orderId).show();

                $.ajax({
                    url: '/order/re-order',
                    type: 'POST',
                    data: {
                        order_id: orderId
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        $('#order_' + orderId).show();
                        $('#order_processing_' + orderId).hide();

                        if (!data.status) {
                            toastr.error(data.message);
                        } else {
                            toastr.success(data.message);

                            setTimeout(() => {
                                window.location.href = "/cart";
                            }, 2000);
                        }
                    }
                });
            })
        })
    </script>
@endpush
