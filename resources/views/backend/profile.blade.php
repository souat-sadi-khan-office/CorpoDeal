@extends('backend.layouts.app')
@section('title', 'My Profile')
@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="h3 mb-0">My Profile</h1>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img width="100" class="profile-user-img img-fluid img-circle" src="{{ Auth::guard('admin')->user()->avatar ? asset(Auth::guard('admin')->user()->avatar) : asset('pictures/face.jpg') }}" alt="{{ Auth::guard('admin')->user()->name }} Picture">
                    </div>

                    <h3 class="profile-username text-center">
                        {{ Auth::guard('admin')->user()->name }}
                    </h3>

                    <p class="text-muted text-center">
                        {{ Auth::guard('admin')->user()->designation }}
                    </p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right" href="mailto:{{ Auth::guard('admin')->user()->email }}">
                                {{ Auth::guard('admin')->user()->email }}
                            </a>
                        </li>
                        <li class="list-group-item">
                            <b>Phone</b> <a class="float-right" href="tel:{{ Auth::guard('admin')->user()->phone }}">
                                {{ Auth::guard('admin')->user()->phone }}
                            </a>
                        </li>
                        <li class="list-group-item">
                            <b>Allow Changes</b> 
                            <a class="float-right">
                                @if (Auth::guard('admin')->user()->allow_changes == 1)
                                    <span class="badge bg-success text-white">Yes</span>
                                @else  
                                    <span class="badge bg-danger text-white">No</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">About Me</h3>
                </div>
                <div class="card-body">
                    <strong>
                        <i class="bi bi-compass"></i> Address 
                    </strong>

                    <p class="text-muted">
                        {{ Auth::guard('admin')->user()->address }}
                    </p>

                    <hr>

                    <strong>
                        <i class="bi bi-crosshair"></i>
                        Area 
                    </strong>

                    <p class="text-muted">
                        {{ Auth::guard('admin')->user()->area }}
                    </p>

                    <hr>
                    
                    <strong>
                        <i class="bi bi-geo-alt"></i>
                        City 
                    </strong>

                    <p class="text-muted">
                        {{ Auth::guard('admin')->user()->city }}
                    </p>

                    <hr>
                    
                    <strong>
                        <i class="bi bi-geo-alt"></i>
                        Country 
                    </strong>

                    <p class="text-muted">
                        {{ Auth::guard('admin')->user()->country }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" href="#activity" data-bs-toggle="tab">
                                <i class="bi bi-person"></i>
                                Activity
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#settings" data-bs-toggle="tab">
                                <i class="bi bi-pencil-square"></i>
                                Update Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#password" data-bs-toggle="tab">
                                <i class="bi bi-pencil-square"></i>
                                Update Password
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="activity">
                            @if (count($logs) > 0)
                                @foreach ($logs as $log)
                                    <div class="post border-bottom mb-3">
                                        <div class="user-block">
                                            <span class="description">{{ date('d F, Y h:i A', strtotime($log->created_at)) }}</span>
                                        </div>
                                        <p>
                                            {{ $log->activity }}
                                        </p>
                                    </div>
                                @endforeach
                            @endif
                            
                        </div>
                    
                        <div class="tab-pane" id="settings">
                            <form action="{{ route('admin.update.profile') }}" enctype="multipart/form-data" method="POST" class="update-profile-form">
                                @method('PATCH')
                                <div class="row">
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="avatar">Name</label>
                                        <input type="file" name="avatar" id="avatar" class="form-control dropify" data-default-file="{{ Auth::guard('admin')->user()->avatar ? asset(Auth::guard('admin')->user()->avatar) : asset('pictures/face.jpg') }}">
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="name">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ Auth::guard('admin')->user()->name }}" required>
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="designation">Designation <span class="text-danger">*</span></label>
                                        <input type="text" name="designation" id="designation" class="form-control" value="{{ Auth::guard('admin')->user()->designation }}" required>
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="phone">Phone <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" id="phone" class="form-control" value="{{ Auth::guard('admin')->user()->phone }}" required>
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="address">Address <span class="text-danger">*</span></label>
                                        <input type="text" name="address" id="address" class="form-control" value="{{ Auth::guard('admin')->user()->address }}" required>
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="area">Area <span class="text-danger">*</span></label>
                                        <input type="text" name="area" id="area" class="form-control" value="{{ Auth::guard('admin')->user()->area }}" required>
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="city">City <span class="text-danger">*</span></label>
                                        <input type="text" name="city" id="city" class="form-control" value="{{ Auth::guard('admin')->user()->city }}" required>
                                    </div>
                                    <div class="col-md-12 form-group text-center">
                                        <button type="submit" class="btn btn-outline-primary" id="update-profile-submit" >
                                            Submit 
                                        </button>
                                        <button style="display: none;" type="button" disabled class="btn btn-outline-primary" id="update-profile-submitting" >
                                            Processing... 
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
  
                        <div class="tab-pane" id="password">
                            <form action="{{ route('admin.update.password') }}" method="POST" class="update-password-form">
                                @method('PATCH')
                                <div class="row">
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="old_password">Old Password</label>
                                        <input type="text" name="old_password" id="old_password" class="form-control" required>
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="new_password">New Password <span class="text-danger">*</span></label>
                                        <input type="text" name="new_password" id="new_password" class="form-control" required>
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="new_password_confirmation">Retype Password <span class="text-danger">*</span></label>
                                        <input type="text" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                                    </div>
                                    <div class="col-md-12 form-group text-center">
                                        <button type="submit" class="btn btn-outline-primary" id="update-password-submit" >
                                            Update Password 
                                        </button>
                                        <button style="display: none;" type="button" disabled class="btn btn-outline-primary" id="update-password-submitting" >
                                            Processing... 
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
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/dropify.min.css') }}">
@endpush
@push('script')
    <script src="{{ asset('backend/assets/js/dropify.min.js') }}"></script>
    <script>
        $('.dropify').dropify({
            imgFileExtensions: ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp']
        });

        if ($('.update-profile-form').length > 0) {
            $('.update-profile-form').parsley().on('field:validated', function () {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            });
        }

        $('.update-profile-form').on('submit', function (e) {
            e.preventDefault();

            $('#update-profile-submit').hide();
            $('#update-profile-submitting').show();

            $(".ajax_error").remove();

            var submit_url = $('.update-profile-form').attr('action');
            var formData = new FormData($(".update-profile-form")[0]);

            //Start Ajax
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
                            for (const [key, messages] of Object.entries(data.message)) {
                                messages.forEach(message => {
                                    toastr.error(message);
                                });
                            }
                        } else {
                            toastr.error(data.message);
                        }

                        if (data.errors) {
                            for (const [key, message] of Object.entries(data.errors)) {
                                toastr.error(message);
                            }
                        }
                    } else {
                        toastr.success(data.message);
                    }

                    $('#update-profile-submit').show();
                    $('#update-profile-submitting').hide();
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

                    $('#update-profile-submit').show();
                    $('#update-profile-submitting').hide();
                }
            });
        });

        if ($('.update-password-form').length > 0) {
            $('.update-password-form').parsley().on('field:validated', function () {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            });
        }

        $('.update-password-form').on('submit', function (e) {
            e.preventDefault();

            $('#update-password-submit').hide();
            $('#update-password-submitting').show();

            $(".ajax_error").remove();

            var submit_url = $('.update-password-form').attr('action');
            var formData = new FormData($(".update-password-form")[0]);

            //Start Ajax
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
                            for (const [key, messages] of Object.entries(data.message)) {
                                messages.forEach(message => {
                                    toastr.error(message);
                                });
                            }
                        } else {
                            toastr.error(data.message);

                            if (data.goto) {
                                setTimeout(() => {
                                    window.location.href = data.goto;
                                }, 2000);
                            }
                        }

                        if (data.errors) {
                            for (const [key, message] of Object.entries(data.errors)) {
                                toastr.error(message);
                            }
                        }
                    } else {
                        toastr.success(data.message);
                    }

                    $('#update-password-submit').show();
                    $('#update-password-submitting').hide();
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

                    $('#update-password-submit').show();
                    $('#update-password-submitting').hide();
                }
            });
        });
        
    </script>
@endpush