<form action="{{ route("admin.category.specification.key.store") }}" method="POST" class="{{ request()->has('category') && request()->get('category') ? 'custom-form' : 'ajax-form' }}">
    <div class="modal-header">
        <h5 class="modal-title">
            <strong>Create new Specification Keys</strong>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
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
                
                <div class="col-md-12 form-group mb-3">
                    <label for="category_id" class="form-label">
                        Product Category 
                        <span class="text-danger">*</span>
                    </label>
                    <select name="category_id" id="category_id" class="form-control" required data-placeholder="Select One" data-parsley-errors-container="#category_id_error">
                        <option value="">Select One</option>
                        @foreach ($categories as $category)
                            <option 
                                value="{{ $category->id }}" 
                                data-parent="{{ $category->parent ? $category->parent->name : 'Self Parent' }}"
                                {{ request()->has('category') && request()->get('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <span id="category_id_error"></span>
                </div>

                <div class="col-md-6 form-group mb-3">
                    <label for="name">
                        Name 
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                
                <div class="col-md-6 form-group mb-3">
                    <label for="position">
                        Position 
                        <span class="text-danger">*</span>
                    </label>
                    <input class="form-control number" type="text" name="position" value="1" >
                </div>

                <div class="col-md-6 form-group mb-3">
                    <label for="status">
                        Status 
                        <span class="text-danger">*</span>
                    </label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="is_active" checked >
                    </div>
                </div>

                <div class="col-md-6 form-group mb-3">
                    <label for="is_public">
                        Is Public 
                        <span class="text-danger">*</span>
                    </label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" checked name="is_public_input">
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="modal-footer">
        <div class="col-md-12 mt-3 text-end">
            <button type="button" class="btn btn-sm btn-outline-danger" type="button" data-bs-dismiss="modal" aria-label="Close">
                <i class="bi bi-x"></i>
                Close
            </button>
            
            <button class="btn btn-sm btn-dark" type="submit" id="submit">
                <i class="bi bi-send"></i>
                Create
            </button>
            <button class="btn btn-xm btn-outline-dark" type="button" id="submitting" style="display: none;">
                <i class="bi bi-spinner bi-spin"></i>
                Processing  
            </button>
        </div>
    </div>
</form>

<script>
    $('#category_id').select2({
        templateResult: formatCategory,
        templateSelection: formatSelection,
        placeholder: $(this).data('placeholder') || "Select One",
        width: '100%',
        dropdownParent: $('#modal_remote')
    });

    function formatCategory(option) {
        if (!option.id) return option.text;

        var parent = $(option.element).data('parent') || '';
        var $option = $(
            `<div>
                <div style="font-weight: 600;">${option.text}</div>
                <small">Parent Category: ${parent}</small>
            </div>`
        );
        return $option;
    }

    function formatSelection(option) {
        if (!option.id) {
            return option.text;
        }
        var parent = $(option.element).data('parent') || '';
        return $('<span><strong>' + option.text + '</strong> <small class="text-muted">(' + parent + ')</small></span>');
    }
</script>