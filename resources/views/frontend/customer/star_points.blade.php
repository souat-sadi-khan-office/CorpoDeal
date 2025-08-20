@extends('frontend.layouts.app', ['title' => 'My Points History | ', get_settings('system_name')])
@push('page_meta_information')
    
    <link rel="canonical" href="{{ route('home') }}" />
    <meta name="referrer" content="origin">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">

    <meta name="title" content="My Points History | {{ get_settings('system_name') }}">
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
                            My Star Points
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
                                    <h1 class="h5">My Points History</h1>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <th>Product</th>
                                                    <th>Points</th>
                                                    <th>Quantity</th>
                                                    <td>Method</td>
                                                </thead>
                                                <tbody>
                                                    @if (count($models))
                                                        @foreach ($models as $model)
                                                            <tr>
                                                                <td>
                                                                    @if ($model->product)
                                                                        <a href="{{ route('slug.handle', $model->product->slug) }}" target="_blank">
                                                                            <div class="row">
                                                                                <div class="col-auto">
                                                                                    <img width="50" height="50" src="{{ asset($model->product->thumb_image) }}" alt="{{ $model->product->name }}">
                                                                                </div>
                                                                                <div class="col">
                                                                                    {{ $model->product->name }}
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    @else   
                                                                        {{ $model->notes }}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    {{ $model->points }}
                                                                </td>
                                                                <td>
                                                                    {{ $model->quantity }}
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-{{ $model->method == 'plus' ? 'success' : 'danger'  }}">{{ $model->method == 'plus' ? 'Added' : 'Substract'  }}</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else    
                                                        <tr>
                                                            <td class="text-center" colspan="4R">Nothing to show.</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <ul class="pagination mt-3 pagination_style1">
                                                        @if ($models->onFirstPage())
                                                            <li class="page-item disabled"><span class="page-link">«</span></li>
                                                        @else
                                                            <li class="page-item"><a class="page-link" href="{{ $models->previousPageUrl() }}">«</a></li>
                                                        @endif
                                            
                                                        @for ($i = 1; $i <= $models->lastPage(); $i++)
                                                            <li class="page-item {{ $i === $models->currentPage() ? 'active' : '' }}">
                                                                <a class="page-link" href="{{ $models->url($i) }}">{{ $i }}</a>
                                                            </li>
                                                        @endfor
                                            
                                                        @if ($models->hasMorePages())
                                                            <li class="page-item"><a class="page-link" href="{{ $models->nextPageUrl() }}"> »</a></li>
                                                        @else
                                                            <li class="page-item disabled"><span class="page-link">»</span></li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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