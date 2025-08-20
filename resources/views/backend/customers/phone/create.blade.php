<div class="modal-header">
    <h5 class="modal-title">Add new Phone</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        &times;
    </button>
</div>
<div class="modal-body">
    <form action="{{ route("admin.customer.phone.store") }}" method="POST" class="ajax-form">
        @csrf
        <input type="hidden" name="user_id" value="{{ $model->id }}">
        <div class="row">

            <div class="col-md-12 mb-3 form-group">
                <label for="phone_number">Phone Number <span class="text-danger">*</span></label>
                <input type="text" name="phone_number" id="phone_number" class="form-control" required>
            </div>

            <div class="col-md-12 form-group mb-3">
                <label for="is_verified">Verified Phone Number <span class="text-danger">*</span></label>
                <select name="is_verified" id="is_verified" class="form-control select">
                    <option value="1">Yes</option>
                    <option value="0" selected>No</option>
                </select>
            </div>

            <div class="col-md-12 form-group mb-3">
                <label for="is_default">Default Phone Number <span class="text-danger">*</span></label>
                <select name="is_default" id="is_default" class="form-control select">
                    <option value="1">Yes</option>
                    <option value="0" selected>No</option>
                </select>
            </div>

            <div class="col-md-12 mt-3 text-end">
                <button class="btn btn-soft-success" type="submit" id="submit">
                    <i class="bi bi-send"></i>
                    Create
                </button>
                <button class="btn btn-soft-warning" style="display: none;" id="submitting" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
            </div>
        </div>
    </form>
</div>