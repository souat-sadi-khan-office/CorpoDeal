<h3 class="m-4">Specifications - {{ $product_name }}</h3>

<div class="card">

    <div class="card-body">
        <div class="row">
            <div class="col-md-12 table-responsive">
                
                @foreach ($models as $data)
                    <hr>

                    <h5>{{ @$data[0]['specificationKey'] }}</h5>
                    <hr>
                    @foreach ($data as $item)
                        <div class="row mx-3 my-1" data-row-id="{{ $item['id'] }}">
                            <div class="col-md-4 border-end">
                                {{ $item['specificationKeyType'] }}
                            </div>
                            <div class="col-md-5 border-end">
                                {{ $item['specificationKeyTypeAttribute'] }}
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="form-check form-switch col-md-7"
                                        style=" padding-left: 2.9em!important;">
                                        <span>Key Feature? </span>
                                        <input
                                            data-url="{{ route('admin.product.specification.keyfeature', $item['id']) }}"
                                            class="form-check-input" type="checkbox" role="switch" name="is_featured"
                                            id="status{{ $item['id'] }}"
                                            {{ $item['key_feature'] == 1 ? 'checked' : '' }}
                                            data-id="{{ $item['id'] }}">
                                    </div>
                                    <div class="col-md-5">
                                        <a class="btn btn-sm btn-outline-danger" href="javascript:;"
                                            id="delete_specification" data-id ="{{ $item['id'] }}"
                                            data-url="{{ route('admin.product.specification.delete', $item['id']) }}">
                                            <i class="bi bi-trash"></i>
                                            Remove
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                @endforeach

                <form action="{{ route('admin.product.specification.add', $product_id) }}" method="POST"
                    class="content_form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">

                        <div class="col-md-12">
                            <div class="row">
                                <!-- Product Specification -->
                                <div class="col-mb-12 mb-4">
                                    <div class="card">
                                        <div class="card-header">

                                            <button id="add-another" type="button" class="btn btn-primary mt-2"
                                                style="display:none;">Add New
                                                Specification</button>
                                            <span class="text-danger mx-5"> Duplicate Types Will be Not Counted for a
                                                Specification Key</span>
                                        </div>
                                        <div class="card-body">
                                            <div class="col-md-12">
                                                <div class="specification_key row"></div>
                                                {{-- <button id="add-another" type="button" class="btn btn-primary mt-2" style="display:none;">Add Another
                                                    Specification</button> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="col-md-12 form-group mb-3 text-end">
                            <button class="btn btn-soft-success" type="submit" id="submit">
                                <i class="bi bi-send"></i>
                                Create
                            </button>
                            <button class="btn btn-soft-warning" style="display: none;" id="submitting" type="button"
                                disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading...
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize validation and select components
        _initializeMultipleFormsValidation();
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
                    dropdownParent: $(specDiv)
                });
            }

            $('#add-another').show();
        }

        function createSpecificationDiv(specifications, index) {
            const specDiv = $('<div>', {
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
