<div class="modal-header">
    <h5 class="modal-title">Create new Address</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        &times;
    </button>
</div>
<div class="modal-body">
    <form action="{{ route("admin.customer.address.store") }}" method="POST" class="ajax-form">
        @csrf
        <input type="hidden" name="user_id" value="{{ $model->id }}">
        <div class="row">

            <div class="col-md-4 form-group mb-3">
                <label for="is_default">Default Address</label>
                <select name="is_default" id="is_default" class="form-control select">
                    <option value="1">Yes</option>
                    <option value="0" selected>No</option>
                </select>
            </div>

            <div class="col-md-4 form-group mb-3">
                <label for="company_name">Company </label>
                <input type="text" name="company_name" id="company_name" class="form-control">
            </div>

            <div class="col-md-6 mb-3 form-group">
                <label for="first_name">First Name <span class="text-danger">*</span></label>
                <input type="text" name="first_name" id="first_name" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3 form-group">
                <label for="last_name">Last Name <span class="text-danger">*</span></label>
                <input type="text" name="last_name" id="last_name" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3 form-group">
                <label for="address">Address <span class="text-danger">*</span></label>
                <input type="text" name="address" id="address" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3 form-group">
                <label for="address_line_2">Address Line 2</label>
                <input type="text" name="address_line_2" id="address_line_2" class="form-control">
            </div>

            <div class="col-md-6 mb-3 form-group">
                <label for="area">Area <span class="text-danger">*</span></label>
                <input type="text" name="area" id="area" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3 form-group">
                <label for="postcode">Postcode <span class="text-danger">*</span></label>
                <input type="text" name="postcode" id="postcode" class="form-control" required>
            </div>

            <div class="col-md-4 mb-3 form-group">
                <label for="zone_id">Zone <span class="text-danger">*</span></label>
                <select name="zone_id" id="zone_id" class="form-control select" required data-placeholder="Select one" data-parsley-errors-container="#zone_id_error">
                    <option value="">Select one</option>
                    @foreach ($zones as $zone)
                        <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                    @endforeach
                </select>
                <span id="zone_id_error"></span>
            </div>

            <div class="col-md-4 form-group mb-3">
                <label for="country_id">Country <span class="text-danger">*</span></label>
                <select name="country_id" id="country_id" class="form-control select" data-parsley-errors-container="#country_id_error" required data-placeholder="Select Country">
                    <option value="">Select Country</option>
                </select>
                <span id="country_id_error"></span>
            </div>

            <div class="col-md-4 form-group mb-3">
                <label for="city_id">City <span class="text-danger">*</span></label>
                <select name="city_id" id="city_id" class="form-control select" data-parsley-errors-container="#city_id_error" required data-placeholder="Select City">
                    <option value="">Select City</option>
                </select>
                <span id="city_id_error"></span>
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