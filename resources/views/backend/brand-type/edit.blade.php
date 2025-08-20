<div class="modal-header">
    <h5 class="modal-title">Update Brand Type Information</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        &times;
    </button>
</div>
<div class="modal-body">
    <form action="{{ route("admin.brand-type.update", $model->id) }}" method="POST" class="ajax-form">
        @method('PATCH')
        <div class="row">

            <div class="col-md-12 form-group">
                <label for="brand_id">Brand <span class="text-danger">*</span></label>
                <select name="brand_id" id="brand_id" class="form-control select" data-placeholder="Select One" required data-parsley-errors-container="brand_id_error">
                    <option value="">Select One</option>
                    @foreach ($brands as $brand)
                        <option {{ $brand->id == $model->brand_id ? 'selected' : '' }} value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
                <span id="brand_id_error"></span>
            </div>

            <div class="col-md-12 mt-3 form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" required value="{{ $model->name }}">
            </div>

            <div class="col-md-12 form-group mt-3">
                <label for="icon" class="form-label">
                    Icon
                    <span class="text-danger">*</span>
                </label>
                <input type="text" name="icon" value="{{App\CPU\Helpers::extractIconClassName($model->icon)}}" class="form-control iconpicker" required>
            </div>

            <div class="col-md-6 mt-3 form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-control select" required>
                    <option {{ $model->status == 1 ? 'selected' : '' }} value="1">Active</option>
                    <option {{ $model->status == 0 ? 'selected' : '' }} value="0">Inactive</option>
                </select>
            </div>

            <div class="col-md-6 mt-3 form-group">
                <label for="is_featured">Status <span class="text-danger">*</span></label>
                <select name="is_featured" id="is_featured" class="form-control select" required>
                    <option {{ $model->is_featured == 1 ? 'selected' : '' }} value="1">Yes</option>
                    <option {{ $model->is_featured == 0 ? 'selected' : '' }} value="0">No</option>
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

<link href="{{ asset('backend/assets/css/bootstrapicons-iconpicker.css') }}" rel="stylesheet">
<script src="{{ asset('backend/assets/js/bootstrapicon-iconpicker.js') }}"></script>
<script>
    $(function() {
        $('.iconpicker').iconpicker();
    });
</script>