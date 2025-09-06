<form action="{{ route("admin.customer.phone.update", $model->id) }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')
    <div class="modal-header">
        <h5 class="h6 mb-0">
            <strong>Update Phone Number Information</strong>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">

            <div class="col-md-12 mb-3 form-group">
                <label for="phone_number">Phone Number <span class="text-danger">*</span></label>
                <input type="text" name="phone_number" id="phone_number" class="form-control" required value="{{ $model->phone_number }}">
            </div>

            <div class="col-md-6 form-group">
                <label for="is_verified">Verified Phone Number <span class="text-danger">*</span></label>
                <select name="is_verified" id="is_verified" class="form-control select" data-minimum-results-for-search="Infinity">
                    <option {{ $model->is_verified == 1 ? 'selected' : '' }} value="1">Yes</option>
                    <option {{ $model->is_verified == 0 ? 'selected' : '' }} value="0">No</option>
                </select>
            </div>

            <div class="col-md-6 form-group">
                <label for="is_default">Default Phone Number <span class="text-danger">*</span></label>
                <select name="is_default" id="is_default" class="form-control select" data-minimum-results-for-search="Infinity">
                    <option {{ $model->is_default == 1 ? 'selected' : '' }} value="1">Yes</option>
                    <option {{ $model->is_default == 0 ? 'selected' : '' }} value="0">No</option>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer text-end">
        <button type="button" class="btn btn-sm btn-outline-danger" type="button" data-bs-dismiss="modal" aria-label="Close">
            <i class="bi bi-x"></i>
            Close
        </button>
        <button class="btn btn-sm btn-dark" type="submit" id="submit">
            <i class="bi bi-send"></i>
            Update
        </button>
        <button class="btn btn-sm btn-outline-dark" style="display: none;" id="submitting" type="button" disabled>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Loading ...
        </button>
    </div>
</form>