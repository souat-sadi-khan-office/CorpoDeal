<div class="row">
    <div class="col-md-12 table-responsive">
        <div class="row">
            <div class="col-md-12">
                <h4 class="h6 mb-3">
                    <b>User Current Cart Items Information</b>
                </h4>
            </div>
        </div>
        
        <table class="table table-bordered" id="user-address-table">
            <thead>
                <th width="5%">#</th>
                <th>Date</th>
                <th>Currency</th>
                <th>Total Quantity</th>
                <th>Remove</th>
            </thead>
            <tbody>
                @if ($model->carts && count($model->carts) > 0)
                    @php($cartCounter = 1)
                    @foreach ($model->carts()->orderBy('id', 'DESC')->get()  as $cart)
                        <tr>
                            <td>{{ $cartCounter }}</td>
                            <td>{{ get_system_date($cart->updated_at) }} {{ get_system_time($cart->updated_at) }}</td>
                            <td>
                                @if ($cart->currency)
                                    {{ $cart->currency->name }}
                                @endif
                            </td>
                            <td class="product-stock-status">
                                {{ $cart->total_quantity }}
                            </td>
                            <td>
                                <a id="content_management" href="javascript:;" data-url="{{ route('admin.customer.cart.show', $cart->id) }}" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="Top" title="View Cart Details">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a id="delete_item"
                                    data-id="{{ $cart->id }}"
                                    data-url="{{ route('admin.customer.cart.destroy', $cart->id) }}"
                                    class="btn btn-sm btn-outline-secondary remove-column-{{ $cart->id }}"
                                    href="javascript:;">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @php($cartCounter++)
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">
                            {!! App\CPU\Images::show('pictures/none.gif') !!} <br>
                            <b>Nothing to show</b>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>