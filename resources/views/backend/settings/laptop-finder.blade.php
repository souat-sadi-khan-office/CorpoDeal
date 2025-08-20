@extends('backend.layouts.app')
@section('title', 'Laptop Finder Page Seo | General')
@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/dropify.min.css') }}">
@endpush
@section('content')
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h1 class="h5 mb-0">Laptop Finder Page Seo</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data"
                        class="content_form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 form-group mb-3">
                                <label for="laptop_finder_page_site_title">Site Title <span class="text-danger">*</span></label>
                                <input type="text" name="laptop_finder_page_site_title" id="laptop_finder_page_site_title" class="form-control" value="{{ get_settings('laptop_finder_page_site_title') }}" required>
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="laptop_finder_page_meta_title">Meta Title <span class="text-danger">*</span></label>
                                <input type="text" name="laptop_finder_page_meta_title" id="laptop_finder_page_meta_title" class="form-control" value="{{ get_settings('laptop_finder_page_meta_title') }}" required>
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="laptop_finder_page_meta_keyword">Meta Keyword</label>
                                <textarea name="laptop_finder_page_meta_keyword" id="laptop_finder_page_meta_keyword" cols="30" rows="3" class="form-control">{{ get_settings('laptop_finder_page_meta_keyword') }}</textarea>
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="laptop_finder_page_meta_description">Meta Description <span class="text-danger">*</span></label>
                                <textarea name="laptop_finder_page_meta_description" id="laptop_finder_page_meta_description" cols="30" rows="3" class="form-control" required>{{ get_settings('laptop_finder_page_meta_description') }}</textarea>
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="laptop_finder_page_meta_article_tag">Meta Article Tag</label>
                                <textarea name="laptop_finder_page_meta_article_tag" id="laptop_finder_page_meta_article_tag" cols="30" rows="3" class="form-control">{{ get_settings('laptop_finder_page_meta_article_tag') }}</textarea>
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="laptop_finder_page_meta_script_tag">Meta Script Tag</label>
                                <textarea name="laptop_finder_page_meta_script_tag" id="laptop_finder_page_meta_script_tag" cols="30" rows="3" class="form-control">{{ get_settings('laptop_finder_page_meta_script_tag') }}</textarea>
                            </div>

                            <div class="col-md-12 form-group text-end">
                                <button type="submit" id="submit" class="btn btn-soft-success">
                                    <i class="bi bi-send"></i>
                                    Update
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
@endsection
@push('script')
    <script src="{{ asset('backend/assets/js/dropify.min.js') }}"></script>
    <script>
        _componentSelect();
        _formValidation();

        $('.dropify').dropify({
            imgFileExtensions: ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp']
        });

        var _formValidation1 = function() {
            if ($('.ajax_form').length > 0) {
                $('.ajax_form').parsley().on('field:validated', function() {
                    var ok = $('.parsley-error').length === 0;
                    $('.bs-callout-info').toggleClass('hidden', !ok);
                    $('.bs-callout-warning').toggleClass('hidden', ok);
                });
            }

            $('.ajax_form').on('submit', function(e) {
                e.preventDefault();

                $('#submit_one').hide();
                $('#submitting_one').show();

                $(".ajax_error").remove();

                var submit_url = $('.ajax_form').attr('action');
                var formData = new FormData($(".ajax_form")[0]);

                //Start Ajax
                $.ajax({
                    url: submit_url,
                    type: 'POST',
                    data: formData,
                    contentType: false, // The content type used when sending data to the server.
                    cache: false, // To unable request pages to be cached
                    processData: false,
                    dataType: 'JSON',
                    success: function(data) {
                        console.log(data)
                        if (!data.status) {
                            if (data.validator) {
                                for (const [key, messages] of Object.entries(data.message)) {
                                    messages.forEach(message => {
                                        toastr.error(message);
                                    });
                                }
                            } else {
                                toastr.error(data.message);
                            }
                        } else {
                            toastr.success(data.message);

                            $('.ajax_form')[0].reset();
                            if (data.goto) {
                                setTimeout(function() {

                                    window.location.href = data.goto;
                                }, 500);
                            }

                            if (data.load) {
                                setTimeout(function() {

                                    window.location.href = "";
                                }, 500);
                            }

                            if (data.load) {
                                setTimeout(function() {

                                    window.location.href = "";
                                }, 1000);
                            }
                        }

                        $('#submit_one').show();
                        $('#submitting_one').hide();
                    },
                    error: function(data) {
                        var jsonValue = $.parseJSON(data.responseText);
                        const errors = jsonValue.errors;
                        if (errors) {
                            var i = 0;
                            $.each(errors, function(key, value) {
                                const first_item = Object.keys(errors)[i]
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
                                // $('#' + first_item).after('<div class="ajax_error" style="color:red">' + value + '</div');
                                toastr.error(value);
                                i++;

                            });
                        } else {
                            toastr.warning(jsonValue.message);
                        }

                        $('#submit_one').show();
                        $('#submitting_one').hide();
                    }
                });
            });
        };
        $('.ajax_form_DeliveryCharge').on('submit', function(e) {
            e.preventDefault();

            $('#DeliveryCharge').hide();
            $('#submitting_DeliveryCharge').show();

            $(".ajax_error").remove();

            var submit_url = $('.ajax_form_DeliveryCharge').attr('action');
            var formData = new FormData($(".ajax_form_DeliveryCharge")[0]);

            //Start Ajax
            $.ajax({
                url: submit_url,
                type: 'POST',
                data: formData,
                contentType: false, // The content type used when sending data to the server.
                cache: false, // To unable request pages to be cached
                processData: false,
                dataType: 'JSON',
                success: function(data) {
                    console.log(data)
                    if (!data.status) {
                        if (data.validator) {
                            for (const [key, messages] of Object.entries(data.message)) {
                                messages.forEach(message => {
                                    toastr.error(message);
                                });
                            }
                        } else {
                            toastr.error(data.message);
                        }
                    } else {
                        toastr.success(data.message);

                        $('.ajax_form_DeliveryCharge')[0].reset();
                        if (data.goto) {
                            setTimeout(function() {

                                window.location.href = data.goto;
                            }, 500);
                        }

                        if (data.load) {
                            setTimeout(function() {

                                window.location.href = "";
                            }, 500);
                        }

                        if (data.load) {
                            setTimeout(function() {

                                window.location.href = "";
                            }, 1000);
                        }
                    }

                    $('#DeliveryCharge').show();
                    $('#submitting_DeliveryCharge').hide();
                },
                error: function(data) {
                    var jsonValue = $.parseJSON(data.responseText);
                    const errors = jsonValue.errors;
                    if (errors) {
                        var i = 0;
                        $.each(errors, function(key, value) {
                            const first_item = Object.keys(errors)[i]
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
                            // $('#' + first_item).after('<div class="ajax_error" style="color:red">' + value + '</div');
                            toastr.error(value);
                            i++;

                        });
                    } else {
                        toastr.warning(jsonValue.message);
                    }

                    $('#DeliveryCharge').show();
                    $('#submitting_DeliveryCharge').hide();
                }
            });
        });
        _formValidation1();

        var _formValidation2 = function() {
            if ($('.ajax_form_one').length > 0) {
                $('.ajax_form_one').parsley().on('field:validated', function() {
                    var ok = $('.parsley-error').length === 0;
                    $('.bs-callout-info').toggleClass('hidden', !ok);
                    $('.bs-callout-warning').toggleClass('hidden', ok);
                });
            }

            $('.ajax_form_one').on('submit', function(e) {
                e.preventDefault();

                $('#submit_two').hide();
                $('#submitting_two').show();

                $(".ajax_error").remove();

                var submit_url = $('.ajax_form_one').attr('action');
                var formData = new FormData($(".ajax_form_one")[0]);

                //Start Ajax
                $.ajax({
                    url: submit_url,
                    type: 'POST',
                    data: formData,
                    contentType: false, // The content type used when sending data to the server.
                    cache: false, // To unable request pages to be cached
                    processData: false,
                    dataType: 'JSON',
                    success: function(data) {
                        console.log(data)
                        if (!data.status) {
                            if (data.validator) {
                                for (const [key, messages] of Object.entries(data.message)) {
                                    messages.forEach(message => {
                                        toastr.error(message);
                                    });
                                }
                            } else {
                                toastr.error(data.message);
                            }
                        } else {
                            toastr.success(data.message);

                            $('.ajax_form_one')[0].reset();
                            if (data.goto) {
                                setTimeout(function() {

                                    window.location.href = data.goto;
                                }, 500);
                            }

                            if (data.load) {
                                setTimeout(function() {

                                    window.location.href = "";
                                }, 500);
                            }

                            if (data.load) {
                                setTimeout(function() {

                                    window.location.href = "";
                                }, 1000);
                            }
                        }

                        $('#submit_two').show();
                        $('#submitting_two').hide();
                    },
                    error: function(data) {
                        var jsonValue = $.parseJSON(data.responseText);
                        const errors = jsonValue.errors;
                        if (errors) {
                            var i = 0;
                            $.each(errors, function(key, value) {
                                const first_item = Object.keys(errors)[i]
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
                                // $('#' + first_item).after('<div class="ajax_error" style="color:red">' + value + '</div');
                                toastr.error(value);
                                i++;

                            });
                        } else {
                            toastr.warning(jsonValue.message);
                        }

                        $('#submit_two').show();
                        $('#submitting_two').hide();
                    }
                });
            });
        };

        _formValidation2();
    </script>
@endpush
