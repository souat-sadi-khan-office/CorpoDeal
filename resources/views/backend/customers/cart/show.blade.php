<div class="modal-header">
    <h5 class="modal-title">Full view Cart Details Information</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        &times;
    </button>
</div>
<div class="modal-body">
    <div class="row">

        <div class="col-md-12 table-responsive">
            <table class="table table-bordered">
                <thead>
                    <th>Date</th>
                    <th>Product</th>
                    <th>Quantity</th>
                </thead>
                <tbody>
                    @if (count($models) > 0)
                        @foreach ($models as $model)
                            <tr>
                                <td>{{ get_system_date($model->created_at) }} <br> {{ get_system_time($model->created_at) }}</td>
                                <td>
                                    @if ($model->product)
                                        <a target="_blank" href="{{ route('slug.handle', $model->product->slug) }}">
                                            <div class="row">
                                                <div class="col-auto">
                                                    {!! App\CPU\Images::show($model->product->thumb_image) !!}
                                                </div>
                                                <div class="col">
                                                    {{ $model->product->name }}
                                                </div>
                                            </div>
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    {{ $model->quantity }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center">
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