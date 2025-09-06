<style>
    .table-specification {
        max-height: 250px;
        overflow-y: auto;
    }
</style>
<form action="{{ route('admin.product.specification.add', $product_id) }}" method="POST" class="ajax-form" >
    @csrf

    <div class="modal-header">
        <h5 class="modal-title">
            <strong>Specifications - {{ $product_name }}</strong>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 mb-3 table-responsive table-specification">
                @if (count($models) > 0)
                    <div id="specificationTable">
                        @include('backend.product.specification._table', ['models' => $models])
                    </div>
                @else   
                    <p class="text-center text-danger my-2">No Specification found for this product.</p>
                @endif
                
            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="col-mb-12 mb-4">
                        <div class="specification_key row"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="col-md-12 text-end">
            <button type="button" class="btn btn-sm btn-outline-danger" type="button" data-bs-dismiss="modal" aria-label="Close">
                <i class="bi bi-x" style="margin-right: 10px;"></i>
                Close
            </button>
            <button id="add-another" type="button" class="btn btn-sm btn-outline-success" style="display:none;">
                <i class="bi bi-plus" style="margin-right: 10px;"></i>
                Add New Specification
            </button>
            <button class="btn btn-sm btn-dark" type="submit" id="submit">
                <i class="bi bi-send" style="margin-right: 10px;"></i>
                Create
            </button>
            <button class="btn btn-sm btn-outline-dark" style="display: none;" id="submitting" type="button" disabled>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Loading...
            </button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        // Initialize validation and select components
        _componentSelect();

        // Fetch specifications based on the category ID
        var categoryId = {{ $category_id }};

        if (categoryId) {
            fetchSpecifications(categoryId);
        } else {
            console.error('Invalid category ID:', categoryId);
        }

        let specificationIndex = 0;

        function fetchSpecifications(categoryId, specDiv) {
            $.ajax({
                url: `/admin/products/specifications`,
                type: 'GET',
                data: {
                    category_id: categoryId
                },
                dataType: 'json',
                success: function(data) {
                    if (specDiv) {
                        appendSpecifications(data.keys, specDiv);
                    } else {
                        appendSpecifications(data.keys);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching specifications:', error);
                }
            });
        }

        function appendSpecifications(specifications, specDiv) {
            if (!specDiv) {
                $('.specification_key').empty();
                specDiv = createSpecificationDiv(specifications, specificationIndex++);
                $('.specification_key').append(specDiv);
            } else {
                const specSelect = specDiv.find('select[name^="specification_key"]');
                $.each(specifications, function(i, spec) {
                    specSelect.append(`<option value="${spec.id}">${spec.name}</option>`);
                });
            }

            // Initialize Select2 for the new select element
            if (specDiv) {
                specDiv.find('select[name^="specification_key"]').select2({
                    width: '100%',
                    placeholder: 'Select Specification Group/Key',
                    dropdownParent: $(specDiv)
                });
            }

            $('#add-another').show();
        }

        function createSpecificationDiv(specifications, index) {
            const specDiv = $('<div class="col-md-12">', {
                class: 'form-group mb-3 specification-group border border-3 py-2 col-md-12'
            });
            const label = $('<label>', {
                text: 'Select Specifications',
                style: 'font-weight:600;',
                for: `specification_key[${index}][key_id]`
            });
            const req = $('<span class="text-danger">*</span>');

            specDiv.append(label).append(req);

            const specSelect = $('<select>', {
                name: `specification_key[${index}][key_id]`,
                class: 'form-control mb-2 select',
                'data-id': index,
                required: true
            }).append('<option value="" disabled selected>--Select Specification--</option>');

            $.each(specifications, function(i, spec) {
                specSelect.append(`<option value="${spec.id}">${spec.name}</option>`);
            });

            specDiv.append(specSelect);

            // Initialize Select2
            specSelect.select2({
                width: '100%',
                dropdownParent: $('#modal_remote')
            });

            const addTypeButton = $('<button>', {
                class: 'btn btn-secondary btn-sm mt-2 add-type',
                text: 'Add Type',
                type: 'button',
                style: 'display:none;'
            });
            specDiv.append(addTypeButton);

            const removeSpecButton = $('<button>', {
                class: 'btn btn-danger btn-sm mt-2 remove-specification',
                text: 'Remove Specification',
                type: 'button'
            });
            specDiv.append(removeSpecButton);

            const row = $('<div>', {
                class: 'row'
            });
            specDiv.append(row);

            // Hide the remove button if this is the first specification
            if (index === 0) {
                removeSpecButton.hide();
            }

            // Specification select change event
            specSelect.change(function() {
                const selectedSpecId = $(this).val();
                row.find('.types-group').remove();
                addTypeButton.toggle(!!selectedSpecId); // Show or hide button based on selection
                if (selectedSpecId) {
                    fetchTypes(selectedSpecId, row, index, false);
                }
            });

            // Add Type Button click event
            addTypeButton.click(function() {
                const selectedSpecId = specDiv.find('select[name^="specification_key"]').val();
                if (selectedSpecId) {
                    fetchTypes(selectedSpecId, row, index, true);
                }
            });

            // Remove Specification Button click event
            removeSpecButton.click(function() {
                specDiv.remove();
            });

            return specDiv;
        }

        function fetchTypes(specId, parentDiv, index, isadd) {
            $.ajax({
                url: `/admin/products/specifications`,
                type: 'GET',
                data: {
                    key_id: specId
                },
                dataType: 'json',
                success: function(data) {
                    appendTypes(data.types, parentDiv, index, isadd);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching types:', error);
                }
            });
        }

        function appendTypes(types, parentDiv, index, isadd) {
            const typesDiv = $('<div>', {
                class: 'form-group mb-3 types-group col-md-6'
            });

            const label = $('<label>', {
                text: 'Select Types'
            });
            typesDiv.append(label);

            const typeSelect = $('<select>', {
                name: `specification_key[${index}][type_id][]`,
                class: 'form-control mb-2 col-8 select',
                'data-id': index,
                required: true
            }).append('<option value="" disabled selected>--Select Type--</option>');

            $.each(types, function(i, type) {
                typeSelect.append(`<option value="${type.id}">${type.name}</option>`);
            });

            typesDiv.append(typeSelect);

            // Initialize Select2
            typeSelect.select2({
                width: '100%',
                placeholder: 'Select Type',
                dropdownParent: $(typesDiv)
            });


            const removeTypeButton = $('<button>', {
                class: 'btn btn-danger btn-sm mt-2 col-4 remove-type',
                text: 'Remove Type',
                type: 'button'
            });
            typesDiv.append(removeTypeButton);
            if (!isadd) {
                removeTypeButton.hide()
            }

            // Add the status switch
            const statusSwitch = $('<div class="form-check form-switch">');
            const statusInput = $('<input>', {
                class: 'form-check-input',
                type: 'checkbox',
                role: 'switch',
                name: '',
                checked: false,
                disabled: true // Initially disabled
            });

            // Update name based on checked status
            statusInput.change(function() {
                const selectedTypeId = typeSelect.val();
                if ($(this).is(':checked') && selectedTypeId) {
                    $(this).attr('name',
                        `specification_key[${index}][type_id][features][${selectedTypeId}]`);
                } else {
                    $(this).attr('name', ''); // Clear the name if unchecked
                }
            });

            statusSwitch.append(statusInput);
            typesDiv.append(statusSwitch);

            parentDiv.append(typesDiv);

            // Change event for type select
            typeSelect.change(function() {
                const selectedTypeId = $(this).val();
                if (selectedTypeId) {
                    statusInput.prop('disabled', false); // Enable the switch if a type is selected
                    fetchAttributes(selectedTypeId, typesDiv, index);
                } else {
                    statusInput.prop('disabled', true); // Disable the switch if no type is selected
                    statusInput.prop('checked', false).change(); // Reset the switch and clear the name
                }
            });

            // Remove Type Button click event
            removeTypeButton.click(function() {
                typesDiv.remove();
                // Hide removeSpecButton if no types left
                if (parentDiv.find('.types-group').length === 0) {
                    parentDiv.closest('.specification-group').find('.remove-specification').hide();
                }
            });
        }

        function fetchAttributes(typeId, parentDiv, index) {
            $.ajax({
                url: `/admin/products/specifications`,
                type: 'GET',
                data: {
                    type_id: typeId
                },
                dataType: 'json',
                success: function(data) {
                    appendAttributes(data.attributes, parentDiv, index, typeId);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching attributes:', error);
                }
            });
        }

        function appendAttributes(attributes, parentDiv, index, typeId) {
            const attributesDiv = $('<div>', {
                class: 'form-group mb-3 attributes-group'
            });
            const label = $('<label>', {
                text: 'Select Attributes'
            });
            attributesDiv.append(label);

            const attrSelect = $('<select>', {
                name: `specification_key[${index}][type_id][attribute_id][${typeId}][]`,
                class: 'form-control mb-2 col-8 select',
                'data-id': index,
                required: true
            }).append('<option value="" disabled selected>--Select Attribute--</option>');

            $.each(attributes, function(i, attr) {
                let extraText = attr.extra ? (attr.extra.length > 50 ? attr.extra.substring(0, 50) +
                    '...' : attr.extra) : '';
                attrSelect.append(`<option value="${attr.id}">${attr.name} ${extraText}</option>`);
            });

            attributesDiv.append(attrSelect);
            parentDiv.append(attributesDiv);

            // Initialize Select2
            attrSelect.select2({
                width: '100%',
                placeholder: 'Select Attributes',
                dropdownParent: $(attributesDiv)
            });
        }

        $('#add-another').click(function() {
            const newSpecDiv = createSpecificationDiv([], specificationIndex++);
            $('.specification_key').append('<hr>');
            $('.specification_key').append(newSpecDiv);

            // Fetch and populate specifications for the new div
            fetchSpecifications(categoryId, newSpecDiv);
        });
    });
</script>