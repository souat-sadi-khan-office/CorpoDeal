<div class="modal-header">
    <h5 class="modal-title">Update Customer Information</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        &times;
    </button>
</div>
<div class="modal-body">
    <form action="{{ route("admin.customer.update", $model->id) }}" method="POST" class="ajax-form">
        @csrf
        @method('PATCH')
        <div class="row">
            <input type="hidden" name="id" value="{{ $model->id }}">

            <div class="col-md-12 form-group">
                <label for="currency_id">Currency <span class="text-danger">*</span></label>
                <select name="currency_id" id="currency_id" class="form-control select" data-placeholder="Select One" required data-parsley-errors-container="#currency_id_error">
                    <option value="">Select One</option>
                    @foreach ($currencies as $currency)
                        <option {{ $currency->id == $model->currency_id ? 'selected' : '' }} value="{{ $currency->id }}">{{ $currency->name }}</option>
                    @endforeach
                </select>
                <span id="currency_id_error"></span>
            </div>

            <div class="col-md-12 mt-3 form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" value="{{ $model->name }}" name="name" id="name" class="form-control" required>
            </div>
            
            <div class="col-md-12 mt-3 form-group">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input type="email" value="{{ $model->email }}" name="email" id="email" class="form-control" required>
            </div>
            
            <div class="col-md-12 mt-3 form-group">
                <label for="password">Password <span class="text-danger">*</span></label>
                <input type="password" name="password" id="password" class="form-control">
                <small class="text-primary">If you want to change this user password, then you can enter here. Otherwise make this field empty.</small>
            </div>

            <div class="col-md-12 mt-3 form-group">
                <label for="is_premium">Premium User <span class="text-danger">*</span></label>
                <select name="is_premium" id="is_premium" class="form-control select" required>
                    <option {{ $model->is_premium == 1 ? 'selected' : '' }} value="1">Premium User</option>
                    <option {{ $model->is_premium == 0 ? 'selected' : '' }} value="0">Normal User</option>
                </select>
            </div>

            <div class="col-md-12 mt-3 form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-control select" required>
                    <option {{ $model->status == 1 ? 'selected' : '' }} value="1">Active</option>
                    <option {{ $model->status == 0 ? 'selected' : '' }} value="0">Inactive</option>
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