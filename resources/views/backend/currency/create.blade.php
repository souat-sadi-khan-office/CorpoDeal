<div class="modal-header">
    <h5 class="modal-title">Create new Currency</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        &times;
    </button>
</div>
<div class="modal-body">
    <form action="{{ route("admin.currency.store") }}" method="POST" class="ajax-form">
        <div class="row">

            <div class="col-md-12 form-group">
                <label for="country_id">Country name<span class="text-danger">*</span></label>
                <select name="country_id" id="country_id" class="form-control select" data-placeholder="Select Country" required data-parsley-errors-container="#country_id_error">
                    <option value="">Select Country</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
                <span id="country_id_error"></span>
            </div>

            <div class="col-md-12 mt-3 form-group">
                <label for="name">Currency name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="col-md-12 mt-3 form-group">
                <label for="symbol">Currency symbol <span class="text-danger">*</span></label>
                <input type="text" name="symbol" id="symbol" class="form-control" required>
            </div>

            <div class="col-md-12 mt-3 form-group">
                <label for="code">Currency code <span class="text-danger">*</span></label>
                <input type="text" name="code" id="code" class="form-control" required>
            </div>

            <div class="col-md-12 mt-3 form-group">
                <label for="exchange_rate">Exchange rate <span class="text-danger">*</span></label>
                <input type="text" name="exchange_rate" id="exchange_rate" class="form-control" required>
            </div>

            <div class="col-md-6 mt-3 form-group">
                <label for="decimal_separator">Status <span class="text-danger">*</span></label>
                <select name="decimal_separator" id="decimal_separator" class="form-control select" required>
                    <option value="0">0 Decimal Number</option>
                    <option value="1">1 Decimal Number</option>
                    <option selected value="2">2 Decimal Number</option>
                    <option value="3">3 Decimal Number</option>
                    <option value="4">4 Decimal Number</option>
                    <option value="5">5 Decimal Number</option>
                </select>
            </div>

            <div class="col-md-6 mt-3 form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-control select" required>
                    <option selected value="1">Active</option>
                    <option value="0">Inactive</option>
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