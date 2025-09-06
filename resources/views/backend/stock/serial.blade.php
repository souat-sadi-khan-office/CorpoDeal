<form action="{{ route('admin.supplier.product.serial-update',$stock->id) }}" method="POST" class="{{ request()->has('category') && request()->get('category') ? 'custom-form' : 'ajax-form' }}">
    @csrf
    <div class="modal-header">
        <h5 class="h6 mb-0">
            <b>Update Product Serials</b>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
        <div class="row" style="max-height:300px;overflow-y:scroll;">
            @for($i=0;$i<$stock->quantity;$i++)
                <div class="col-md-6 form-group mb-3">
                    <div class="d-flex align-items-center">
                        <label for="serial[{{$i}}]" class="me-2 mb-0">
                            <strong>{{$i+1}}</strong>
                        </label>
                        <input type="text" name="serial[{{$i}}]" id="serial[{{$i}}]" value="{{ count($stock->serials) && isset($stock->serials[$i]) ? $stock->serials[$i]->serial : '' }}"
                               class="form-control py-2">
                    </div>
                </div>
            @endfor
        </div>
    </div>
    <div class="modal-footer text-end">
        <button type="button" class="btn btn-sm btn-outline-danger" type="button" data-bs-dismiss="modal" aria-label="Close">
            <i class="bi bi-x"></i>
            Close
        </button>

        <button class="btn btn-sm btn-dark" type="submit" id="submit">
            <i class="bi bi-send"></i> Update
        </button>

        <button class="btn btn-sm btn-outline-dark" type="button" id="submitting" style="display: none;">
            <i class="bi bi-spinner bi-spin"></i> Processing
        </button>
    </div>
</form>