<form action="{{ route("admin.installment.plan.store") }}" method="POST" class="ajax-form">
    <div class="modal-header">
        <h5 class="modal-title">
            <strong>Create new Installment plan</strong>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 form-group mb-3">
                <label for="name">
                    Name 
                    <span class="text-danger">*</span>
                </label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="col-md-6 form-group mb-3">
                <label for="length">
                    Duration in months 
                    <span class="text-danger">*</span>
                </label>
                <input type="text" value="1" name="length" id="length" class="form-control number" required>
            </div>
            <div class="col-md-6 form-group mb-3">
                <label for="extra_charge_percent">
                    Extra Charge in Percent (Optional)
                 </label>
                <input type="text" value="0" name="extra_charge_percent" id="extra_charge_percent" class="form-control number">
            </div>

            <div class="col-md-12 form-group">
                <label for="status">
                    Status 
                    <span class="text-danger">*</span>
                </label>
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
            Loading ...
        </button>
    </div>
</form>
