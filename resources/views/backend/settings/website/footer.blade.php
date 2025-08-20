@extends('backend.layouts.app')
@section('title', 'Website Footer Configuration')
@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/dropify.min.css') }}">
@endpush
@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="h3 mb-0">Website Footer Configuration</h1>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="content_form">
    @csrf
    <div class="row">
        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">About Wizard</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 form-group mb-3">
                            <label for="system_about_wizard">About description</label>
                            <textarea name="system_about_wizard" id="system_about_wizard" class="form-control" rows="5" required>{!! get_settings('system_about_wizard') !!}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Contact Info Widget</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 form-group mb-3">
                            <label for="system_footer_contact_address">Contact address</label>
                            <input type="text" name="system_footer_contact_address" id="system_footer_contact_address" class="form-control" value="{{ get_settings('system_footer_contact_address') }}">
                        </div>
                        <div class="col-md-12 form-group mb-3">
                            <label for="system_footer_contact_phone">Contact phone</label>
                            <input type="text" name="system_footer_contact_phone" id="system_footer_contact_phone" class="form-control" value="{{ get_settings('system_footer_contact_phone') }}">
                        </div>
                        <div class="col-md-12 form-group mb-3">
                            <label for="system_footer_contact_email">Contact email</label>
                            <input type="text" name="system_footer_contact_email" id="system_footer_contact_email" class="form-control" value="{{ get_settings('system_footer_contact_email') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mt-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Link Widget One</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 form-group mb-3">
                            <label for="footer_menu_one_label_text">Title</label>
                            <input type="text" name="footer_menu_one_label_text" id="footer_menu_one_label_text" class="form-control" value="{{ get_settings('footer_menu_one_label_text') }}">
                        </div>
                        <div class="col-md-12 form-group mb-3">
                            <div class="footer-nav-menu-one">
                                @if (get_settings('footer_menu_one_labels') != null)
                                    @php
                                        $rand = rand(10000, 1000000);
                                    @endphp
                                    @foreach ( json_decode(get_settings('footer_menu_one_labels')) as $key => $value)
                                        <div class="row mt-3" id="data-{{ $rand}}">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Label" name="footer_menu_one_labels[]" required value="{{ $value }}">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Link with http:// or https://" name="footer_menu_one_links[]" value="{{ json_decode(App\Models\ConfigurationSetting::where('type', 'footer_menu_one_links')->first()->value, true)[$key] }}" required>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger remove-parent" data-parent="{{ $rand }}">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button
                                type="button"
                                class="btn mt-4 btn-primary btn-sm add-more-one">
                                Add New
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mt-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Link Widget Two</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 form-group mb-3">
                            <label for="footer_menu_tow_label_text">Title</label>
                            <input type="text" name="footer_menu_tow_label_text" id="footer_menu_tow_label_text" class="form-control" value="{{ get_settings('footer_menu_tow_label_text') }}">
                        </div>
                        <div class="col-md-12 form-group mb-3">
                            <div class="footer-nav-menu-two">
                                @if (get_settings('footer_menu_two_labels') != null)
                                    @php
                                        $rand = rand(10000, 1000000);
                                    @endphp
                                    @foreach ( json_decode(get_settings('footer_menu_two_labels')) as $key => $value)
                                        <div class="row mt-3" id="data-{{ $rand}}">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Label" name="footer_menu_two_labels[]" required value="{{ $value }}">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Link with http:// or https://" name="footer_menu_two_links[]" value="{{ json_decode(App\Models\ConfigurationSetting::where('type', 'footer_menu_two_links')->first()->value, true)[$key] }}" required>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger remove-parent" data-parent="{{ $rand }}">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button
                                type="button"
                                class="btn mt-4 btn-primary btn-sm add-more-two">
                                Add New
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-12 mt-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Copy Right Wizard</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 form-group mb-3">
                            <label for="system_copyright_wizard">Copy right description</label>
                            <textarea name="system_copyright_wizard" id="system_copyright_wizard" class="form-control" rows="5" required>{!! get_settings('system_copyright_wizard') !!}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mt-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Social Links</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="bi bi-facebook"></i>
                            </span>
                            <input type="url" class="form-control" name="system_facebook_link" id="system_facebook_link" value="{{ get_settings('system_facebook_link') }}">
                        </div>
                        <div class="col-md-12 input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="bi bi-twitter-x"></i>
                            </span>
                            <input type="text" class="form-control" name="system_twitter_link" id="system_twitter_link" value="{{ get_settings('system_twitter_link') }}">
                        </div>
                        <div class="col-md-12 input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="bi bi-instagram"></i>
                            </span>
                            <input type="text" class="form-control" name="system_instagram_link" id="system_instagram_link" value="{{ get_settings('system_instagram_link') }}">
                        </div>
                        <div class="col-md-12 input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="bi bi-youtube"></i>
                            </span>
                            <input type="text" class="form-control" name="system_youtube_link" id="system_youtube_link" value="{{ get_settings('system_youtube_link') }}">
                        </div>
                        <div class="col-md-12 input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="bi bi-linkedin"></i>
                            </span>
                            <input type="text" class="form-control" name="system_linkedin_link" id="system_linkedin_link" value="{{ get_settings('system_linkedin_link') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Payment Methods Widget</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 form-group mb-3">
                            <label for="system_payment_method_photo">Photo </label>
                            <input type="file" name="system_payment_method_photo" id="system_payment_method_photo" class="form-control dropify" data-default-file="{{ get_settings('system_payment_method_photo') ? asset(get_settings('system_payment_method_photo')) : '' }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 mt-3">
            <div class="card">
                <div class="card-body text-end">
                    <button type="submit" id="submit" class="btn btn-soft-success">
                        <i class="bi bi-send"></i>
                        Update
                    </button>
                    <button class="btn btn-soft-warning" style="display: none;" id="submitting" type="button" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@push('script')
    <script src="{{ asset('backend/assets/js/dropify.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.9.2/ckeditor.js"></script>    
    <script>
        _componentSelect();

        var _formValidationCustom = function () {
            if ($('.content_form').length > 0) {
                $('.content_form').parsley().on('field:validated', function () {
                    var ok = $('.parsley-error').length === 0;
                    $('.bs-callout-info').toggleClass('hidden', !ok);
                    $('.bs-callout-warning').toggleClass('hidden', ok);
                });
            }

            $('.content_form').on('submit', function (e) {
                e.preventDefault();

                $('#submit').hide();
                $('#submitting').show();

                $(".ajax_error").remove();

                var submit_url = $('.content_form').attr('action');
                var formData = new FormData($(".content_form")[0]);

                const system_about_wizard = CKEDITOR.instances['system_about_wizard'].getData();
                const system_copyright_wizard = CKEDITOR.instances['system_copyright_wizard'].getData();
                formData.append('system_about_wizard', system_about_wizard);
                formData.append('system_copyright_wizard', system_copyright_wizard);
                
                //Start Ajax
                $.ajax({
                    url: submit_url,
                    type: 'POST',
                    data: formData,
                    contentType: false, // The content type used when sending data to the server.
                    cache: false, // To unable request pages to be cached
                    processData: false,
                    dataType: 'JSON',
                    success: function (data) {
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
                            
                            $('.content_form')[0].reset();

                            if (data.load) {
                                setTimeout(function () {

                                    window.location.href = "";
                                }, 500);
                            }
                        }

                        $('#submit').show();
                        $('#submitting').hide();
                    },
                    error: function (data) {
                        var jsonValue = $.parseJSON(data.responseText);
                        const errors = jsonValue.errors;
                        if (errors) {
                            var i = 0;
                            $.each(errors, function (key, value) {
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

                        $('#submit').show();
                        $('#submitting').hide();
                    }
                });
            });
        };

        _formValidationCustom();

        let _initCkEditor = function(editorName, startupFocus = false, editorHeight = false) {
            CKEDITOR.replace(editorName, {
                // filebrowserUploadUrl: 'ck_upload.php', //Later
                filebrowserUploadMethod: 'form',
                height: editorHeight ? editorHeight : '',
                startupFocus: startupFocus == 1 ? true : false,
                removePlugins: 'exportpdf',
                toolbar: [
                    ['Format', 'Font', 'FontSize', '-'],
                    ['Bold', 'Italic', 'Underline', 'Table', '-', 'NumberedList', 'BulletedList', '-'],
                    ["JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock"],
                    ['Link', 'Blockquote', 'Maximize', 'Image', 'TextColor', '-', 'Source']
                ]
            });
        }

        $('.dropify').dropify();
        _initCkEditor("system_about_wizard");
        _initCkEditor("system_copyright_wizard");

        $(document).on('click', '.add-more-one', function() {
            let id = Math.floor((Math.random() * 10000000) + 1);
            let content = `<div class="row mt-3" id="data-`+ id +`">
                    <div class="col-4">
                        <div class="form-group">
                            <input type="text" required class="form-control" placeholder="Label" name="footer_menu_one_labels[]">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <input type="text" required class="form-control" placeholder="Link with http:// or https://" name="footer_menu_one_links[]">
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger remove-parent-one" data-parent="`+ id+`">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>`;
            $('.footer-nav-menu-one').append(content);
        });

        $(document).on('click', '.remove-parent-one', function() {
            let id = $(this).data('parent');
            $('#data-'+id).remove();
        })

        $(document).on('click', '.add-more-two', function() {
            let id = Math.floor((Math.random() * 10000000) + 1);
            let content = `<div class="row mt-3" id="data-`+ id +`">
                    <div class="col-4">
                        <div class="form-group">
                            <input type="text" required class="form-control" placeholder="Label" name="footer_menu_two_labels[]">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <input type="text" required class="form-control" placeholder="Link with http:// or https://" name="footer_menu_two_links[]">
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger remove-parent-two" data-parent="`+ id+`">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>`;
            $('.footer-nav-menu-two').append(content);
        });

        $(document).on('click', '.remove-parent-two', function() {
            let id = $(this).data('parent');
            $('#data-'+id).remove();
        });
        
        $(document).on('click', '.add-more-three', function() {
            let id = Math.floor((Math.random() * 10000000) + 1);
            let content = `<div class="row mt-3" id="data-`+ id +`">
                    <div class="col-4">
                        <div class="form-group">
                            <input type="text" required class="form-control" placeholder="Label" name="footer_menu_three_labels[]">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <input type="text" required class="form-control" placeholder="Link with http:// or https://" name="footer_menu_three_links[]">
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger remove-parent-three" data-parent="`+ id+`">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>`;
            $('.footer-nav-menu-two').append(content);
        });

        $(document).on('click', '.remove-parent-three', function() {
            let id = $(this).data('parent');
            $('#data-'+id).remove();
        });
    </script>
@endpush