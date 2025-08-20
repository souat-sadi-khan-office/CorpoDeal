<div class="modal-header">
    <h5 class="modal-title">Show Customer Information</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        &times;
    </button>
</div>
<div class="modal-body">
    <form action="{{ route("admin.customer.point.update", $model->id) }}" method="POST" class="ajax-form">
        @csrf
        @method('PATCH')
        <div class="row">
            <input type="hidden" name="id" value="{{ $model->id }}">

            <div class="col-md-12 form-group mb-3">
                <label for="name">Name</label>
                <input type="text" readonly value="{{ $model->name }}" class="form-control">
            </div>

            <div class="col-md-12 form-group mb-3">
                <label for="email">Email</label>
                <input type="text" readonly value="{{ $model->email }}" class="form-control">
            </div>

            <div class="col-md-12 form-group mb-3">
                <label for="points">Customer Current Star Points</label>
                <input type="text" readonly value="{{ $model->points }}" class="form-control">
            </div>

            <div class="col-md-12 form-group mb-3">
                <label for="points">Point <span class="text-danger">*</span></label>
                <input type="text" name="points" id="points" class="form-control" required value="10">
            </div>

            <div class="col-md-12 form-group mb-3">
                <label for="method">Method <span class="text-danger">*</span></label>
                <select name="method" id="method" class="form-control" required>
                    <option selected value="plus">Plus</option>
                    <option value="minus">Minus</option>
                </select>
            </div>

            <div class="col-md-12 form-group mb-3">
                <label for="note">Note <span class="text-danger">*</span></label>
                <textarea name="note" id="note" cols="30" rows="4" class="form-control"></textarea>
            </div>

            <div class="col-md-12 mt-3 text-end">
                <button class="btn btn-soft-success" type="submit" id="submit">
                    <i class="bi bi-send"></i>
                    Submit
                </button>
                <button class="btn btn-soft-warning" style="display: none;" id="submitting" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
            </div>
        </div>
    </form>
</div>