<div class="modal-header">
    <h5 class="modal-title">Create new Specification Key Type</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        &times;
    </button>
</div>
<div class="modal-body">
    <form action="{{ route("admin.category.specification.type.store") }}" method="POST" class="{{ request()->has('key') && request()->get('key') != '' ? 'custom-form' : 'ajax-form' }}">
        @if (request()->has('key') && request()->get('key') != '')
            <input type="hidden" name="has_key" value="{{ request()->get('key') }}">
            <input type="hidden" name="specification_key_id" value="{{ request()->get('key') }}">
        @endif
        <div class="row">
            <div class="col-md-5 form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control mt-3 py-2" required>
            </div>
            <div class="col-md-2 mt-4 form-group text-center">
                <label for="show_on_filter">Show on Filter <span class="text-danger">*</span></label>
                <div class="form-check form-switch">
                    <input class="form-check-input" style="margin-left: 15%" type="checkbox" role="switch" name="is_show_on_filter" id="show_on_filter">
                </div>
            </div>

            <div id="FILTER" class="col-md-5"></div>

            @if (!request()->has('key'))
                <div class="col-md-5 form-group">
                    <label for="specification_key_id" class="form-label">
                        Select Specification Key 
                        <span class="text-danger">*</span>
                    </label>
                    <select name="specification_key_id" id="specification_key_id" class="form-control select" required>
                        <option value="" disabled selected>-- Select Specification Key --</option>
                        @foreach ($keys as $category)
                            <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            
            <div class="col-md-5 form-group">
                <label for="position"  class="form-label">Position <span class="text-danger">*</span></label>
                <input  class="form-control mt-2" type="number" name="position" value="1" >
              
            </div>
            <div class="col-md-2 mt-3 form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" name="is_active" checked >
                </div>
            </div>

            <div class="col-md-12 mt-3 text-end">
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

