<div class="modal-header">
    <h5 class="modal-title">Create New Log</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        &times;
    </button>
</div>
<div class="modal-body">
    <form action="{{ route("admin.order-log.store") }}" method="POST" class="ajax-form">
        <input type="hidden" name="order_id" value="{{ $order->id }}">
        <div class="row">

            <div class="col-md-12 form-group">
                <label for="user_id">User</label>
                <input type="text" id="user_id" class="form-control" value="{{ Auth::guard('admin')->user()->name }}" readonly>
            </div>

            <div class="col-md-12 mt-3 form-group">
                <label for="subject">Subject <span class="text-danger">*</span></label>
                <input type="text" name="subject" id="subject" class="form-control" required>
            </div>

            <div class="col-md-12 mt-3 form-group">
                <label for="content">Content <span class="text-danger">*</span></label>
                <textarea name="content" id="content" cols="30" rows="3" class="form-control" required></textarea>
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