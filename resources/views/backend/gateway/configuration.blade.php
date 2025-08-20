

@extends('backend.layouts.app')
@section('title', 'Gateway Configuration | General')
@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/dropify.min.css') }}">
@endpush
@section('content')
    <div class="row mt-5">
        <div class="card">
            <div class="card-body">
                <p>"Please review all values carefully before proceeding. <span class="text-danger">Note: This action will directly modify the project's .env (Environment) file.</span>"</p>
                <div id="countdown" class="alert alert-warning"></div>

            </div>
        </div>

        <div class="col-lg-6 col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h1 class="h5 mb-0">Paypal</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.gateway.configuration.update', ['type' => 'paypal']) }}" method="POST"
                        enctype="multipart/form-data" class="content_form2">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 form-group mb-3">
                                <label for="system_name">PAYPAL CLIENT ID SANDBOX <span class="text-danger">*</span></label>
                                <input type="text" name="PAYPAL_CLIENT_ID_SANDBOX" class="form-control"
                                    value="{{ env('PAYPAL_CLIENT_ID_SANDBOX') }}" required>
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="system_name">PAYPAL SECRET SANDBOX<span class="text-danger">*</span></label>
                                <input type="text" name="PAYPAL_SECRET_SANDBOX" class="form-control"
                                    value="{{ env('PAYPAL_SECRET_SANDBOX') }}" required>
                            </div>
                            <div class="col-md-12 form-group mb-3">
                                <label for="system_name">PAYPAL CLIENT ID LIVE <span class="text-danger">*</span></label>
                                <input type="text" name="PAYPAL_CLIENT_ID_LIVE" class="form-control"
                                    value="{{ env('PAYPAL_CLIENT_ID_LIVE') }}" required>
                            </div>
                            <div class="col-md-12 form-group mb-3">
                                <label for="system_name">PAYPAL SECRET LIVE<span class="text-danger">*</span></label>
                                <input type="text" name="PAYPAL_SECRET_LIVE" class="form-control"
                                    value="{{ env('PAYPAL_SECRET_LIVE') }}" required>
                            </div>
                            <div class="col-md-12 form-group mb-3">
                                <label for="system_name">PAYPAL SANDBOX MODE<span class="text-danger">*</span></label>
                                <select name="PAYPAL_SANDBOX_MODE" id="PAYPAL_SANDBOX_MODE" class="form-control">
                                    <option value="true" {{ env('PAYPAL_SANDBOX_MODE') == true ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="false" {{ env('PAYPAL_SANDBOX_MODE') == false ? 'selected' : '' }}>
                                        Deactive
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-12 form-group text-end">
                                <button type="submit" id="submit2" class="btn btn-soft-success">
                                    <i class="bi bi-send"></i>
                                    Update
                                </button>
                                <button class="btn btn-soft-warning" style="display: none;" id="submitting2" type="button"
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
        <div class="col-lg-6 col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h1 class="h5 mb-0">SslCommerz</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.gateway.configuration.update', ['type' => 'sslcommerz']) }}"
                        method="POST" enctype="multipart/form-data" class="ajax_form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 form-group mb-3">
                                <label for="system_name">SSLCOMMERZ STORE ID <span class="text-danger">*</span></label>
                                <input type="text" name="SSLCOMMERZ_STORE_ID" class="form-control"
                                    value="{{ env('SSLCOMMERZ_STORE_ID') }}" required>
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="system_name">SSLCOMMERZ STORE PASSWORD<span class="text-danger">*</span></label>
                                <input type="text" name="SSLCOMMERZ_STORE_PASSWORD" class="form-control"
                                    value="{{ env('SSLCOMMERZ_STORE_PASSWORD') }}" required>
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="system_name">SSLCOMMERZ SANDBOX<span class="text-danger">*</span></label>
                                <select name="SSLCOMMERZ_SANDBOX" id="SSLCOMMERZ_SANDBOX" class="form-control">
                                    <option value="true" {{ env('SSLCOMMERZ_SANDBOX') == true ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="false" {{ env('SSLCOMMERZ_SANDBOX') == false ? 'selected' : '' }}>
                                        Deactive
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-12 form-group mb-3">
                                <label for="system_name">SSLCOMMERZ ALLOW LOCALHOST<span
                                        class="text-danger">*</span></label>
                                <select name="SSLCOMMERZ_ALLOW_LOCALHOST" id="SSLCOMMERZ_ALLOW_LOCALHOST"
                                    class="form-control">
                                    <option value="true"
                                        {{ env('SSLCOMMERZ_ALLOW_LOCALHOST') == true ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="false"
                                        {{ env('SSLCOMMERZ_ALLOW_LOCALHOST') == false ? 'selected' : '' }}>
                                        Deactive</option>
                                </select>
                            </div>
                            <div class="col-md-12 form-group text-end">
                                <button type="submit" id="submit" class="btn btn-soft-success">
                                    <i class="bi bi-send"></i>
                                    Update
                                </button>
                                <button class="btn btn-soft-warning" style="display: none;" id="submitting"
                                    type="button" disabled>
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
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
    <script>
        const countdownTime = 10; 
        let timeLeft = countdownTime;

        $('#submit, #submit2').prop('disabled', true);

        $('#countdown').text(`Please wait ${timeLeft} seconds...`).addClass('text-danger');

        const countdownInterval = setInterval(function() {
            timeLeft--;
            $('#countdown').text(`Please wait ${timeLeft} seconds...`).addClass('text-danger');

            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                $('#submit, #submit2').prop('disabled', false);
                $('#countdown').text("You can now submit the form.").removeClass('text-danger alert-warning').addClass('alert-primary');
            }
        }, 1000); 

        $('.content_form2').on('submit', async function(e) {
            e.preventDefault();

            $('#submit2').hide();
            $('#submitting2').show();
            $(".ajax_error").remove();

            const submit_url = $('.content_form2').attr('action');
            const formData = new FormData($(".content_form2")[0]);

            try {
                const response = await $.ajax({
                    url: submit_url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'JSON',
                });

                if (!response.status) {
                    if (response.validator) {
                        for (const messages of Object.values(response.message)) {
                            messages.forEach(message => toastr.error(message));
                        }
                    } else {
                        toastr.error(response.message);
                    }

                    if (response.errors) {
                        for (const message of Object.values(response.errors)) {
                            toastr.error(message);
                        }
                    }
                } else {
                    toastr.success(response.message);
                    $('.content_form2')[0].reset();
                    if (response.goto) {
                        setTimeout(function() {

                            window.location.href = data.goto;
                        }, 500);
                    }

                    if (response.load) {
                        setTimeout(function() {

                            window.location.href = "";
                        }, 500);
                    }
                }
            } catch (error) {
                if (error.responseJSON && error.responseJSON.errors) {
                    const errors = error.responseJSON.errors;
                    for (const [key, message] of Object.entries(errors)) {
                        toastr.error(message[0]);
                    }
                } else {
                    toastr.warning("An unexpected error occurred. Please try again.");
                }
            } finally {
                $('#submit2').show();
                $('#submitting2').hide();
            }
        });

        $('.ajax_form').on('submit', async function(e) {
            e.preventDefault();

            $('#submit').hide();
            $('#submitting').show();
            $(".ajax_error").remove();

            const submit_url = $('.ajax_form').attr('action');
            const formData = new FormData($(".ajax_form")[0]);

            try {
                const response = await $.ajax({
                    url: submit_url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'JSON',
                });

                if (!response.status) {
                    if (response.validator) {
                        for (const messages of Object.values(response.message)) {
                            messages.forEach(message => toastr.error(message));
                        }
                    } else {
                        toastr.error(response.message);
                    }

                    if (response.errors) {
                        for (const message of Object.values(response.errors)) {
                            toastr.error(message);
                        }
                    }
                } else {
                    toastr.success(response.message);
                    $('.content_form2')[0].reset();

                    if (response.goto) {
                        setTimeout(function() {

                            window.location.href = data.goto;
                        }, 500);
                    }

                    if (response.load) {
                        setTimeout(function() {

                            window.location.href = "";
                        }, 500);
                    }

                }
            } catch (error) {
                if (error.responseJSON && error.responseJSON.errors) {
                    const errors = error.responseJSON.errors;
                    for (const [key, message] of Object.entries(errors)) {
                        toastr.error(message[0]);
                    }
                } else {
                    toastr.warning("An unexpected error occurred. Please try again.");
                }
            } finally {
                $('#submit').show();
                $('#submitting').hide();
            }
        });
    </script>
@endpush
