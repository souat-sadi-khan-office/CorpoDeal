@php($data=$model->orders()
            ->select('id', 'unique_id', 'final_amount', 'exchange_rate', 'payment_status', 'status', 'created_at', 'payment_id', 'currency_id', 'is_cod', 'is_refund_requested')
            ->with('payment:id,currency,gateway_name', 'currency:id,code,symbol')
            ->latest()
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'unique_id' => $order->unique_id,
                    'currency' => isset($order->payment->currency) ? $order->payment->currency : @$order->currency->code,
                    'currency_symbol' => isset($order->currency->symbol) ? $order->currency->symbol : null,
                    'gateway_name' => $order->is_cod ? 'Cash on Delivery' : (isset($order->payment->gateway_name) ? $order->payment->gateway_name : null),
                    'payment_status' => $order->payment_status,
                    'status' => $order->is_refund_requested ? "Refund Requested" : $order->status,
                    'amount' => $order->final_amount * $order->exchange_rate,
                    'created_at' => $order->created_at->format('d M Y, h:i:s A')
                ];
            }))
  

<div class="row">
    <div class="col-md-12 table-responsive">
        <div class="row">
            <div class="col-md-12">
                <h4 class="h6 mb-3">
                    <b>User Order History</b>
                </h4>
            </div>
        </div>

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
                    @if (isset($data) || (is_array($data) && count($data) <= 0))
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ strtoupper($item['unique_id']) }}</td>
                                <td>
                                    {{ get_system_date($item['created_at']) }}
                                    {{ get_system_time($item['created_at']) }}
                                </td>
                                <td>{{ str_replace('_', ' ', ucfirst($item['payment_status'])) }}
                                    -{{ ucfirst($item['gateway_name']) }}
                                </td>
                                <td>{{ ucfirst($item['status']) }}</td>
                                <td>{{ $item['currency_symbol'] }}{{ round($item['amount'], 2) }}
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-outline-secondary btn-sm dropdown-toggle" href="javascript:;" role="button" style="padding: 2px 8px; font-size: 12px;"
                                        id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.order.details', $item['id']) }}"
                                                target="_blank">
                                                    <i class="bi bi-eye"></i>
                                                    View
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                href="{{route('admin.order.invoice',['id' => $item['id'], 'download' => true])}}">
                                                    <i class="bi bi-box-arrow-down"></i>
                                                    Invoice
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{route('admin.order.invoice',$item['id'])}}">
                                                    <i class="bi bi-receipt"></i> Print
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center">
                                {!! App\CPU\Images::show('pictures/none.gif') !!} <br>
                                <b>Nothing to show</b>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>