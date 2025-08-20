<div class="row">
    <div class="col-md-12 table-responsive">
        <div class="row">
            <div class="col-md-12">
                <h4 class="h6 mb-3">
                    <b>User Product Queries Information</b>
                </h4>
            </div>
        </div>
        
        <table class="table table-bordered" id="user-address-table">
            <thead>
                <th width="5%">#</th>
                <th>Date</th>
                <th>Product</th>
                <th>Question</th>
                <th width="15%">Action</th>
            </thead>
            <tbody>
                @if ($model->questions && count($model->questions) > 0)
                    @php($questionCounter = 1)
                    @foreach ($model->questions()->orderBy('id', 'DESC')->get()  as $question)
                        <tr>
                            <td>{{ $questionCounter }}</td>
                            <td>{{ get_system_date($question->updated_at) }} {{ get_system_time($question->updated_at) }}</td>
                            <td>
                                @if ($question->product)
                                    <a href="{{ route('slug.handle', $question->product->slug) }}">
                                        <div class="row">
                                            <div class="col-auto">
                                                {!! App\CPU\Images::show($question->product->thumb_image) !!}
                                            </div>
                                            <div class="col">
                                                {{ $question->product->name }}
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            </td>
                            <td class="product-stock-status">
                                {{ $question->message }}
                            </td>
                            <td>
                                <a href="javascript:;" id="content_management" data-url="{{ route('admin.customer.question.answer', $question->id) }}" class="btn btn-sm btn-outline-secondary" style="padding: 3px 8px;font-size:12px;">
                                    Reply
                                </a>
                            
                                <a href="javascript:;" id="delete_item" data-id="{{ $question->id }}" data-url="{{ route('admin.customer.question.destroy', $question->id) }}" class="btn btn-sm btn-outline-secondary" style="padding: 3px 8px;font-size:12px;">
                                    Remove
                                </a>
                            </td>
                        </tr>
                        @php($questionCounter++)
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