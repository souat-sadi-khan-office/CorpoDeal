<form action="{{ route("admin.category.specification.type.attribute.store") }}" method="POST" class="{{ request()->has('type') && request()->get('type') != '' ? 'custom-form' : 'ajax-form' }}">
    @if (request()->has('type') && request()->get('type') != '')
        <input type="hidden" name="key_type_id" value="{{ request()->get('type') }}">
        <input type="hidden" name="is_active" value="1">
    @endif
    <div class="modal-header">
        <h5 class="modal-title">
            <strong>Create new Attribute</strong>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row mb-3">
            @if (!request()->has('type'))
                <div class="col-md-11 form-group">
                    <label for="key_type_id">
                        Key Type 
                        <span class="text-danger">*</span>
                    </label>
                    <select name="key_type_id" data-placeholder="Select One" id="key_type_id" class="form-control" required data-parsley-errors-container="#key_type_error">
                        <option value="">Select One</option>
                        @foreach ($keys as $category)
                            <option data-category="{{ $category['key_name'] }}" value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                        @endforeach
                    </select>
                    <span id="key_type_error"></span>
                </div>
                <div class="col-md-1 form-group">
                    <label for="status">
                        Status 
                        <span class="text-danger">*</span>
                    </label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="is_active" checked >
                    </div>
                </div>
            @endif
        </div>
        
        <div class="model-data">
            <div class="row mb-3">
                <div class="col-md-6 form-group">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name[]" id="name" class="form-control" required>
                </div>

                <div class="col-md-6 form-group">
                    <label for="name">Extra Words</label>
                    <input type="text" name="extra[]" id="extra" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="row">
                
            <div class="col-md-12 text-end">
                <button type="button" class="btn btn-sm btn-outline-danger" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x"></i>
                    Close
                </button>
                
                @if (!request()->has('type'))
                    <button type="button" class="btn add-new btn-sm btn-outline-success">
                        <i class="bi bi-plus"></i>
                        Add New
                    </button>
                @endif 

                <button class="btn btn-sm btn-dark" type="submit" id="submit">
                    <i class="bi bi-send"></i>
                    Create
                </button>
                <button class="btn btn-sm btn-outline-dark" type="button" id="submitting" style="display: none;">
                    <i class="bi bi-spinner bi-spin"></i>
                    Processing  
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#key_type_id').select2({
            dropdownParent: $('#modal_remote'),
            templateResult: formatOption,
            templateSelection: formatSelection
        });

        function formatOption(option) {
            if (!option.id) {
                return option.text;
            }
            var category = $(option.element).data('category');
            console.log(category);
            var $option = $(
                '<div>' +
                    '<strong>' + option.text + '</strong><br>' +
                    '<small class="text-muted">Type : ' + category + '</small>' +
                '</div>'
            );
            return $option;
        }

        function formatSelection(option) {
            if (!option.id) {
                return option.text;
            }
            var category = $(option.element).data('category');
            return $('<span><strong>' + option.text + '</strong> <small class="text-muted">(' + category + ')</small></span>');
        }
    });
</script>