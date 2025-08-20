@extends('frontend.layouts.app', ['title' => 'Remove My Account | '. get_settings('system_name')])
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
                        Saved PC
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
                                <h1 class="h5 text-danger">For Removing your account, you will loosse</h1>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                      <strong>Account Benefits Reminder:</strong>
                                      <ul class="list-unstyled ms-3">
                                        <li>Exclusive discounts or rewards</li>
                                        <li>Loyalty points and account credits</li>
                                        <li>Saved wishlists and order history for easy reordering</li>
                                        <li>Personalized recommendations and offers</li>
                                      </ul>
                                    </li>
                                    <li class="list-group-item">
                                      <strong>Pending Orders and Returns:</strong>
                                      <ul class="list-unstyled ms-3">
                                        <li>Details about active orders</li>
                                        <li>Return status information</li>
                                      </ul>
                                    </li>
                                    <li class="list-group-item">
                                      <strong>Data to be Deleted:</strong>
                                      <ul class="list-unstyled ms-3">
                                        <li>Personal information (e.g., name, email, phone)</li>
                                        <li>Order history</li>
                                        <li>Saved addresses and payment methods</li>
                                        <li>Account preferences and settings</li>
                                      </ul>
                                    </li>
                                    <li class="list-group-item">
                                      <strong>Data to be Retained:</strong>
                                      <ul class="list-unstyled ms-3">
                                        <li>Order invoices for accounting and tax purposes</li>
                                        <li>Data necessary to comply with regulations</li>
                                      </ul>
                                    </li>
                                    <li class="list-group-item">
                                      <strong>Reactivation Policy:</strong>
                                      <p class="ms-3 mb-0">Explanation of how long the data will be retained before complete deletion (if applicable).</p>
                                    </li>
                                    <li class="list-group-item">
                                      <strong>Feedback Request:</strong>
                                      <p class="ms-3 mb-0">Optional survey asking for feedback on why they are leaving.</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 mt-3 mx-auto">
                        <div class="card card-body">
                            <form id="remove-account-form" method="POST" action="{{ route('account.delete') }}">
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <input type="password" name="password" id="password" class="form-control" required placeholder="Enter your password">
                                    </div>

                                    <div class="col-md-12 form-group mt-3">
                                        <button type="submit" class="btn btn-fill-out btn-sm btn-block" id="submit">Remove Account</button>
                                    </div>
                                    <div class="col-md-12 pb-0 mb-0 form-group mt-3">
                                        <button style="display: none;" class="btn btn-block btn-sm btn-dark" disabled id="submitting" type="button">
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
@endsection
@push('scripts')
    <script src="{{ asset('backend/assets/js/parsley.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/toastr.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/select2.min.js') }}"></script>
    <script>

        $('.select').select2({
            width: '100%'
        });

        var _formValidation = function () {
            if ($('#remove-account-form').length > 0) {
                $('#remove-account-form').parsley().on('field:validated', function () {
                    var ok = $('.parsley-error').length === 0;
                    $('.bs-callout-info').toggleClass('hidden', !ok);
                    $('.bs-callout-warning').toggleClass('hidden', ok);
                });
            }

            $('#remove-account-form').on('submit', function (e) {
                e.preventDefault();

                $('#submit').hide();
                $('#submitting').show();

                $(".ajax_error").remove();

                var submit_url = $('#remove-account-form').attr('action');
                var formData = new FormData($("#remove-account-form")[0]);

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
                        } else {
                            toastr.success(data.message);
                            
                            $('#remove-account-form')[0].reset();
                            if (data.goto) {
                                setTimeout(function () {
                                    window.location.href = data.goto;
                                }, 2500);
                            }
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