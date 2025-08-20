<div class="modal-header">
    <h5 class="modal-title">Create new Specification Key Type Attribute</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        &times;
    </button>
</div>
<div class="modal-body">
    <form action="{{ route("admin.category.specification.type.attribute.store") }}" method="POST" class="{{ request()->has('type') && request()->get('type') != '' ? 'custom-form' : 'ajax-form' }}">
        <div class="row">

            @if (request()->has('type') && request()->get('type') != '')
                <input type="hidden" name="key_type_id" value="{{ request()->get('type') }}">
                <input type="hidden" name="is_active" value="1">
            @endif

            @if (!request()->has('type'))
                <div class="col-md-11 form-group">
                    <label for="key_type_id" class="form-label">
                        Select Specification Key Type 
                        <span class="text-danger">*</span>
                    </label>
                    <select name="key_type_id" data-placeholder="-- Select Specification Key Type --" id="key_type_id" class="form-control select" required data-parsley-errors-container="#key_type_error">
                        <option value="">-- Select Specification Key Type --</option>
                        @foreach ($keys as $category)
                            <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                        @endforeach
                    </select>
                    <span id="key_type_error"></span>
                </div>
                <div class="col-md-1 mt-3 form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="is_active" checked >
                    </div>
                </div>
            @endif
        </div>
        
        <div class="model-data">
            <div class="row mt-2">
                <div class="col-md-6 form-group">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name[]" id="name" class="form-control mt-3 py-2" required>
                </div>
    
                <div class="col-md-6 form-group">
                    <label for="name">Extra Words</label>
                    <input type="text" name="extra[]" id="extra" class="form-control mt-3 py-2">
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                @if (!request()->has('type'))
                    <button type="button" class="btn add-new btn-outline-primary">
                        <i class="bi bi-plus"></i>
                        Add New
                    </button>
                @endif 
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-soft-success" type="submit" id="submit">
                    <i class="bi bi-send"></i>
                    Create
                </button>
                <button class="btn btn-soft-warning" type="button" id="submitting" style="display: none;">
                    <i class="bi bi-spinner bi-spin"></i>
                    Processing  
                </button>
            </div>
        </div>
    </form>
</div>

