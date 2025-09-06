@extends('backend.layouts.app')
@section('title', 'Balance Request Details')

@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="h3 mb-0">Balance Request Details</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Balance Request Details</li>
                    </ol>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ route('admin.customer.view',$data->user->id) }}" class="btn btn-sm btn-outline-dark">
                        <i class="bi bi-arrow-bar-right"></i>
                        Customer Info
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            
            <div class="card">
                <div class="card-header">
                    <h4 class="h6 mb-0">
                        <strong>Request Information</strong>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <td width="30%">Customer</td>
                                    <td>
                                        {{ $data->user->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">Status</td>
                                    <td class="bg-{{ $data->is_declined ? 'danger' : ( $data->is_approved ? 'success text-white' : 'warning') }}">
                                        <strong>{{ $data->is_declined ? 'Declined' : ($data->is_approved ? 'Approved' : 'Pending') }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">Currency</td>
                                    <td>
                                        {{ $data->currency->code }} - {{ $data->currency->symbol }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">Amount</td>
                                    <td>{{ $data->currency->code }} {{ number_format($data->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td width="30%">Installment Plan</td>
                                    <td> 
                                        {{ $data->installmentPlan->name }}
                                        - ({{ $data->installmentPlan->length }} Months)
                                        + {{ $data->installmentPlan->extra_charge_percent }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">Document 1</td>
                                    <td>
                                        <a style="color:#000;" target="_blank" href="{{ asset($data->document) }}">
                                            <i class="bi bi-file-binary-fill"></i>
                                            Preview
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">Document 2</td>
                                    <td>
                                        <a target="_blank" style="color:#000;" href="{{asset($data->document_2)}}">
                                            <i class="bi bi-file-earmark-ruled"></i>
                                            Preview
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">Additional Documents</td>
                                    <td>
                                        @if(json_decode($data->document_3) !== null)
                                            @foreach(json_decode($data->document_3) as $additional)
                                                <a class="mx-auto" target="_blank" style="color:#000;" href="{{asset($additional)}}">
                                                    <i class="bi bi-file-earmark-ruled"></i> Preview
                                                </a>
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">Created At</td>
                                    <td>
                                        {{ get_system_date($data->created_at) }} {{ get_system_time($data->created_at) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">Updated At</td>
                                    <td>
                                        @if ($data->created_at != $data->updated_at)
                                            {{ get_system_date($data->updated_at) }} {{ get_system_time($data->updated_at) }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">Updated By</td>
                                    <td>
                                        {{ $data->admin ? $data->admin->name : "N/A" }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">Request Message</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        {!! nl2br($data->description) !!}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                @if(!$data->is_declined && !$data->is_approved)
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="h6 mb-0">
                                    <strong>Update Request Information</strong>
                                </h4>
                            </div>
                            <div class="card-body">
                                <form method="POST" id="balance-form" action="{{ route('admin.balance.request.update',$data->id) }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="status">
                                                Approve or Decline 
                                                <span class="required">*</span>
                                            </label>
                                            <select name="status" class="form-control select" id="status" required data-placeholder="Select Any" data-minimum-results-for-search="Infinity" data-parsley-errors-container="#status_error">
                                                <option value="">Select Any</option>
                                                <option value="approved" {{ $data->is_approved ? 'selected' : '' }}>Approved</option>
                                                <option value="declined" {{ $data->is_declined ? 'selected' : '' }}>Declined</option>
                                            </select>
                                            <span id="status_error"></span>
                                        </div>
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="installment_start">
                                                Assign next Installment Date
                                            </label>
                                            <input type="date" class="form-control" required name="installment_start" id="installment_start">
                                        </div>

                                        <div class="col-md-12 form-group mb-3">
                                            <label for="description">Additional Massage (if Any)</label>
                                            <textarea name="description" id="description" class="form-control" cols="15" rows="7"></textarea>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-sm btn-dark btn-fill-out my-4" id="submit">
                                        <i class="bi bi-send" style="margin-right: 5px;"></i>
                                        Update 
                                    </button>
                                    <button style="display: none;" class="btn btn-sm btn-outline-dark" disabled id="submitting" type="button">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Loading...
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="h6 mb-0">
                                <strong>Documents Preview</strong>
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    {!! renderFile($data->document) !!}
                                </div>
                                <div class="col-md-4">
                                    {!! renderFile($data->document_2) !!}
                                </div>
                                <div class="col-md-4">
                                    @if(json_decode($data->document_3) !== null)
                                        @foreach(json_decode($data->document_3) as $additional)
                                            <div class="row">
                                                <div class="col-md-12">
                                                    {!! renderFile($additional) !!}
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p>No additional documents available.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dateInput = document.getElementById('installment_start');

            const today = new Date();

            today.setDate(today.getDate() + 3);
            const minDate = today.toISOString().split('T')[0];

            dateInput.setAttribute('min', minDate);
        });


        $(document).ready(function () {
            function toggleInstallmentInput() {
                if ($('#status').val() === 'declined') {
                    $('#installment_start').prop('disabled', true);
                    $('#installment_start').prop('required', false);
                } else {
                    $('#installment_start').prop('disabled', false);
                    $('#installment_start').prop('required', true);
                }
            }

            toggleInstallmentInput();

            $('#status').change(function () {
                toggleInstallmentInput();
            });
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
        _componentSelect();
    </script>
@endpush
