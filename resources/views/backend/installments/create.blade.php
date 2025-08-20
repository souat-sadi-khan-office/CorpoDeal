<div class="modal-header">
    <h5 class="modal-title">Create new Installment plan</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        &times;
    </button>
</div>
<div class="modal-body">
    <form action="{{ route("admin.installment.plan.store") }}" method="POST" class="ajax-form">

        <div class="row">

            <div class="col-md-12 mt-3 form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" placeholder="Installment Plan name" name="name" id="name" class="form-control" required>
            </div>
            <div class="col-md-12 mt-3 form-group">
                <label for="length">Duration in months <span class="text-danger">*</span></label>
                <input type="number" value="1" name="length" id="length" class="form-control" required>
            </div>
            <div class="col-md-12 mt-3 form-group">
                <label for="extra_charge_percent">Extra Charge in Percent (Optional) </label>
                <input type="number" value="0" name="extra_charge_percent" id="extra_charge_percent" class="form-control">
            </div>
            <div class="col-md-12 mt-3 form-group">
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
