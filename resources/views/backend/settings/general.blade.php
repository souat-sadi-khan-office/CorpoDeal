@extends('backend.layouts.app')
@section('title', 'System Configuration | General')
@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/dropify.min.css') }}">
@endpush
@section('content')
    <div class="row mt-5">
        <div class="col-lg-7 col-md-7">
            <div class="card mb-4">
                <div class="card-header">
                    <h1 class="h5 mb-0">General Settings</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="content_form">
                        @csrf
                        <div class="row">

                            <div class="col-md-12 form-group mb-3">
                                <label for="">Select Default Laptop Category</label>
                                <select name="default_laptop_category" id="default_laptop_category" required
                                        class="form-control select">
                                    <option selected value="{{ get_settings('default_laptop_category') }}">Laptop
                                    </option>
                                </select>
                                <small class="text-muted">Please select the parent category for Laptop</small>
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="system_name">System Name <span class="text-danger">*</span></label>
                                <input type="text" name="system_name" class="form-control"
                                       value="{{ get_settings('system_name') }}" required>
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="pricing_tier">Pricing Tier Enabled?</label>
                                <select name="pricing_tier" id="pricing_tier" required class="form-control">
                                    <option value="1" {{ get_settings('pricing_tier') == 0 ? 'selected' : '' }}>
                                        Enabled
                                    </option>
                                    <option value="0" {{ get_settings('pricing_tier') == 0 ? 'selected' : '' }}>
                                        Disabled
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="primary_color">System Primary Color <span class="text-danger">*</span></label>
                                <input type="color" name="primary_color" style="height:50px" class="form-control"
                                       value="{{ get_settings('primary_color') }}" required>
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="system_logo_white">System Logo - White</label>
                                <input type="file" name="system_logo_white" id="system_logo_white"
                                       class="form-control dropify"
                                       data-default-file="{{ get_settings('system_logo_white') ? asset(get_settings('system_logo_white')) : asset('pictures/default-logo-white.png') }}">
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="system_logo_dark">System Logo - Black</label>
                                <input type="file" name="system_logo_dark" id="system_logo_dark"
                                       class="form-control dropify"
                                       data-default-file="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="system_favicon">Favicon</label>
                                <input type="file" name="system_favicon" id="system_favicon"
                                       class="form-control dropify"
                                       data-default-file="{{ get_settings('system_favicon') ? asset(get_settings('system_favicon')) : asset('pictures/default-favicon.png') }}">
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label for="system_timezone">Timezone </label>
                                <select name="system_timezone" id="system_timezone" class="form-control select">
                                    @foreach (tz_list() as $key => $time)
                                        <option {{ $time['zone'] == get_settings('system_timezone') ? 'selected' : '' }}
                                                value="{{ $time['zone'] }}">{{ $time['diff_from_GMT'] . ' - ' . $time['zone'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label for="system_date_format">Date Format</label>
                                <select name="system_date_format" id="system_date_format" class="form-control select"
                                        data-placeholder="Select Default Date Format">
                                    <option value="">Select Default Date Format</option>
                                    <option {{ get_settings('system_date_format') == 'd F, Y' ? 'selected' : '' }}
                                            value="d F, Y">DD MMM YYYY
                                    </option>
                                    <option {{ get_settings('system_date_format') == 'd-m-Y' ? 'selected' : '' }}
                                            value="d-m-Y">DD-MM-YYYY
                                    </option>
                                    <option {{ get_settings('system_date_format') == 'm-d-Y' ? 'selected' : '' }}
                                            value="m-d-Y">MM-DD-YYYY
                                    </option>
                                    <option {{ get_settings('system_date_format') == 'D-F-Y' ? 'selected' : '' }}
                                            value="D-F-Y">DD-MMM-YYYY
                                    </option>
                                    <option {{ get_settings('system_date_format') == 'F-d-Y' ? 'selected' : '' }}
                                            value="F-d-Y">MMM-DD-YYYY
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label for="system_time_format">Time Format</label>
                                <select name="system_time_format" id="system_time_format" class="form-control select"
                                        data-placeholder="Select Default Time Format">
                                    <option value="">Select Default Time Format</option>
                                    <option {{ get_settings('system_time_format') == 'H:i:s' ? 'selected' : '' }}
                                            value="H:i:s">24 Hour
                                    </option>
                                    <option {{ get_settings('system_time_format') == 'h:i:s' ? 'selected' : '' }}
                                            value="h:i:s">12 Hour
                                    </option>
                                    <option {{ get_settings('system_time_format') == 'h:i:s A' ? 'selected' : '' }}
                                            value="h:i:s A">12 Hour Meridiem
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label for="system_notification_format">Notification Position</label>
                                <select name="system_notification_format" id="system_notification_format"
                                        class="form-control select"
                                        data-placeholder="Select Default Notification Position ">
                                    <option value="">Select Default Notification Position</option>
                                    <option
                                        {{ get_settings('system_notification_format') == 'toast-top-right' ? 'selected' : '' }}
                                        value="toast-top-right">Top Right
                                    </option>
                                    <option
                                        {{ get_settings('system_notification_format') == 'toast-top-left' ? 'selected' : '' }}
                                        value="toast-top-left">Top Left
                                    </option>
                                    <option
                                        {{ get_settings('system_notification_format') == 'toast-top-full-width' ? 'selected' : '' }}
                                        value="toast-top-full-width">Top Full Width
                                    </option>
                                    <option
                                        {{ get_settings('system_notification_format') == 'toast-top-center' ? 'selected' : '' }}
                                        value="toast-top-center">Top Center
                                    </option>
                                    <option
                                        {{ get_settings('system_notification_format') == 'toast-bottom-right' ? 'selected' : '' }}
                                        value="toast-bottom-right">Bottom Right
                                    </option>
                                    <option
                                        {{ get_settings('system_notification_format') == 'toast-bottom-left' ? 'selected' : '' }}
                                        value="toast-bottom-left">Bottom Left
                                    </option>
                                    <option
                                        {{ get_settings('system_notification_format') == 'toast-bottom-full-width' ? 'selected' : '' }}
                                        value="toast-bottom-full-width">Bottom Full Width
                                    </option>
                                    <option
                                        {{ get_settings('system_notification_format') == 'toast-bottom-center' ? 'selected' : '' }}
                                        value="toast-bottom-center">Bottom Center
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-12 form-group text-end">
                                <button type="submit" id="submit" class="btn btn-soft-success">
                                    <i class="bi bi-send"></i>
                                    Update
                                </button>
                                <button class="btn btn-soft-warning" style="display: none;" id="submitting"
                                        type="button"
                                        disabled>
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

        <div class="col-md-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h2 class="h5 mb-0">Premium Users</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.settings.update') }}" method="POST" class="ajax_form_premium_user">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="premium_option_module_enabled">Premium Users Module Enabled <span class="text-danger">*</span></label>
                                        <select name="premium_option_module_enabled" id="premium_option_module_enabled" class="form-control select" >
                                            <option {{ get_settings('premium_option_module_enabled') == 0 ? 'selected' : '' }} value="0">Disabled</option>
                                            <option {{ get_settings('premium_option_module_enabled') == 1 ? 'selected' : '' }} value="1">Enabled</option>
                                        </select>
                                    </div>

                                    <div class="col-md-12 mb-3 form-group">
                                        <label for="premium_user_discount_type">Discount Type <span class="text-danger">*</span></label>
                                        <select name="premium_user_discount_type" id="premium_user_discount_type" class="form-control select">
                                            <option {{ get_settings('premium_user_discount_type') == 'flat' ? 'selected' : '' }} value="flat">Flat</option>
                                            <option {{ get_settings('premium_user_discount_type') == 'percent' ? 'selected' : '' }} value="percent">Percent</option>
                                        </select>
                                    </div>

                                    <div class="col-md-12 mb-3 form-group">
                                        <label for="premium_user_discount_amount">Discount Amount <span class="text-danger">* </span></label>
                                        <input type="text" name="premium_user_discount_amount" id="premium_user_discount_amount" class="form-control number" required value="{{ round(covert_to_defalut_currency(get_settings('premium_user_discount_amount')),2) }}">
                                    </div>

                                    <div class="col-md-12 form-group text-end">
                                        <button type="submit" id="premium_user_submit" class="btn btn-soft-success">
                                            <i class="bi bi-send"></i>
                                            Update
                                        </button>
                                        <button class="btn btn-soft-warning" style="display: none;" id="premium_user_submitting"
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

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h2 class="h5 mb-0">Current Default : {{ @get_system_default_currency()->name }}</h2>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning">
                                {{ format_price(2500, false, true) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h2 class="h5 mb-0">System Activity Deletion</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.settings.update') }}" method="POST"
                                  enctype="multipart/form-data" class="ajax_form_Activity">
                                @csrf
                                <div class="row">
                                    <label for="activity_log_delete_day">Auto Activity Log Data Deletion After <span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-12 form-group input-group mb-3">

                                        <input type="number" name="activity_log_delete_day" id="activity_log_delete_day"
                                               value="{{get_settings('activity_log_delete_day')}}"
                                               class="form-control">
                                        <span class="input-group-text" id="activity_log_delete_day">Days</span>
                                    </div>

                                    <div class="col-md-12 form-group text-end">
                                        <button type="submit" id="Activity" class="btn btn-soft-success">
                                            <i class="bi bi-send"></i>
                                            Update
                                        </button>
                                        <button class="btn btn-soft-warning" style="display: none;" id="ActivityLOG"
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
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h2 class="h5 mb-0">System Default Currency</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.settings.update') }}" method="POST"
                                  enctype="multipart/form-data" class="ajax_form">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="system_default_currency">System Default Currency <span
                                                class="text-danger">*</span></label>
                                        <select name="system_default_currency" id="systeem_default_currency"
                                                class="form-control select" data-placeholder="Select Country">
                                            <option value="">Select Currency</option>
                                            @foreach ($currencies as $currency)
                                                <option
                                                    {{ get_settings('system_default_currency') == $currency->id ? 'selected' : '' }}
                                                    value="{{ $currency->id }}">{{ $currency->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-12 form-group text-end">
                                        <button type="submit" id="submit_one" class="btn btn-soft-success">
                                            <i class="bi bi-send"></i>
                                            Update
                                        </button>
                                        <button class="btn btn-soft-warning" style="display: none;" id="submitting_one"
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
                {{-- <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h2 class="h5 mb-0">System Default Delivery Charge</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.settings.update') }}" method="POST"
                                  enctype="multipart/form-data" class="ajax_form_DeliveryCharge">
                                @csrf
                                <div class="row">


                                    <div class="col-md-12 form-group text-end">
                                        <button type="submit" id="DeliveryCharge" class="btn btn-soft-success">
                                            <i class="bi bi-send"></i>
                                            Update
                                        </button>
                                        <button class="btn btn-soft-warning" style="display: none;"
                                                id="submitting_DeliveryCharge" type="button" disabled>
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                  aria-hidden="true"></span>
                                            Loading...
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div> --}}

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h2 class="h5 mb-0">Set Currency Formats</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.settings.update') }}" method="POST"
                                  enctype="multipart/form-data" class="ajax_form_one">
                                @csrf
                                <div class="row">

                                    <div class="col-md-12 form-group mb-3">
                                        <label for="system_symbol_format">Symbol Format</label>
                                        <select name="system_symbol_format" id="system_symbol_format"
                                                class="form-control select" data-placeholder="Select Format">
                                            <option value="">Select Format</option>
                                            <option
                                                {{ get_settings('system_symbol_format') == '[Symbol][Amount]' ? 'selected' : '' }}
                                                value="[Symbol][Amount]">[Symbol][Amount]
                                            </option>
                                            <option
                                                {{ get_settings('system_symbol_format') == '[Amount][Symbol]' ? 'selected' : '' }}
                                                value="[Amount][Symbol]">[Amount][Symbol]
                                            </option>
                                            <option
                                                {{ get_settings('system_symbol_format') == '[Symbol] [Amount]' ? 'selected' : '' }}
                                                value="[Symbol] [Amount]">[Symbol] [Amount]
                                            </option>
                                            <option
                                                {{ get_settings('system_symbol_format') == '[Amount] [Symbol]' ? 'selected' : '' }}
                                                value="[Amount] [Symbol]">[Amount] [Symbol]
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-12 form-group mb-3">
                                        <label for="system_decimal_separator">Decimal Separator</label>
                                        <select name="system_decimal_separator" id="system_decimal_separator"
                                                class="form-control select" data-placeholder="Select Separator">
                                            <option value="">Select Separator</option>
                                            <option
                                                {{ get_settings('system_decimal_separator') == 1 ? 'selected' : '' }}
                                                value="1">1,23,456.78
                                            </option>
                                            <option
                                                {{ get_settings('system_decimal_separator') == 2 ? 'selected' : '' }}
                                                value="2">1.23.456,78
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-12 form-group mb-3">
                                        <label for="system_no_of_decimals">No of decimals</label>
                                        <select name="system_no_of_decimals" id="system_no_of_decimals"
                                                class="form-control select" data-placeholder="Select Number">
                                            <option value="">Select Number</option>
                                            <option {{ get_settings('system_no_of_decimals') == 0 ? 'selected' : '' }}
                                                    value="0">100
                                            </option>
                                            <option {{ get_settings('system_no_of_decimals') == 1 ? 'selected' : '' }}
                                                    value="1">100.1
                                            </option>
                                            <option {{ get_settings('system_no_of_decimals') == 2 ? 'selected' : '' }}
                                                    value="2">100.22
                                            </option>
                                            <option {{ get_settings('system_no_of_decimals') == 3 ? 'selected' : '' }}
                                                    value="3">100.333
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-12 form-group mb-3">
                                        <label for="currency_api_fetch_time">Exchange Rate Refresh Time (API)</label>
                                        <select name="currency_api_fetch_time" id="currency_api_fetch_time"
                                                class="form-control select" data-placeholder="Select Number">
                                            <option value="">Select Time</option>
                                            <option
                                                {{ get_settings('currency_api_fetch_time') == 60 ? 'selected' : '' }}
                                                value="60">1 Minute
                                            </option>
                                            <option
                                                {{ get_settings('currency_api_fetch_time') == 1800 ? 'selected' : '' }}
                                                value="1800">30 Minute
                                            </option>
                                            <option
                                                {{ get_settings('currency_api_fetch_time') == 3600 ? 'selected' : '' }}
                                                value="3600">1 Hour
                                            </option>
                                            <option
                                                {{ get_settings('currency_api_fetch_time') == 21600 ? 'selected' : '' }}
                                                value="21600">6 Hour
                                            </option>
                                            <option
                                                {{ get_settings('currency_api_fetch_time') == 43200 ? 'selected' : '' }}
                                                value="43200">12 Hour
                                            </option>
                                            <option
                                                {{ get_settings('currency_api_fetch_time') == 86400 ? 'selected' : '' }}
                                                value="86400">Per Day
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-12 form-group text-end">
                                        <button type="submit" id="submit_two" class="btn btn-soft-success">
                                            <i class="bi bi-send"></i>
                                            Update
                                        </button>
                                        <button class="btn btn-soft-warning" style="display: none;" id="submitting_two"
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

        $('#default_laptop_category').select2({
            width: '100%',
            placeholder: 'Select category',
            ajax: {
                url: '/search/category/parent',
                method: 'POST',
                dataType: 'JSON',
                delay: 250,
                cache: true,
                data: function (data) {
                    return {
                        searchTerm: data.term
                    };
                },

                processResults: function (response) {
                    return {
                        results: response
                    };
                }
            }
        });

        var _formValidation1 = function () {
            if ($('.ajax_form').length > 0) {
                $('.ajax_form').parsley().on('field:validated', function () {
                    var ok = $('.parsley-error').length === 0;
                    $('.bs-callout-info').toggleClass('hidden', !ok);
                    $('.bs-callout-warning').toggleClass('hidden', ok);
                });
            }

            $('.ajax_form').on('submit', function (e) {
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

                            $('.ajax_form')[0].reset();
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

                            if (data.load) {
                                setTimeout(function () {

                                    window.location.href = "";
                                }, 1000);
                            }
                        }

                        $('#submit_one').show();
                        $('#submitting_one').hide();
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

                        $('#submit_one').show();
                        $('#submitting_one').hide();
                    }
                });
            });
        };

        $('.ajax_form_Activity').on('submit', function (e) {
            e.preventDefault();

            $('#Activity').hide();
            $('#ActivityLOG').show();

            $(".ajax_error").remove();

            var submit_url = $('.ajax_form_Activity').attr('action');
            var formData = new FormData($(".ajax_form_Activity")[0]);

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

                        $('.ajax_form_Activity')[0].reset();
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

                        if (data.load) {
                            setTimeout(function () {

                                window.location.href = "";
                            }, 1000);
                        }
                    }

                    $('#Activity').show();
                    $('#ActivityLOG').hide();
                },

            });
        });
        $('.ajax_form_DeliveryCharge').on('submit', function (e) {
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

                        $('.ajax_form_DeliveryCharge')[0].reset();
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

                        if (data.load) {
                            setTimeout(function () {

                                window.location.href = "";
                            }, 1000);
                        }
                    }

                    $('#DeliveryCharge').show();
                    $('#submitting_DeliveryCharge').hide();
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

                    $('#DeliveryCharge').show();
                    $('#submitting_DeliveryCharge').hide();
                }
            });
        });
        _formValidation1();

        var _formValidation2 = function () {
            if ($('.ajax_form_one').length > 0) {
                $('.ajax_form_one').parsley().on('field:validated', function () {
                    var ok = $('.parsley-error').length === 0;
                    $('.bs-callout-info').toggleClass('hidden', !ok);
                    $('.bs-callout-warning').toggleClass('hidden', ok);
                });
            }

            $('.ajax_form_one').on('submit', function (e) {
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

                            $('.ajax_form_one')[0].reset();
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

                            if (data.load) {
                                setTimeout(function () {

                                    window.location.href = "";
                                }, 1000);
                            }
                        }

                        $('#submit_two').show();
                        $('#submitting_two').hide();
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

                        $('#submit_two').show();
                        $('#submitting_two').hide();
                    }
                });
            });
        };

        _formValidation2();

        var _formValidation7 = function () {
            if ($('.ajax_form_premium_user').length > 0) {
                $('.ajax_form_premium_user').parsley().on('field:validated', function () {
                    var ok = $('.parsley-error').length === 0;
                    $('.bs-callout-info').toggleClass('hidden', !ok);
                    $('.bs-callout-warning').toggleClass('hidden', ok);
                });
            }

            $('.ajax_form_premium_user').on('submit', function (e) {
                e.preventDefault();

                $('#premium_user_submit').hide();
                $('#premium_user_submitting').show();

                $(".ajax_error").remove();

                var submit_url = $('.ajax_form_premium_user').attr('action');
                var formData = new FormData($(".ajax_form_premium_user")[0]);

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

                            $('.ajax_form_premium_user')[0].reset();
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

                            if (data.load) {
                                setTimeout(function () {

                                    window.location.href = "";
                                }, 1000);
                            }
                        }

                        $('#premium_user_submit').show();
                        $('#premium_user_submitting').hide();
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

                        $('#premium_user_submit').show();
                        $('#premium_user_submitting').hide();
                    }
                });
            });
        };

        _formValidation7();
    </script>
@endpush
