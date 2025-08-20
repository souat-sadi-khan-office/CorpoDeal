@extends('frontend.layouts.app', ['title' => 'My Profile | ', get_settings('system_name')])
@push('page_meta_information')
    
    <link rel="canonical" href="{{ route('home') }}" />
    <meta name="referrer" content="origin">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">

    <meta name="title" content="My Profile | {{ get_settings('system_name') }}">
@endpush
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/parsley.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/select2.min.css') }}">
@endpush
@push('breadcrumb')
    <div class="breadcrumb_section page-title-mini">
        <div class="custom-container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">
                                <i class="linearicons-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                Account
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            My Account Information
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endpush

@section('content')
    <div class="section bg_gray">
        <div class="custom-container">
            <div class="row">
                @include('frontend.customer.partials.sidebar')
                <div class="col-lg-9 col-md-8 dashboard_content">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            @include('frontend.customer.partials.header')
                        </div>

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h1 class="h5">My Account Information</h1>
                                </div>
                                <div class="card-body">
                                    <p>You can update your name, email, avatar from this place. Also you can change your profile default currency. so that when you logged in, you will see the pricing according to your default currency.</p>
                                    <form method="POST" enctype="multipart/form-data" id="profile-form" action="{{ route('account.update.profile') }}">
                                        <div class="row">

                                            <div class="col-md-12 mb-3 form-group">
                                                <label for="avatar">Avatar </label>
                                                <input type="file" name="avatar" id="avatar" class="form-control">
                                            </div>

                                            <div class="form-group col-md-12 mb-3">
                                                <label>Name <span class="required">*</span></label>
                                                <input required class="form-control" value="{{ $model->name }}" name="name" type="text">
                                            </div>

                                            <div class="form-group col-md-12 mb-3">
                                                <label>Email <span class="required">*</span></label>
                                                <input required class="form-control" value="{{ $model->email }}" name="email" type="email">
                                            </div>

                                            <div class="form-group col-md-12 mb-3">
                                                <label>Default Currency <span class="required">*</span></label>
                                                <select name="currency_id" id="currency_id" class="form-control select">
                                                    @foreach ($currencies as $currency)
                                                        <option {{ $model->currency_id == $currency->id ? 'selected' : '' }} value="{{ $currency->id }}">{{ $currency->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-12 form-group mb-3">
                                                <button type="submit" id="submit" class="btn btn-fill-out">Save</button>
                                            </div>
                                            
                                            <div class="col-md-12 form-group mb-3">
                                                <button style="display: none;" class="btn btn-dark" disabled id="submitting" type="button">
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
                    
                    
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('backend/assets/js/parsley.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/toastr.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/select2.min.js') }}"></script>
    <script>
        $('.select').select2();

        var _formValidation = function () {
            if ($('#profile-form').length > 0) {
                $('#profile-form').parsley().on('field:validated', function () {
                    var ok = $('.parsley-error').length === 0;
                    $('.bs-callout-info').toggleClass('hidden', !ok);
                    $('.bs-callout-warning').toggleClass('hidden', ok);
                });
            }

            $('#profile-form').on('submit', function (e) {
                e.preventDefault();

                $('#submit').hide();
                $('#submitting').show();

                $(".ajax_error").remove();

                var submit_url = $('#profile-form').attr('action');
                var formData = new FormData($("#profile-form")[0]);

                $.ajax({
                    url: submit_url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'JSON',
                    success: function (data) {
                        if (!data.status) {
                            if (data.validator) {
                                console.log(data.validator);
                                for (const [key, messages] of Object.entries(data.validator)) {
                                    messages.forEach(message => {
                                        toastr.error(message);
                                    });
                                }
                            } else {
                                toastr.error(data.message);
                            }
                        } else {
                            toastr.success(data.message);
                            
                            $('#profile-form')[0].reset();
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

        _formValidation();
    </script>
@endpush