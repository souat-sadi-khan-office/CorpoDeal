<form action="{{ route("admin.city.store") }}" method="POST" class="ajax-form">
    <div class="modal-header">
        <h5 class="h6 mb-0">
            <b>Add New Shipping City</b>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            &times;
        </button>
    </div>
    <div class="modal-body">
        <div class="row">

            <div class="col-md-12 form-group">
                <label for="country_id">
                    Country 
                    <span class="text-danger">*</span>
                </label>
                <select name="country_id" id="country_id" class="form-control select" data-placeholder="Select Country" data-parsley-errors-container="#country_error" required data-minimum-results-for-search="Infinity">
                    <option value="">Select Country</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
                <span id="country_error"></span>
            </div>

            <div class="col-md-12 mt-3 form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="col-md-12 form-group mt-3">
                <label for="cost">Cost <span class="text-danger">*</span></label>
                <input type="text" name="cost" id="cost" class="form-control" required value="0">
            </div>

            <div class="col-md-12 mt-3 form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-control select" required data-minimum-results-for-search="Infinity">
                    <option selected value="1">Active</option>
                    <option value="0">Inactive</option>
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
            Create
        </button>
        <button class="btn btn-sm btn-outline-dark" style="display: none;" id="submitting" type="button" disabled>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Loading...
        </button>
    </div>
</form>