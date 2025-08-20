<div class="row">
    <div class="col-md-12 table-responsive">
        <div class="row">
            <div class="col-md-12">
                <h4 class="h6 mb-3">
                    <b>User Product Review History</b>
                </h4>
            </div>
        </div>
        
        <table class="table table-bordered" id="user-address-table">
            <thead>
                <th width="5%">#</th>
                <th>Date</th>
                <th>Product</th>
                <th>Rating</th>
                <th>Review</th>
                <th>Action</th>
            </thead>
            <tbody>
                @if ($model->ratings && count($model->ratings) > 0)
                    @php($ratingCounter = 1)
                    @foreach ($model->ratings()->orderBy('id', 'DESC')->get()  as $rating)
                        <tr>
                            <td>{{ $ratingCounter }}</td>
                            <td>{{ get_system_date($rating->created_at) }} {{ get_system_time($rating->created_at) }}</td>
                            <td>
                                @if ($rating->product)
                                    <a target="_blank" href="{{ route('slug.handle', $rating->product->slug) }}">
                                        <div class="row">
                                            <div class="col-auto">
                                                {!! App\CPU\Images::show($rating->product->thumb_image) !!}
                                            </div>
                                            <div class="col">
                                                {{ $rating->product->name }}
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            </td>
                            <td>{{ $rating->rating }}</td>
                            <td>
                                {{ $rating->review }}
                            </td>
                            <td>
                                <a href="javascript:;" id="delete_item" data-id ="{{ $model->id }}" data-url="{{ route('admin.customer.rating.destroy',$rating->id) }}" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        
                        @php($ratingCounter++)
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