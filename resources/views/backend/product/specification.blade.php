@extends('backend.layouts.app', ['modal' => 'lg'])
@section('title', 'Product Specification Control')
@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Product Specification Control</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <form action="{{ route('admin.product.spec.store') }}" method="POST">
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        @csrf
        <div class="card">
            <div class="card-body row">
                <div class="col-md-12 table-responsive">
                    <table class="table table-bordered" id="spec-table">
                        <tbody id="row_1">
                            <tr>
                                <th class="heading-row" colspan="2">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button type="button" data-url="{{ route('admin.category.specification.key.create', ['category' => $product->category->id]) }}" data-id="1" class="btn btn-success content_management" data-bs-toggle="tooltip" data-bs-placement="top" title="Add New" id="">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                        <select name="key[]" id="spec_key_1" class="form-control key" data-id="1" required>
                                            <option value="" disabled selected>Select Key</option>
                                            @foreach ($keys as $key)
                                                <option value="{{ $key->id }}">{{ $key->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button type="button" data-id="1" class="add-item-1 btn add-item btn-info" data-bs-toggle="tooltip" data-bs-placement="top" title="Add New">
                                                <i class="bi bi-code"></i>
                                            </button>
                                            <button type="button" data-id="1" class="remove-item-1 btn remove-item btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove Item">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                            <tr id="item_1">
                                <td class="name">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-success" disabled id="type__button_1" data-bs-toggle="tooltip" data-bs-placement="top" title="Add New" id="">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                        <select name="type[]" id="type_1" class="form-control type" data-id="1" required>
                                            <option value="" disabled selected>Select Type</option>
                                        </select>
                                    </div>
                                </td>
                                <td class="value">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-success" disabled id="attr_button_1" data-bs-toggle="tooltip" data-bs-placement="top" title="Add New" id="">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                        <select name="attribute[]" id="attr_1" class="form-control attr" data-id="1" required>
                                            <option value="" disabled selected>Select Attributes</option>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <textarea name="attr_text[]" id="attr_text_1" class="form-control" cols="30" rows="2"></textarea>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-6 text-center">
                        <button type="button" id="add_key" class="btn btn-sm btn-primary">
                            Add New
                        </button>
                    </div>
                    <div class="col-md-6 text-center">
                        <button type="submit" id="submit" class="btn btn-sm btn-success">
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <input type="hidden" id="counter" value="1">
    <input type="hidden" id="category_id" value="{{ $product->category_id }}">
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/jquery.flexdatalist.min.css') }}">
    <style>
        .heading-row {
            background: aqua !important;
        }
    </style>
@endpush
@push('script')
    <script src="{{ asset('backend/assets/js/jquery.flexdatalist.min.js') }}"></script>
    <script>
        let counter = parseInt($('#counter').val()) + 1;

        $(document).on('change', '.key', function() {
            let id = $(this).val();
            let keyId = $(this).data('id');

            _getTypes(id, keyId);
        })

        $(document).on('change', '.type', function() {
            let id = $(this).val();
            let typeId = $(this).data('id');
            let keyId = $('#spec_key_'+ id).val();

            _getKeys(id, typeId);
        })

        $(document).on('change', '.attr', function() {
            let id = $(this).val();
            let typeId = $(this).data('id');

            _getAttr(id, typeId);
        })

        var _getTypes = function (keyId, selectorId) {
            console.log("Key Id : " + keyId);
            $.ajax({
                url: '/admin/products/specifications',
                type: 'GET',
                data: {
                    key_id: keyId
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#type_' + selectorId).attr('name', 'type['+ keyId +']');
                    $('#type_' + selectorId).empty();

                    $('#type_' + selectorId).append('<option selected disabled value="">Select Type</option>');

                    if(data.types) {
                        data.types.forEach(function (type) {
                            $('#type_' + selectorId).append(
                                $('<option></option>').val(type.id).text(type.name)
                            );
                        });
                    }

                    $('#type__button_' + selectorId).addClass('content_management');
                    $('#type__button_' + selectorId).removeAttr('disabled');
                    $('#type__button_' + selectorId).attr('data-url', '/admin/categories/specification/type-create?key='+keyId);
                    $('#type__button_' + selectorId).attr('data-id', selectorId);
                }
            });
        }

        var _getKeys = function (typeId, selectorId) {
            $.ajax({
                url: '/admin/products/specifications',
                type: 'GET',
                data: {
                    type_id: typeId
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#attr_' + selectorId).empty();
                    $('#attr_' + selectorId).attr('name', 'attribute['+ typeId +']');
                    $('#attr_text_' + selectorId).attr('name', 'attr_text['+ typeId +']');

                    $('#attr_' + selectorId).append('<option selected disabled value="">Select Attributes</option>');

                    if(data.attributes) {
                        data.attributes.forEach(function (type) {
                            $('#attr_' + selectorId).append(
                                $('<option></option>').val(type.id).text(type.name)
                            );
                        });
                    }

                    $('#attr_button_' + selectorId).addClass('content_management');
                    $('#attr_button_' + selectorId).removeAttr('disabled');
                    $('#attr_button_' + selectorId).attr('data-url', '/admin/categories/specification/types/attributes/create?type='+typeId);
                    $('#attr_button_' + selectorId).attr('data-id', selectorId);
                }
            });
        }

        var _getAttr = function (attrId, selectorId) {
            $.ajax({
                url: '/admin/categories/specification/types/attributes/single/' + attrId,
                type: 'GET',
                dataType: 'JSON',
                success: function (data) {
                    $('#attr_text_' + selectorId).val(data.extra);
                }
            });
        }

        // Add new Specification Key
        $(document).on('click', '#add_key', function() {
            let content = `<tbody id="row_`+ counter +`">
                        <tr>
                            <th class="heading-row" colspan="2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button type="button" data-url="{{ route('admin.category.specification.key.create', ['category' => $product->category->id]) }}" data-id="`+ counter +`" class="btn btn-success content_management" data-bs-toggle="tooltip" data-bs-placement="top" title="Add New" id="">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                    <select name="key[]" id="spec_key_`+ counter +`" class="form-control key" data-id="`+ counter +`" required>
                                        <option value="" disabled selected>Select Key</option>
                                        @foreach ($keys as $key)
                                            <option value="{{ $key->id }}">{{ $key->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" data-id="`+ counter +`" class="add-item-`+ counter +` btn add-item btn-info" data-bs-toggle="tooltip" data-bs-placement="top" title="Add New">
                                            <i class="bi bi-code"></i>
                                        </button>
                                        <button type="button" data-id="`+ counter +`" class="remove-item-`+ counter +` btn remove-item btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove Item">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </th>
                        </tr>
                        <tr id="item_1">
                            <td class="name">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-success" disabled id="type__button_`+ counter +`" data-bs-toggle="tooltip" data-bs-placement="top" title="Add New" id="">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                    <select name="type[]" id="type_`+ counter +`" class="form-control type" data-id="`+ counter +`" required>
                                        <option value="" disabled selected>Select Type</option>
                                    </select>
                                </div>
                            </td>
                            <td class="value">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-success" disabled id="attr_button_`+ counter +`" data-bs-toggle="tooltip" data-bs-placement="top" title="Add New" id="">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                    <select name="attribute[]" id="attr_`+ counter +`" class="form-control attr" data-id="`+ counter +`" required>
                                        <option value="" disabled selected>Select Attributes</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <textarea name="attr_text[]" id="attr_text_`+ counter +`" class="form-control" cols="30" rows="2"></textarea>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
            `;

            $('#spec-table').append(content);
            counter++;
        });

        var __customFormValidation = function (selectorId = null) {
            if ($('.custom-form').length > 0) {
                $('.custom-form').parsley().on('field:validated', function () {
                    var ok = $('.parsley-error').length === 0;
                    $('.bs-callout-info').toggleClass('hidden', !ok);
                    $('.bs-callout-warning').toggleClass('hidden', ok);
                });
            }

            $('.custom-form').on('submit', function (e) {
                e.preventDefault();
                $('#submit').hide();
                $('#submitting').show();
                $(".ajax_error").remove();
                var submit_url = $('.custom-form').attr('action');
                var formData = new FormData($(".custom-form")[0]);
                $.ajax({
                    url: submit_url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'JSON',
                    success: function (data) {
                        if (data.status) {

                            toastr.success(data.message);
                            $('#submit').show();
                            $('#submitting').hide();

                            if(!data.stay) {
                                $('#modal_remote').modal('toggle');
                            }

                            if(data.key && data.id && selectorId) {
                                let newOption = $('<option></option>').val(data.id).text(data.name);
                                $('#spec_key_'+selectorId).append(newOption);
                                $('#spec_key_' + selectorId).val(data.id);

                                _getTypes(data.id, selectorId);
                            }

                            if(data.type && data.id && selectorId) {
                                let newOption = $('<option></option>').val(data.id).text(data.name);
                                $('#type_'+selectorId).append(newOption);
                                $('#type_' + selectorId).val(data.id);


                                _getKeys(data.key_id, data.id, selectorId);
                            }


                            if(data.attributes && data.id && selectorId) {
                                let newOption = $('<option></option>').val(data.id).text(data.name);
                                $('#attr_'+selectorId).append(newOption);
                                $('#attr_' + selectorId).val(data.id);
                                $('#attr_text_' + selectorId).val(data.content);
                            }




                        } else {
                            toastr.error(data.message);
                        }
                    },
                    error: function (data) {
                        var jsonValue = data.responseJSON;
                        const errors = jsonValue.errors;
                        if (errors) {
                            var i = 0;
                            $.each(errors, function (key, value) {
                                const first_item = Object.keys(errors)[i];
                                const message = errors[first_item][0];
                                if ($('#' + first_item).length > 0) {
                                    $('#' + first_item).parsley().removeError('required', {
                                        updateClass: true
                                    });
                                    $('#' + first_item).parsley().addError('required', {
                                        message: value,
                                        updateClass: true
                                    });
                                }

                                toastr.error(value);
                                i++;
                            });
                        } else {
                            toastr.error(jsonValue.message);
                        }

                        $('#submit').show();
                        $('#submitting').hide();
                    }
                });
            });
        };

        var _customRemoteModalLoadAfterAjax = function () {
            $(document).on('click', '.content_management', function (e) {
                e.preventDefault();
                $('#modal_remote').modal('toggle');
                var url = $(this).data('url');
                let selectorId = $(this).data('id');
                $('.modal-content').html('');
                $('#modal-loader').show();
                $.ajax({
                    url: url,
                    type: 'Get',
                    dataType: 'html'
                })
                .done(function (data) {
                    $('.modal-content').html(data);
                    _componentSelect2Normal();

                    __customFormValidation(selectorId);
                })
                .fail(function (data) {
                    $('.modal-content').html('<span style="color:red; font-weight: bold;"> Something Went Wrong. Please Try again later.......</span>');
                    $('#modal-loader').hide();
                });
            });
        };

        _customRemoteModalLoadAfterAjax();

        $(document).on('click', '.add-item', function() {
            let id = $(this).data('id');
            let randomNumber = getRandomNumber(1000000, 10000000000);
            let content = `
                <tr id="item_`+ randomNumber +`">
                    <td class="name">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-success" disabled="" id="type__button_`+ randomNumber +`" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Add New" data-bs-original-title="Add New">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                            <select name="type[]" id="type_`+ randomNumber +`" class="form-control type" data-id="`+ randomNumber +`" required="">
                                <option value="" disabled="" selected="">Select Type</option>
                            </select>
                        </div>
                    </td>
                    <td class="value">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-success" disabled="" id="attr_button_`+ randomNumber +`" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Add New" data-bs-original-title="Add New">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                            <select name="attribute[]" id="attr_`+ randomNumber +`" class="form-control attr" data-id="`+ randomNumber +`" required="">
                                <option value="" disabled="" selected="">Select Attributes</option>
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-danger remove-type" data-id="`+ randomNumber +`" id="attr_remove_button_`+ randomNumber +`" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Remove Type" data-bs-original-title="Remove Type">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <textarea name="attr_text[]" id="attr_text_`+ randomNumber +`" class="form-control" cols="30" rows="2"></textarea>
                            </div>
                        </div>
                    </td>
                </tr>
            `;

            $('#row_' + id).append($(content).hide()).find(':last-child').fadeIn();
            let keyId = $('#spec_key_'+id).val();
            _getTypes(keyId, randomNumber);
        });

        function getRandomNumber(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

        $(document).on('click', '.remove-type', function() {
            let id = $(this).data('id');
            $('#item_'+id).fadeOut();
            setTimeout(() => {
                $('#item_'+id).remove();
            }, 500);
        });

        $(document).on('click', '.remove-item', function() {
            let id = $(this).data('id');
            $('#row_' + id).fadeOut();
            setTimeout(() => {
                $('#row_' + id).remove();
            }, 500);
        })

    </script>
@endpush
