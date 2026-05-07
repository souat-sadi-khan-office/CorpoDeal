<div class="modal-header">
    <h5 class="modal-title">
        <strong>Create new Customer</strong>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form action="{{ route("admin.customer.store") }}" method="POST" class="ajax-form">
        @if (Request::get('from') && Request::get('from') == 'offline-sale')
            <input type="hidden" name="from" value="offline-sale">
        @endif
        <div class="row">
            <input type="hidden" name="currency_id" value="305">

            <!-- <div class="col-md-12 form-group">
                <label for="currency_id">Currency <span class="text-danger">*</span></label>
                <select name="currency_id" id="currency_id" class="form-control select" data-placeholder="Select One" required data-parsley-errors-container="#currency_id_error">
                    <option value="">Select One</option>
                    @foreach ($currencies as $currency)
                        <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                    @endforeach
                </select>
                <span id="currency_id_error"></span>
            </div> -->

            <div class="col-md-12 mb-3 form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            
            <div class="col-md-12 mb-3 form-group">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            
            <div class="col-md-12 mb-3 form-group">
                <label for="password">Password <span class="text-danger">*</span></label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="col-md-12 mb-3 form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-control select" required data-minimum-results-for-search="Infinity">
                    <option selected value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div class="col-md-12 mt-3 text-end">
                <button type="button" class="btn btn-sm btn-outline-danger" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x"></i>
                    Close
                </button>

                <button class="btn btn-sm btn-dark" type="submit" id="submit">
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