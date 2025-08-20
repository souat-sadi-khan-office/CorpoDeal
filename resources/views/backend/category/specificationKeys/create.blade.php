<div class="modal-header">
    <h5 class="modal-title">Create new Specification Keys</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        &times;
    </button>
</div>
<div class="modal-body">
    <form action="{{ route("admin.category.specification.key.store") }}" method="POST" class="{{ request()->has('category') && request()->get('category') ? 'custom-form' : 'ajax-form' }}">
        <div class="row">
            @if (request()->has('category') && request()->get('category'))
                <input type="hidden" name="category_id" value="{{ request()->get('category') }}">
                <div class="col-md-12 form-group mb-3">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control mt-3 py-2" required>
                </div>
                
                <div class="col-md-12 mb-3 form-group">
                    <label for="position" class="form-label">
                        Position
                        <span class="text-danger">*</span>
                    </label>
                    <input class="form-control" type="number" name="position" value="1" >
                </div>

                <div class="col-md-6 mb-3 form-group">
                    <label for="status">
                        Status 
                        <span class="text-danger">*</span>
                    </label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="is_active" checked >
                    </div>
                </div>
                <div class="col-md-6 mb-3 form-group">
                    <label for="is_public">
                        Is Public 
                        <span class="text-danger">*</span>
                    </label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="is_public_input">
                    </div>
                </div>
            @else  
                <div class="col-md-5 form-group">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control mt-3 py-2" required>
                </div>
                
                <div class="col-md-3 form-group">
                    <label for="category_id" class="form-label">
                        Select Category 
                        <span class="text-danger">*</span>
                    </label>
                    <select name="category_id" id="category_id" class="form-control select" required>
                        <option value="" disabled selected>-- Select Parent Category --</option>
                        @foreach ($categories as $category)
                            <option {{ request()->has('category') && request()->get('category') == $category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 form-group">
                    <label for="position"  class="form-label">Position <span class="text-danger">*</span></label>
                 
                        <input  class="form-control mt-2" type="number" name="position" value="1" >
                  
                </div>
                <div class="col-md-1 mt-3 form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="is_active" checked >
                    </div>
                </div>
                <div class="col-md-1 mt-3 form-group">
                    <label for="is_public">Is Public <span class="text-danger">*</span></label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="is_public_input">
                    </div>
                </div>
            @endif
            
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