<form action="{{ route("admin.category.specification.type.store") }}" method="POST" class="{{ request()->has('key') && request()->get('key') != '' ? 'custom-form' : 'ajax-form' }}">
    @if (request()->has('key') && request()->get('key') != '')
        <input type="hidden" name="has_key" value="{{ request()->get('key') }}">
        <input type="hidden" name="specification_key_id" value="{{ request()->get('key') }}">
    @endif
    <div class="modal-header">
        <h5 class="modal-title">
            <strong>Create new Specification Key Type</strong>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            @if (!request()->has('key'))
                <div class="col-md-12 form-group mb-3">
                    <label for="specification_key_id">
                        Specification Group/Key 
                        <span class="text-danger">*</span>
                    </label>
                    <select name="specification_key_id" id="specification_key_id" class="form-control" required data-placeholder="Select One" data-parsley-errors-container="#specification_key_id_error">
                        <option value="">Select One</option>
                        @foreach ($keys as $category)
                            <option value="{{ $category['id'] }}" 
                                data-category="{{ $category['category_name'] }}">
                                {{ $category['name'] }}
                            </option>
                        @endforeach
                    </select>
                    <span id="specification_key_id_error"></span>
                </div>
            @endif

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
                <input class="form-control number" type="text" name="position" value="1" required>
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
                <label for="show_on_filter">
                    Show on Filter 
                    <span class="text-danger">*</span>
                </label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" name="is_show_on_filter" id="show_on_filter">
                </div>
            </div>

            <div id="FILTER" class="col-md-12"></div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="col-md-12 text-end">
            <button type="button" class="btn btn-sm btn-outline-danger" type="button" data-bs-dismiss="modal" aria-label="Close">
                <i class="bi bi-x"></i>
                Close
            </button>

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
</form>

<script>
    $(document).ready(function() {
        $('#specification_key_id').select2({
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
                    '<small class="text-muted">Category: ' + category + '</small>' +
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