<div class="row">
    <div class="col-md-12 table-responsive">
        <div class="row">
            <div class="col-md-9">
                <h4 class="h6 mb-3">
                    <b>User Point History</b> 
                    <span class="badge bg-success">{{ $model->points }}</span> 
                </h4>
            </div>
            <div class="col-md-3 text-end">
                <a id="content_management" href="javascript:;" data-url="{{ route('admin.customer.show', $model->id) }}" class="btn btn-sm btn-outline-secondary" style="padding: 3px 8px; font-size: 12px;">
                    <i class="bi bi-gift"></i>
                    Send Gift Points
                </a>
            </div>
        </div>
        
        <table class="table table-bordered" id="user-address-table">
            <thead>
                <th width="5%">#</th>
                <th>Date</th>
                <th>Product/Reason</th>
                <th>Points</th>
                <th width="5%">Quantity</th>
                <th width="5%">Method</th>
            </thead>
            <tbody>
                @if ($model->point_history && count($model->point_history) > 0)
                    @php($pointHistoryCounter = 1)
                    @foreach ($model->point_history()->orderBy('id', 'DESC')->get()  as $point)
                        <tr>
                            <td>{{ $pointHistoryCounter }}</td>
                            <td>{{ get_system_date($point->created_at) }} {{ get_system_time($point->created_at) }}</td>
                            <td>
                                @if ($point->product)
                                    <a href="{{ route('slug.handle', $point->product->slug) }}" target="_blank">
                                        <div class="row">
                                            <div class="col-auto">
                                                <img width="50" height="50" src="{{ asset($point->product->thumb_image) }}" alt="{{ $point->product->name }}">
                                            </div>
                                            <div class="col">
                                                {{ $point->product->name }}
                                            </div>
                                        </div>
                                    </a>
                                @else   
                                    {{ $point->notes }}
                                @endif
                            </td>
                            <td>
                                {{ $point->points }}
                            </td>
                            <td>
                                {{ $point->quantity }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $point->method == 'plus' ? 'success' : 'danger'  }}">{{ $point->method == 'plus' ? 'Added' : 'Substract'  }}</span>
                            </td>
                        </tr>

                        @php($pointHistoryCounter++)
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