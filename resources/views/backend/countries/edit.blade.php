<div class="modal-header">
    <h5 class="modal-title">Update Country Information</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        &times;
    </button>
</div>
<div class="modal-body">
    <form action="{{ route("admin.country.update", $model->id) }}" enctype="multipart/form-data" method="POST" class="ajax-form">
        @method('PATCH')
        <div class="row">

            <div class="col-md-12 form-group">
                <label for="zone_id">Zone <span class="text-danger">*</span></label>
                <select name="zone_id" id="zone_id" class="form-control select" data-placeholder="Select Zone" data-parsley-error-containers="zone_error">
                    <option value="">Select One</option>
                    @foreach ($zones as $zone)
                        <option {{ $model->zone_id == $zone->id ? 'selected' : '' }} value="{{ $zone->id }}">{{ $zone->name }}</option>
                    @endforeach
                </select>
                <span id="zone_error"></span>
            </div>

            <div class="col-md-12 mt-3 form-group">
                <label for="image">Image <span class="text-danger">*</span></label>
                <input type="file" name="image" id="image" class="form-control">
            </div>

            <div class="col-md-12 mt-3 form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" required value="{{ $model->name }}">
            </div>

            <div class="col-md-12 form-group mt-3">
                <label for="cost">Cost <span class="text-danger">*</span></label>
                <input type="text" value="{{ covert_to_defalut_currency($model->cost) }}" name="cost" id="cost" class="form-control" required value="0">
            </div>

            <div class="col-md-12 mt-3 form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-control select" required>
                    <option {{ $model->status == 1 ? 'selected' : '' }} value="1">Active</option>
                    <option {{ $model->status == 0 ? 'selected' : '' }} value="0">Inactive</option>
                </select>
            </div>

            <div class="col-md-12 mt-3 text-end">
                <button class="btn btn-soft-success" type="submit" id="submit">
                    <i class="bi bi-send"></i>
                    Update
                </button>
                <button class="btn btn-soft-warning" style="display: none;" id="submitting" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
            </div>
        </div>
    </form>
</div>