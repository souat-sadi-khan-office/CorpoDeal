<div class="modal-header">
    <h5 class="modal-title">Update Address Information</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        &times;
    </button>
</div>
<div class="modal-body">
    <form action="{{ route("admin.customer.address.update", $model->id) }}" method="POST" class="ajax-form">
        @csrf
        @method('PUT')
        <div class="row">

            <div class="col-md-3 form-group mb-3">
                <label for="is_default">Default Address</label>
                <select name="is_default" id="is_default" class="form-control select">
                    <option {{ $model->is_default == 1 ? 'selected' : '' }} value="1">Yes</option>
                    <option {{ $model->is_default == 0 ? 'selected' : '' }} value="0">No</option>
                </select>
            </div>

            <div class="col-md-3 form-group mb-3">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control select">
                    <option {{ $model->status == 1 ? 'selected' : '' }} value="1">Active</option>
                    <option {{ $model->status == 0 ? 'selected' : '' }} value="0">Inactive</option>
                </select>
            </div>

            <div class="col-md-6 form-group mb-3">
                <label for="company_name">Company </label>
                <input type="text" name="company_name" id="company_name" class="form-control" value="{{ $model->company_name }}">
            </div>

            <div class="col-md-6 mb-3 form-group">
                <label for="first_name">First Name <span class="text-danger">*</span></label>
                <input type="text" name="first_name" id="first_name" class="form-control" required value="{{ $model->first_name }}">
            </div>

            <div class="col-md-6 mb-3 form-group">
                <label for="last_name">Last Name <span class="text-danger">*</span></label>
                <input type="text" name="last_name" id="last_name" class="form-control" required value="{{ $model->last_name }}">
            </div>

            <div class="col-md-6 mb-3 form-group">
                <label for="address">Address <span class="text-danger">*</span></label>
                <input type="text" name="address" id="address" class="form-control" required value="{{ $model->address }}">
            </div>

            <div class="col-md-6 mb-3 form-group">
                <label for="address_line_2">Address Line 2</label>
                <input type="text" name="address_line_2" id="address_line_2" class="form-control" value="{{ $model->address_line_2 }}">
            </div>

            <div class="col-md-6 mb-3 form-group">
                <label for="area">Area <span class="text-danger">*</span></label>
                <input type="text" name="area" id="area" class="form-control" required value="{{ $model->area }}">
            </div>

            <div class="col-md-6 mb-3 form-group">
                <label for="postcode">Postcode <span class="text-danger">*</span></label>
                <input type="text" name="postcode" id="postcode" class="form-control" required value="{{ $model->postcode }}">
            </div>

            <div class="col-md-4 mb-3 form-group">
                <label for="zone_id">Zone <span class="text-danger">*</span></label>
                <select name="zone_id" id="zone_id" class="form-control select" required data-placeholder="Select one" data-parsley-errors-container="#zone_id_error">
                    <option value="">Select one</option>
                    @foreach ($zones as $zone)
                        <option {{ $model->zone_id == $zone->id ? 'selected' : '' }} value="{{ $zone->id }}">{{ $zone->name }}</option>
                    @endforeach
                </select>
                <span id="zone_id_error"></span>
            </div>

            <div class="col-md-4 form-group mb-3">
                <label for="country_id">Country <span class="text-danger">*</span></label>
                <select name="country_id" id="country_id" class="form-control select" data-parsley-errors-container="#country_id_error" required data-placeholder="Select Country">
                    <option value="">Select Country</option>
                    @foreach ($countries as $country)
                        <option {{ $model->country_id == $country->id ? 'selected' : '' }} value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
                <span id="country_id_error"></span>
            </div>

            <div class="col-md-4 form-group mb-3">
                <label for="city_id">City <span class="text-danger">*</span></label>
                <select name="city_id" id="city_id" class="form-control select" data-parsley-errors-container="#city_id_error" required data-placeholder="Select City">
                    <option value="">Select City</option>
                    @foreach ($cities as $city)
                        <option {{ $model->city_id == $city->id ? 'selected' : '' }} value="{{ $city->id }}">{{ $city->name }}</option>
                    @endforeach
                </select>
                <span id="city_id_error"></span>
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