<form action="{{ route("admin.customer.point.update", $model->id) }}" method="POST" class="ajax-form">
    @csrf
    @method('PATCH')
    <div class="modal-header">
        <h5 class="h6 mb-0">
            <strong>
                Send Git Point to {{ $model->name }}
            </strong>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id" value="{{ $model->id }}">

            <div class="col-md-12 form-group mb-3">
                <label for="name">Name</label>
                <input type="text" readonly value="{{ $model->name }}" class="form-control">
            </div>

            <div class="col-md-6 form-group mb-3">
                <label for="email">Email</label>
                <input type="text" readonly value="{{ $model->email }}" class="form-control">
            </div>

            <div class="col-md-6 form-group mb-3">
                <label for="points">Customer Current Star Points</label>
                <input type="text" readonly value="{{ $model->points }}" class="form-control">
            </div>

            <div class="col-md-6 form-group mb-3">
                <label for="points">Point <span class="text-danger">*</span></label>
                <input type="text" name="points" id="points" class="form-control" required value="10">
            </div>

            <div class="col-md-6 form-group mb-3">
                <label for="method">Method <span class="text-danger">*</span></label>
                <select name="method" id="method" class="form-control" required data-minimum-results-for-search="Infinity">
                    <option selected value="plus">Plus</option>
                    <option value="minus">Minus</option>
                </select>
            </div>

            <div class="col-md-12 form-group">
                <label for="note">Note <span class="text-danger">*</span></label>
                <textarea name="note" id="note" cols="30" rows="4" class="form-control"></textarea>
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
            Submit
        </button>
        <button class="btn btn-sm btn-outline-dark" style="display: none;" id="submitting" type="button" disabled>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Loading...
        </button>
    </div>
</form>