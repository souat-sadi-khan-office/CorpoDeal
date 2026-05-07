<form id="csvUploadForm" action="{{ route('admin.supplier.product.serial-update-csv',$stock->id) }}" method="POST" class="{{ request()->has('category') && request()->get('category') ? 'custom-form' : 'ajax-form' }}" enctype="multipart/form-data">
    <div class="modal-header">
        <h5 class="h6 mb-0">
            <strong>Upload Serial Numbers</strong>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
        <p>
            Download a sample CSV file:
            <a href="{{ asset('download/serials.csv') }}" style="color:#000;" download>Download Sample CSV</a>
        </p>
        @csrf
        <div class="mb-3">
            <label for="csv_file" class="form-label">Upload CSV File</label>
            <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
        </div>
    </div>
    <div class="modal-footer text-end">
        <button type="button" class="btn btn-sm btn-outline-danger" type="button" data-bs-dismiss="modal" aria-label="Close">
            <i class="bi bi-x"></i>
            Close
        </button>

        <button class="btn btn-sm btn-dark" type="submit" id="submit">
            <i class="bi bi-send"></i> Update
        </button>

        <button class="btn btn-sm btn-outline-dark" type="button" id="submitting" style="display: none;">
            <i class="bi bi-spinner bi-spin"></i> Processing
        </button>
    </div>
</form>