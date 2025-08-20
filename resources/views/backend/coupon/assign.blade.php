<div class="modal-header">
    <h5 class="modal-title">Assign Coupon To User</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        &times;
    </button>
</div>
<div class="modal-body">
    <form action="{{ route("admin.coupon.assign-to-user") }}" method="POST" class="ajax-form">

        @if ($model != null)
            <input type="hidden" name="coupon_id[]" value="{{ $model->id }}">
        @else    
            <input type="hidden" name="user_id[]" value="{{ request()->get('user') }}">
        @endif

        <div class="row">

            @if ($model != null)
                <div class="col-md-12 form-group">
                    <label for="user_id">Customers <span class="text-danger">*</span></label>
                    <select name="user_id[]" id="user_id" class="form-control select" multiple data-placeholder="Select one" data-parsley-errors-container="#user_id_error">
                        <option value="">Select one</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <span id="user_id_error"></span>
                </div>
            @else    
                <div class="col-md-12 form-group">
                    <label for="coupon_id">Coupon <span class="text-danger">*</span></label>
                    <select name="coupon_id[]" id="coupon_id" class="form-control select" multiple data-placeholder="Select one" data-parsley-errors-container="#coupon_id_error">
                        <option value="">Select one</option>
                        @foreach ($coupons as $coupon)
                            <option value="{{ $coupon->id }}">{{ $coupon->coupon_code }}</option>
                        @endforeach
                    </select>
                    <span id="coupon_id_error"></span>
                </div>
            @endif

            <div class="col-md-12 mt-3 text-end">
                <button class="btn btn-soft-success" type="submit" id="submit">
                    <i class="bi bi-send"></i>
                    Assign
                </button>
                <button class="btn btn-soft-warning" style="display: none;" id="submitting" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
            </div>
        </div>
    </form>
</div>