@extends('frontend.layouts.app')
@section('title', 'Login ', get_settings('system_name'))

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
                        <li class="breadcrumb-item">
                            <a href="{{ route('account.address-book.index') }}">
                                Address Book
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Update Address Information
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

                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h1 style="margin-top: 10px;" class="h5">Update Address Information </h1>
                                    </div>
                                    <div class="col-md-4 text-end">  
                                        <a href="{{ route('account.phone-book.index') }}" class="btn btn-sm px-3 py-2 btn-fill-out">
                                            Back
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('account.address-book.update', $model->id) }}" method="POST" id="phone-book-form">
                                    @csrf
                                    @method('PATCH')
                                    <div class="row">
                                        <!-- first_name -->
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="first_name">First Name <span class="text-danger">*</span></label>
                                            <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" required value="{{ $model->first_name }}">
                                        </div>

                                        <!-- last_name -->
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" required value="{{ $model->last_name }}">
                                        </div>

                                        <!-- company -->
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="company">Company</label>
                                            <input type="text" name="company_name" id="company" class="form-control" placeholder="Company" value="{{ $model->company_name }}">
                                        </div>

                                        <!-- address -->
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="address">Address 1 <span class="text-danger">*</span></label>
                                            <input type="text" name="address" id="address" class="form-control" placeholder="Address 1" required value="{{ $model->address }}">
                                        </div>

                                        <!-- address_2 -->
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="address_2">Address 2</label>
                                            <input type="text" name="address_line_2" id="address_2" class="form-control" placeholder="Address 2" value="{{ $model->address_line_2 }}">
                                        </div>

                                        <!-- zone -->
                                        <input type="hidden" name="zone_id" value="{{ $model->zone_id }}">
                                        <input type="hidden" name="country_id" value="{{ $model->country_id }}">
                                        {{-- <div class="col-md-6 form-group mb-3">
                                            <label for="zone_id">Zone/Contenent <span class="text-danger">*</span></label>
                                            <select name="zone_id" id="zone_id" class="form-control select" data-parsley-errors-container="#zone_id_error" required data-placeholder="Select Zone">
                                                <option value="">Select Zone</option>
                                                @foreach ($zones as $zone)
                                                    <option {{ $zone->id == $model->zone_id ? 'selected' : '' }} value="{{ $zone->id }}">{{ $zone->name }}</option>
                                                @endforeach
                                            </select>
                                            <span id="zone_id_error"></span>
                                        </div> --}}

                                        <!-- country_id -->
                                        {{-- <div class="col-md-6 form-group mb-3">
                                            <label for="country_id">Country <span class="text-danger">*</span></label>
                                            <select name="country_id" id="country_id" class="form-control select" data-parsley-errors-container="#country_id_error" required data-placeholder="Select Country">
                                                <option value="">Select Country</option>
                                                @foreach ($countries as $country)
                                                    <option {{ $country->id == $model->country_id ? 'selected' : '' }} value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                            <span id="country_id_error"></span>
                                        </div> --}}

                                        <!-- city_id -->
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="city_id">City <span class="text-danger">*</span></label>
                                            <select name="city_id" id="city_id" class="form-control select" data-parsley-errors-container="#city_id_error" required data-placeholder="Select City">
                                                <option value="">Select City</option>
                                                @foreach ($cities as $city)
                                                    <option {{ $city->id == $model->city_id ? 'selected' : ''}} value="{{ $city->id }}">{{ $city->name }}</option>
                                                @endforeach
                                            </select>
                                            <span id="city_id_error"></span>
                                        </div>

                                        <!-- area -->
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="area">Area <span class="text-danger">*</span></label>
                                            <input type="text" name="area" id="area" class="form-control" required placeholder="Area" value="{{ $model->area }}">
                                        </div>

                                        <!-- postcode -->
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="postcode">Postcode <span class="text-danger">*</span></label>
                                            <input type="text" name="postcode" id="postcode" class="form-control" required placeholder="Postcode" value="{{ $model->postcode }}">
                                        </div>

                                        <!-- is_default -->
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="is_default">Default Address <span class="text-danger">*</span></label>
                                            <select name="is_default" id="is_default" class="form-control select" required>
                                                <option {{ $model->is_default == 1 ? 'selected' : '' }} value="1">Yes</option>
                                                <option {{ $model->is_default == 0 ? 'selected' : '' }} value="0">No</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-12 form-group mb-3">
                                            <button type="submit" class="btn btn-fill-out btn-sm" id="submit">Update</button>
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <button style="display: none;" class="btn btn-sm btn-dark" disabled id="submitting" type="button">
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

        $('.select').select2({
            width: '100%'
        });

        var _formValidation = function () {
            if ($('#phone-book-form').length > 0) {
                $('#phone-book-form').parsley().on('field:validated', function () {
                    var ok = $('.parsley-error').length === 0;
                    $('.bs-callout-info').toggleClass('hidden', !ok);
                    $('.bs-callout-warning').toggleClass('hidden', ok);
                });
            }

            $('#phone-book-form').on('submit', function (e) {
                e.preventDefault();

                $('#submit').hide();
                $('#submitting').show();

                $(".ajax_error").remove();

                var submit_url = $('#phone-book-form').attr('action');
                var formData = new FormData($("#phone-book-form")[0]);

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
                            
                            $('#phone-book-form')[0].reset();
                            if (data.goto) {
                                setTimeout(function () {
                                    window.location.href = data.goto;
                                }, 500);
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

        $(document).on('change', '#zone_id', function() {
            var zoneId = $(this).val();
            if (zoneId) {
                $.ajax({
                    url: "{{ route('getCountries') }}",
                    type: "GET",
                    data: { zone_id: zoneId },
                    success: function(data) {
                        $('#country_id').empty();
                        $('#country_id').append('<option value="">Select Country</option>');
                        $.each(data, function(key, country) {
                            $('#country_id').append('<option value="'+ country.id +'">'+ country.name +'</option>');
                        });
                        $('#country_id').trigger('change');
                    }
                });
            } else {
                $('#country_id').empty();
                $('#country_id').append('<option value="">Select Country</option>');
            }
        });

        $(document).on('change', '#country_id', function() {
            var countryId = $(this).val();
            if (countryId) {
                $.ajax({
                    url: "{{ route('getCities') }}",
                    type: "GET",
                    data: { country_id: countryId },
                    success: function(data) {
                        $('#city_id').empty();
                        $('#city_id').append('<option value="">Select City</option>');
                        $.each(data, function(key, city) {
                            $('#city_id').append('<option value="'+ city.id +'">'+ city.name +'</option>');
                        });
                        $('#city_id').trigger('change');
                    }
                });
            } else {
                $('#city_id').empty();
                $('#city_id').append('<option value="">Select City</option>');
            }
        });
    </script>
@endpush