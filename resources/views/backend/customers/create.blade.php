<div class="modal-header">
    <h5 class="modal-title">Create new Customer</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        &times;
    </button>
</div>
<div class="modal-body">
    <form action="{{ route("admin.customer.store") }}" method="POST" class="ajax-form">
        @if (Request::get('from') && Request::get('from') == 'offline-sale')
            <input type="hidden" name="from" value="offline-sale">
        @endif
        <div class="row">

            <div class="col-md-12 form-group">
                <label for="currency_id">Currency <span class="text-danger">*</span></label>
                <select name="currency_id" id="currency_id" class="form-control select" data-placeholder="Select One" required data-parsley-errors-container="#currency_id_error">
                    <option value="">Select One</option>
                    @foreach ($currencies as $currency)
                        <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                    @endforeach
                </select>
                <span id="currency_id_error"></span>
            </div>

            <div class="col-md-12 mt-3 form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            
            <div class="col-md-12 mt-3 form-group">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            
            <div class="col-md-12 mt-3 form-group">
                <label for="password">Password <span class="text-danger">*</span></label>
                <input type="password" name="password" id="password" class="form-control" required>
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