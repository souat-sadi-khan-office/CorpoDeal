@extends('backend.layouts.app', ['title' => 'Update Pricing Tier information'])
@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="h3 mb-0">Update Pricing Tier Information</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.pricing-tier.index') }}">Pricing Tier Management</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Update tier information</li>
                    </ol>
                </div>

                <div class="col-sm-6 text-end">
                    @if (Auth::guard('admin')->user()->hasPermissionTo('pricing-tier.update'))
                        <a href="{{ route('admin.pricing-tier.index') }}" class="btn btn-soft-danger">
                            <i class="bi bi-backspace"></i>
                            Back
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/tempus-dominus.min.css') }}">
@endpush
@section('content')
    <form action="{{ route('admin.pricing-tier.update', $model->id) }}" enctype="multipart/form-data" class="content_form" method="post">
        @method('PATCH')
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0">Tier Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-12 form-group mb-3">
                                <label for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" required value="{{ $model->name }}">
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="currency_id">Currency <span class="text-danger">*</span></label>
                                <select name="currency_id" id="currency_id" class="form-control select" required data-parsley-errors-container="#currency_id_error" data-placeholder="Select one">
                                    <option value="">Select one</option>
                                    @foreach ($currencies as $currency)
                                        <option {{ $model->currency_id == $currency->id ? 'selected' : '' }} value="{{ $currency->id }}">{{ $currency->name }}</option>
                                    @endforeach
                                </select>
                                <span id="currency_id_error"></span>
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label for="discount_type">Discount Type <span class="text-danger">*</span></label>
                                <select name="discount_type" id="discount_type" class="form-control">
                                    <option {{ $model->discount_type == 'flat' ? 'selected' : '' }} value="flat">Flat</option>
                                    <option {{ $model->discount_type == 'percent' ? 'selected' : '' }} value="percent">Percent</option>
                                </select>
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label for="discount_amount">Discount Amount <span class="text-danger">*</span></label>
                                <input type="text" name="discount_amount" id="discount_amount" class="form-control number" required value="{{ round($model->discount_amount, 0) }}">
                            </div>
                            
                            <div class="col-md-6 form-group mb-3">
                                <label for="threshold">Threshold Amount <span class="text-danger">*</span></label>
                                <input type="text" name="threshold" id="threshold" class="form-control number" required value="{{ round($model->threshold, 0) }}">
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label for="usage_limit">Maximum Number Of Usage <span class="text-danger">*</span></label>
                                <input type="text" name="usage_limit" id="usage_limit" class="form-control number" required value="{{ $model->usage_limit }}">
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label for="with_product_tax">With Product Tax? <span class="text-danger">*</span></label>
                                <select name="with_product_tax" id="with_product_tax" class="form-control">
                                    <option {{ $model->with_product_tax == 'no' ? 'selected' : '' }} value="no">No</option>
                                    <option {{ $model->with_product_tax == 'yes' ? 'selected' : '' }} value="yes">Yes</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4 form-group mb-3">
                                <label for="applicable_to">Applicable To <span class="text-danger">*</span></label>
                                <select name="applicable_to" id="applicable_to" class="form-control">
                                    <option value="full_order" selected>Full Order</option>
                                    <option value="single_product" disabled>Single Product</option>
                                    <option value="multi_product" disabled>Multiple Product</option>
                                </select>
                            </div>

                            <div class="col-sm-6 form-group mb-3" id="htmlTarget">
                                <label for="start_date" class="form-label">Start date</label>
                                <input id="start_date" value="{{ $model->start_date ? date('m/d/Y', strtotime($model->start_date)) : '' }}" type="text"  class="form-control" name="start_date">
                            </div>

                            <div class="col-md-6 mb-3 form-group">
                                <label for="end_date">End Date <span class="text-danger">*</span></label>
                                <input type="text" value="{{ $model->end_date ? date('m/d/Y', strtotime($model->end_date)) : '' }}" name="end_date" id="end_date" class="form-control">
                            </div>
                            
                            @include('backend.components.descriptionInput', ['description' => $model->description])

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 form-group">
                <button type="submit" class="btn btn-soft-success"  id="submit">
                    <i class="bi bi-send"></i>
                    Update
                </button>
                <button class="btn btn-soft-warning" style="display: none;" id="submitting" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
                @if (Auth::guard('admin')->user()->hasPermissionTo('pricing-tier.update'))
                    <a href="{{ route('admin.pricing-tier.index') }}" class="btn btn-soft-danger">
                        <i class="bi bi-backspace"></i>
                        Back
                    </a>
                @endcan
            </div>
        </div>
    </form>
@endsection
@push('script')
    <script src="{{ asset('backend/assets/js/tempus-dominus.min.js') }}"></script>

    <script>
        _formValidation();
        _initCkEditor("editor");
        _componentSelect();

        const element = document.getElementById('start_date');
        const input = document.getElementById('start_date');
        const picker = new tempusDominus.TempusDominus(element, {
            defaultDate: new Date(),
            display: {
                components: {
                    calendar: true,
                    date: true,
                    month: true,
                    year: true,
                    decades: true,
                    clock: false 
                }
            }
        });

        element.addEventListener('change.td', (e) => {
            const selectedDate = picker.dates.formatInput(e.detail.date); 
            input.value = selectedDate;
        });

        const element2 = document.getElementById('end_date');
        const input2 = document.getElementById('end_date');
        const picker2 = new tempusDominus.TempusDominus(element2, {
            defaultDate: new Date(),
            display: {
                components: {
                    calendar: true,
                    date: true,
                    month: true,
                    year: true,
                    decades: true,
                    clock: false 
                }
            }
        });

        element2.addEventListener('change.td', (e) => {
            const selectedDate = picker2.dates.formatInput(e.detail.date);
            input2.value = selectedDate;
        });
    </script>
@endpush