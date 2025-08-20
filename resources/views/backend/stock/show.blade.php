<div class="modal-header">
    <h5 class="modal-title">Stock Purchase Information</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        &times;
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12 mb-3 table-responsive">
            <table class="table table-bordered table-hover">
                <tr>
                    <td colspan="2" width="20%">Product</td>
                    <td colspan="2">
                        <div class="row">
                            <div class="col-auto">
                                {!! App\CPU\Images::show($model->product->thumb_image) !!}
                            </div>
                            <div class="col">
                                {{ $model->product->name }}
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="20%">Created By</td>
                    <td>{{ $model->admin->name }}</td>
                    <td width="20%">Created At</td>
                    <td>{{ get_system_date($model->created_at) }} {{ get_system_time($model->created_at) }}</td>
                </tr>
                <tr>
                    <td width="20%">Currency</td>
                    <td>{{ $model->currency ? $model->currency->name : '' }}</td>
                    <td width="20%">SKU</td>
                    <td>{{ $model->sku }}</td>
                </tr>
                <tr>
                    <td width="20%">Quantity</td>
                    <td>{{ $model->quantity }}</td>
                    <td width="20%">Unit Price</td>
                    <td>{{ format_price($model->unit_price) }}</td>
                </tr>
                <tr>
                    <td width="20%">Purchase Unit Price</td>
                    <td>{{ format_price($model->purchase_unit_price) }}</td>
                    <td width="20%">Purchase Total Price</td>
                    <td>{{ format_price($model->purchase_total_price) }}</td>
                </tr>
                <tr>
                    <td width="20%">Is Sellable</td>
                    <td>
                        @if ($model->is_sellable == 1)
                            <span class="badge bg-success">Yes</span>
                        @else   
                            <span class="badge bg-danger">No</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <div class="col-md-12 mb-3 table-reponsive">
            <h4><b>Stock Details</b></h4>
            @if ($model->product->stock_types == 'globally')
                <table class="table table-bordered table-striped">
                    <thead>
                        <th>Quantity</th>
                        <th>Number of Sale</th>
                        <th>In stock</th>
                    </thead>
                    <tbody>
                        @foreach ($model->stocks as $stock)
                            <tr>
                                <td>{{ $stock->stock }}</td>
                                <td>{{ $stock->number_of_sale }}</td>
                                <td>
                                    @if ($stock->in_stock)
                                        <span class="badge bg-success">In Stock</span>
                                    @else  
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            @if ($model->product->stock_types == 'zone_wise')
                <table class="table table-bordered table-striped">
                    <thead>
                        <th>Zone</th>
                        <th>Quantity</th>
                        <th>Number of Sale</th>
                        <th>In stock</th>
                    </thead>
                    <tbody>
                        @foreach ($model->stocks as $stock)
                            <tr>
                                <td>{{ $stock->zone->name }}</td>
                                <td>{{ $stock->stock }}</td>
                                <td>{{ $stock->number_of_sale }}</td>
                                <td>
                                    @if ($stock->in_stock)
                                        <span class="badge bg-success">In Stock</span>
                                    @else  
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            @if ($model->product->stock_types == 'country_wise')
                <table class="table table-bordered table-striped">
                    <thead>
                        <th>Country</th>
                        <th>Quantity</th>
                        <th>Number of Sale</th>
                        <th>In stock</th>
                    </thead>
                    <tbody>
                        @foreach ($model->stocks as $stock)
                            <tr>
                                <td>{{ $stock->country->name }}</td>
                                <td>{{ $stock->stock }}</td>
                                <td>{{ $stock->number_of_sale }}</td>
                                <td>
                                    @if ($stock->in_stock)
                                        <span class="badge bg-success">In Stock</span>
                                    @else  
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            @if ($model->product->stock_types == 'city_wise')
                <table class="table table-bordered table-striped">
                    <thead>
                        <th>City</th>
                        <th>Quantity</th>
                        <th>Number of Sale</th>
                        <th>In stock</th>
                    </thead>
                    <tbody>
                        @foreach ($model->stocks as $stock)
                            <tr>
                                <td>{{ $stock->city->name }}</td>
                                <td>{{ $stock->stock }}</td>
                                <td>{{ $stock->number_of_sale }}</td>
                                <td>
                                    @if ($stock->in_stock)
                                        <span class="badge bg-success">In Stock</span>
                                    @else  
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            
        </div>
    </div>
</div>