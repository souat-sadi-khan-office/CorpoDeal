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
                    <a href="{{ route('admin.customer.view',$data->user->id) }}" class="btn btn-soft-success istiyak bw-2">
                        <i class="bi bi-arrow-bar-right"></i>
                        Customer Info
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="p-3 mb-3 row">

        <div class="col-md-12">
            <!-- Table row -->
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Currency</th>
                            <th>Amount</th>
                            <th>Installment</th>
                            <th>Document 1</th>
                            <th>Document 2</th>
                            <th>Additional Documents</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Updated By</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{$data->user->name}}</td>
                            <td class="bg-{{$data->is_declined?'danger':($data->is_approved?'success':'warning')}}">
                                {{$data->is_declined?'Declined':($data->is_approved?'Approved':'Pending')}}
                            </td>
                            <td>{{$data->currency->code}}
                                - {{$data->currency->symbol}}</td>
                            <td>{{$data->amount}}</td>
                            <td> {{$data->installmentPlan->name}}
                                - {{$data->installmentPlan->length}} Months
                                + {{$data->installmentPlan->extra_charge_percent}}%
                            </td>
                            <td class="text-center">
                                <a target="_blank"
                                   href="{{asset($data->document)}}">
                                    <i class="bi bi-file-binary-fill"></i>

                                </a>
                            </td>
                            <td class="text-center">
                                <a target="_blank"
                                   href="{{asset($data->document_2)}}">
                                    <i class="bi bi-file-earmark-ruled"></i>

                                </a>
                            </td>
                            <td class="text-center">
                                @if(json_decode($data->document_3) !== null)
                                    @foreach(json_decode($data->document_3) as $additional)
                                        <a class="mx-auto" target="_blank"
                                           href="{{asset($additional)}}">
                                            <i class="bi bi-file-earmark-ruled"></i>
                                        </a>
                                    @endforeach
                                @endif
                            </td>

                            <td>
                                {{get_system_date($data->created_at)}} {{get_system_time($data->created_at)}}
                            </td>
                            <td>
                                {{get_system_date($data->updated_at)}} {{get_system_time($data->updated_at)}}
                            </td>
                            <td>
                                {{$data->admin?$data->admin->name:"N/A"}}
                            </td>
                        </tr>

                        </tbody>
                    </table>
                    @if(isset($data->description))
                        <p>
                            Request Message: {{$data->description}}
                        </p>
                    @endif
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <div class="row">
            @if(!$data->is_declined && !$data->is_approved)

                <!-- accepted payments column -->
                    <div class="col-12">
                        <form method="POST" id="balance-form"
                              action="{{ route('admin.balance.request.update',$data->id) }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="status">Approve or Decline <span class="required">*</span></label>
                                    <select name="status" class="form-control" id="status" required>
                                        <option selected disabled>--Select--</option>
                                        <option value="approved" {{$data->is_approved?'selected':''}}>Approved</option>
                                        <option value="declined" {{$data->is_declined?'selected':''}}>Declined</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="installment_start">Assign next Installment Date</label>
                                    <input type="date" class="form-control" required name="installment_start"
                                           id="installment_start">

                                </div>
                            </div>

                            <label for="description">Additional Massage (if Any)</label>
                            <textarea name="description" id="description" class="form-control"
                                      cols="15" rows="7"></textarea>

                            <button type="submit" class="btn btn-primary btn-fill-out my-4" id="submit">Update
                            </button>
                            <button style="display: none;" class="btn btn-white" disabled
                                    id="submitting" type="button">
                                                    <span class="spinner-border spinner-border-sm" role="status"
                                                          aria-hidden="true"></span>
                                Loading...
                            </button>

                        </form>
                    </div>
            @endif

            <!-- /.col -->
                <div class="col-12">
                    <h4>Documents Preview:</h4>
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
    </script>
@endpush
