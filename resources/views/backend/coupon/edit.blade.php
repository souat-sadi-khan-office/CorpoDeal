<div class="modal-header">
    <h5 class="modal-title">Update Coupon Information</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        &times;
    </button>
</div>
<div class="modal-body">
    <form action="{{ route("admin.coupon.update", $model->id) }}" method="POST" class="ajax-form">
        @method('PATCH')
        <div class="row">

            {{-- <div class="col-md-12 form-group">
                <div class="alert alert-primary">
                    <strong>Note</strong>: Enter your price here in <b>US Dollar</b>. Price will be automatically converted to customer default currency.
                </div>
            </div> --}}
            
            <div class="col-md-12 form-group">
                <label for="coupon_code">Coupon code <span class="text-danger">*</span></label>
                <input type="text" name="coupon_code" id="coupon_code" class="form-control" minlength="5" value="{{ $model->coupon_code }}" required>
                <small class="text-muted">Coupon Code must be at least 5 Character</small>
            </div>
            
            <div class="col-md-4 mt-3 form-group">
                <label for="minimum_shipping_amount">Minimum shopping amount <span class="text-danger">*</span></label>
                <input type="text" name="minimum_shipping_amount" id="minimum_shipping_amount" value="{{ round(covert_to_defalut_currency($model->minimum_shipping_amount)) }}" class="form-control" required>
            </div>
            
            <div class="col-md-4 mt-3 form-group">
                <label for="discount_amount">Discount amount <span class="text-danger">*</span></label>
                <input type="text" name="discount_amount" id="discount_amount" value="{{ $model->discount_type == 'percent' ? $model->discount_amount : covert_to_defalut_currency($model->discount_amount) }}" class="form-control" required>
            </div>
            
            <div class="col-md-4 mt-3 form-group">
                <label for="maximum_discount_amount">Maximum discount amount <span class="text-danger">*</span></label>
                <input type="text" name="maximum_discount_amount" id="maximum_discount_amount" value="{{ covert_to_defalut_currency($model->maximum_discount_amount) }}" class="form-control" required>
            </div>

            <div class="col-md-6 mt-3 form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-control select" required>
                    <option {{ $model->status == 1 ? 'selected' : '' }} value="1">Active</option>
                    <option {{ $model->status == 0 ? 'selected' : '' }} value="0">Inactive</option>
                </select>
            </div>
            
            <div class="col-md-6 mt-3 form-group">
                <label for="discount_type">Discount Type <span class="text-danger">*</span></label>
                <select name="discount_type" id="discount_type" class="form-control select" required>
                    <option {{ $model->discount_type == 'amount' ? 'selected' : '' }} value="amount">Amount</option>
                    <option {{ $model->discount_type == 'percent' ? 'selected' : '' }} value="percent">Percent</option>
                </select>
            </div>

            {{-- <div class="col-md-6 mt-3 form-group">
                <label for="start_date">Start date <span class="text-danger">*</span></label>
                <input type="text" name="start_date" id="start_date" value="{{ $model->start_date ? date('d-m-Y', strtotime($model->start_date)) : '' }}" class="form-control">
            </div>
            
            <div class="col-md-6 mt-3 form-group">
                <label for="end_date">End date</label>
                <input type="text" name="end_date" id="end_date" value="{{ $model->end_date ? date('d-m-Y', strtotime($model->end_date)) : '' }}" class="form-control">
            </div> --}}

            <div class="col-md-6 form-group mt-3">
                <label for="is_sellable">Is Sellable</label>
                <select name="is_sellable" id="is_sellable" class="form-control">
                    <option {{ $model->is_sellable == 1 ? 'selected' : '' }} value="1">Sellable</option>
                    <option {{ $model->is_sellable == 0 ? 'selected' : '' }} value="0">Not Sellable</option>
                </select>
            </div>

            <div class="col-md-12 form-group mt-3 points_to_buy_area" style="display: {{ $model->is_sellable == 1 ? 'block' : 'none' }};">
                <label for="points_to_buy">Number of points to buy this coupon <span class="text-danger">*</span></label>
                <input type="text" name="points_to_buy" id="points_to_buy" value="{{ $model->points_to_buy }}" class="form-control number" {{ $model->is_sellable == 1 ? 'required' : '' }}>
            </div>

            <div class="col-md-6 form-group mt-3">
                <label for="is_new_user">New User Only?</label>
                <select name="is_new_user" id="is_new_user" class="form-control">
                    <option value="1" {{ old('is_new_user', $model->is_new_user ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ old('is_new_user', $model->is_new_user ?? 0) == 0 ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <div class="col-md-6 form-group mt-3">
                <label for="deadline">Coupon Deadline (optional)</label>
                <input type="date" name="deadline" id="deadline" value="{{ old('deadline', $model->deadline ?? '') }}" class="form-control">
            </div>

            <div class="col-md-6 form-group mt-3">
                <label for="platform">Platform <span class="text-danger">*</span></label>
                <select name="platform" id="platform" class="form-control" required>
                    <option value="web" {{ old('platform', $model->platform ?? '') == 'web' ? 'selected' : '' }}>Web Only</option>
                    <option value="app" {{ old('platform', $model->platform ?? '') == 'app' ? 'selected' : '' }}>App Only</option>
                    <option value="both" {{ old('platform', $model->platform ?? '') == 'both' ? 'selected' : '' }}>Web & App</option>
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