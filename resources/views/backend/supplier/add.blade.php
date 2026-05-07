<form action="{{ route('admin.supplier.store') }}" method="POST" class="{{ request()->has('category') && request()->get('category') ? 'custom-form' : 'ajax-form' }}">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">
            <strong>Create New Supplier</strong>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <!-- Supplier Name -->
            <div class="col-md-12 form-group mb-3">
                <label for="name">Supplier Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <!-- Contact Email -->
            <div class="col-md-6 form-group mb-3">
                <label for="contact_email">Contact Email <span class="text-danger">*</span></label>
                <input type="email" name="contact_email" id="contact_email" class="form-control" required>
            </div>

            <!-- Contact Phone -->
            <div class="col-md-6 form-group mb-3">
                <label for="contact_phone">Contact Phone <span class="text-danger">*</span></label>
                <input type="text" name="contact_phone" id="contact_phone" class="form-control" required>
            </div>

            <!-- Address -->
            <div class="col-md-12 form-group mb-3">
                <label for="address">Address</label>
                <textarea name="address" id="address" class="form-control" rows="3"></textarea>
            </div>

            <!-- Website -->
            <div class="col-md-12 form-group mb-3">
                <label for="website">Website</label>
                <input type="url" name="website" id="website" class="form-control">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="col-md-12 text-end">
            <button type="button" class="btn btn-sm btn-outline-danger" type="button" data-bs-dismiss="modal" aria-label="Close">
                <i class="bi bi-x"></i>
                Close
            </button>
            <button class="btn btn-sm btn-dark" type="submit" id="submit">
                <i class="bi bi-send"></i> Create
            </button>
            <button class="btn btn-sm btn-outline-dark" type="button" id="submitting" style="display: none;">
                <i class="bi bi-spinner bi-spin"></i> Processing
            </button>
        </div>
    </div>
</form>