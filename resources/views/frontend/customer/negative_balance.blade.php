@extends('frontend.layouts.app', ['title', 'Negative Balance | '. get_settings('system_name')])
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
                            Password Manager
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
                            @if(isset($history) && count($history))
                                <div class="card">
                                    <div class="card-header">
                                        <h3> My Previous Requests</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="accordion" id="accordionExample">
                                            @foreach($history as $data)
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="heading{{$data->id}}">
                                                        <button class="accordion-button" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#collapse{{$data->id}}"
                                                                aria-expanded="true"
                                                                aria-controls="collapse{{$data->id}}">

                                                        <span class="mx-2">
                                                            Amount: {{$data->currency->symbol}}{{$data->amount}}
                                                        </span>
                                                            <span class="mx-2">
                                                            Installment:  {{$data->installmentPlan->name}}
                                                        - {{$data->installmentPlan->length}} Months
                                                        + {{$data->installmentPlan->extra_charge_percent}}%
                                                        </span>

                                                            <span
                                                                class="mx-auto badge bg-{{$data->is_declined?'danger':($data->is_approved?'success':'warning')}}">
                                                        {{$data->is_declined?'Declined':($data->is_approved?'Approved':'Pending')}}
                                                    </span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse{{$data->id}}" class="accordion-collapse collapse"
                                                         aria-labelledby="heading{{$data->id}}"
                                                         data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <table class="table">
                                                                <thead>
                                                                <tr>
                                                                    <th scope="col">Currency</th>
                                                                    <th scope="col">Amount</th>
                                                                    <th scope="col">Document 1</th>
                                                                    <th scope="col">Document 2</th>
                                                                    <th scope="col">Additional Documents</th>
                                                                    <th scope="col">Requested At</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <th scope="row">{{$data->currency->code}}
                                                                        - {{$data->currency->symbol}}</th>
                                                                    <td>{{$data->amount}}</td>
                                                                    <td class="text-center">
                                                                        <a target="_blank"
                                                                           href="{{asset($data->document)}}">
                                                                            <i class="fas fa-solid fa-file"></i>
                                                                        </a>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <a target="_blank"
                                                                           href="{{asset($data->document_2)}}">
                                                                            <i class="fas fa-solid fa-file"></i>
                                                                        </a>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if(json_decode($data->document_3) !== null)
                                                                            @foreach(json_decode($data->document_3) as $additional)
                                                                                <a class="mx-auto" target="_blank"
                                                                                   href="{{asset($additional)}}">
                                                                                    <i class="fas fa-solid fa-file"></i>
                                                                                </a>
                                                                            @endforeach
                                                                        @endif
                                                                    </td>

                                                                    <td>
                                                                        {{get_system_date($data->created_at)}} {{get_system_time($data->created_at)}}
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>

                                                            <p>
                                                                {{$data->description}}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                        @if ($history->hasMorePages() || !$history->onFirstPage())
                                            @include('frontend.components.paginate',['products'=>$history])
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <div class="card">
                                <div class="card-header">
                                    <h4>Apply Negative Balance Request</h4>
                                </div>
                                <div class="card-body">
                                    <p>Please Fill The Details for Applying Negative balance. <br> <span
                                            style="font-size: 80%">Documents File must be Under 500KB Each.</span></p>

                                    <form method="POST" id="balance-form"
                                          action="{{ route('account.negative.balance.store') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group col-md-12 mb-3">
                                                    <label for="currency">Select Currency <span
                                                            class="required">*</span></label>
                                                    <select name="currency_id" id="currency" class="form-control">
                                                        @foreach($currencies as $currency)
                                                            <option value="{{$currency->id}}"
                                                                    data-symbol="{{$currency->symbol}}"
                                                                {{ session('currency_code') == $currency->code ? 'selected' : '' }}>
                                                                {{$currency->code}} - {{$currency->symbol}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <label>Amount <span class="required">*</span></label>
                                                <div class="input-group col-md-12 mb-3">
                                                    <input required class="form-control" name="amount" type="number"
                                                           aria-describedby="Amount">
                                                    <span class="input-group-text" id="Amount"></span>
                                                </div>

                                                <div class="form-group col-md-12 mb-3">
                                                    <label for="installment">Select Installment Plans <span
                                                            class="required">*</span></label>
                                                    <select name="installment_plan_id" id="installment"
                                                            class="form-control">
                                                        @foreach($plans as $plan)
                                                            <option value="{{$plan->id}}">
                                                                {{$plan->name}} - {{$plan->length}} Months
                                                                + {{$plan->extra_charge_percent}}%
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-12 mb-3">
                                                    <label>Document <span style="font-size: 70%">(NID/Passport/TIN/Bank Statement etc.)</span><span
                                                            class="required">*</span></label>
                                                    <input required class="form-control" name="document"
                                                           type="file">
                                                </div>
                                                <div class="form-group col-md-12 mb-3">
                                                    <label>Document <span style="font-size: 70%">(Increase Chance for approval with more Documents)</span><span
                                                            class="required">*</span></label>
                                                    <input class="form-control" name="document_2"
                                                           type="file">
                                                </div>


                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group col-md-12 mb-4">
                                                    <label for="description">Request Description/Reason <span
                                                            class="required">*</span></label>
                                                    <textarea name="description" id="description" class="form-control"
                                                              cols="30" rows="12" required></textarea>
                                                </div>
                                                <div class="form-group col-md-12 mb-3">
                                                    <label>Additional Documents <span style="font-size: 70%">(You Can Upload Multiple Here)</span></label>
                                                    <input class="form-control" name="document_3[]"
                                                           type="file" multiple>
                                                </div>
                                            </div>
                                            <div class="col-md-12 form-group mb-3">
                                                <button type="submit" class="btn btn-fill-out" id="submit">Apply
                                                </button>
                                            </div>
                                            <div class="col-md-12 form-group mb-3">
                                                <button style="display: none;" class="btn btn-dark" disabled
                                                        id="submitting" type="button">
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
        </div>
    </div>
@endsection
@push('scripts')
    <script>

        $(document).ready(function () {
            function updateSymbol() {
                const selectedOption = $('#currency option:selected');
                const symbol = selectedOption.data('symbol');
                $('#Amount').text(symbol);
            }

            $('#currency').on('change', function () {
                updateSymbol();
            });

            updateSymbol();
        });

        var _formValidation = function () {
            if ($('#balance-form').length > 0) {
                $('#balance-form').parsley().on('field:validated', function () {
                    var ok = $('.parsley-error').length === 0;
                    $('.bs-callout-info').toggleClass('hidden', !ok);
                    $('.bs-callout-warning').toggleClass('hidden', ok);
                });
            }

            $('#balance-form').on('submit', function (e) {
                e.preventDefault();

                $('#submit').hide();
                $('#submitting').show();

                $(".ajax_error").remove();

                var submit_url = $('#balance-form').attr('action');
                var formData = new FormData($("#balance-form")[0]);

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

                            $('#balance-form')[0].reset();
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
