<div class="modal-header">
    <h5 class="modal-title">Create new Brand Type</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        &times;
    </button>
</div>
<div class="modal-body">
    <form action="{{ route("admin.brand-type.store") }}" method="POST" class="ajax-form">
        <div class="row">

            <div class="col-md-12 form-group">
                <label for="brand_id">Brand <span class="text-danger">*</span></label>
                <select name="brand_id" id="brand_id" class="form-control select" data-placeholder="Select One" required data-parsley-errors-container="#brand_id_error">
                    <option value="">Select One</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
                <span id="brand_id_error"></span>
            </div>

            <div class="col-md-12 mt-3 form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="col-md-12 form-group mt-3">
                <label for="icon" class="form-label">
                    Icon
                    <span class="text-danger">*</span>
                </label>
                <input type="text" name="icon" autocomplete="off" class="form-control iconpicker" required value="fi-rr-list">
            </div>

            <div class="col-md-6 mt-3 form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-control select" required>
                    <option selected value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            
            <div class="col-md-6 mt-3 form-group">
                <label for="is_featured">Is Featured <span class="text-danger">*</span></label>
                <select name="is_featured" id="is_featured" class="form-control select" required>
                    <option value="1">Yes</option>
                    <option selected value="0">No</option>
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

<link href="{{ asset('backend/assets/css/bootstrapicons-iconpicker.css') }}" rel="stylesheet">
<script src="{{ asset('backend/assets/js/bootstrapicon-iconpicker.js') }}"></script>
<script>
    $(function() {
        $('.iconpicker').iconpicker();
    });
</script>