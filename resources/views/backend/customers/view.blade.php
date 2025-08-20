@extends('backend.layouts.app', ['modal' => 'lg'])
@section('title', 'View Full Information | Customer Management')
@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="h3 mb-0">View Customer Full Information</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.customer.index') }}">
                                Customer Management
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">View Full Information</li>
                    </ol>
                </div>

                <div class="col-sm-6 text-end">
                    <div class="dropdown">
                        <a style="padding: 2px 8px;font-size:12px;" href="{{ route('admin.customer.index') }}" class="btn btn-outline-danger">
                            <i class="bi bi-arrow-left-short"></i>
                            Back
                        </a>
                        @if ($model)
                            <button style="padding: 2px 8px;font-size:12px;" class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton_one" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-pen"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_one">
                                {{-- <a href="{{ route('admin.customer.view', ['id' => $model->id, 'action' => '']) }}" class="dropdown-item">
                                    General Information
                                </a> --}}

                                <a href="{{ route('admin.customer.view', ['id' => $model->id, 'action' => 'address']) }}" class="dropdown-item">
                                    Address
                                </a>

                                <a href="{{ route('admin.customer.view', ['id' => $model->id, 'action' => 'phone-numbers']) }}" class="dropdown-item">
                                    Phone Numbers
                                </a>

                                <a href="{{ route('admin.customer.view', ['id' => $model->id, 'action' => 'point-history']) }}" class="dropdown-item">
                                    Point History
                                </a>

                                <a href="{{ route('admin.customer.view', ['id' => $model->id, 'action' => 'wish-list']) }}" class="dropdown-item">
                                    Wish List
                                </a>

                                <a href="{{ route('admin.customer.view', ['id' => $model->id, 'action' => 'cart-items']) }}" class="dropdown-item">
                                    Cart Items
                                </a>

                                <a href="{{ route('admin.customer.view', ['id' => $model->id, 'action' => 'contact-messages']) }}" class="dropdown-item">
                                    Contact Messages
                                </a>

                                <a href="{{ route('admin.customer.view', ['id' => $model->id, 'action' => 'negative-balance']) }}" class="dropdown-item">
                                    Negative Balance Request
                                </a>

                                <a href="{{ route('admin.customer.view', ['id' => $model->id, 'action' => 'order-history']) }}" class="dropdown-item">
                                    Order History
                                </a>

                                <a href="{{ route('admin.customer.view', ['id' => $model->id, 'action' => 'product-queries']) }}" class="dropdown-item">
                                    Product Questions
                                </a>

                                <a href="{{ route('admin.customer.view', ['id' => $model->id, 'action' => 'brought-coupon']) }}" class="dropdown-item">
                                    Brought Coupon
                                </a>

                                <a href="{{ route('admin.customer.view', ['id' => $model->id, 'action' => 'coupon-usage']) }}" class="dropdown-item">
                                    Coupon Usage
                                </a>

                                <a href="{{ route('admin.customer.view', ['id' => $model->id, 'action' => 'product-reviews']) }}" class="dropdown-item">
                                    Product Reviews
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')

    @if (!$model)
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 py-5">
                        <div class="row py-5">
                            <div class="col-md-6 mx-auto">
                                <label for="user_id">Select Customer</label>
                                <select id="customer_id" class="form-control"></select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-4">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img width="100" class="profile-user-img img-fluid img-circle" src="{{ Auth::guard('admin')->user()->avatar ? asset(Auth::guard('admin')->user()->avatar) : asset('pictures/face.jpg') }}" alt="{{ Auth::guard('admin')->user()->name }} Picture">
                        </div>

                        <h3 class="h6 profile-username text-center">
                            @if ($model->currency->country->image != null)
                                <img src="{{ asset($model->currency->country->image) }}" alt="Country Flag" style="width: 25px;">
                            @endif
                            
                            {{ Auth::guard('admin')->user()->name }} <span class="badge bg-{{ $model->status == 1 ? 'success' : 'warning' }}">{{ $model->status == '1' ? 'Active' : 'Inactive' }}</span>
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
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        @php
                            $allowedActions = ['general-information', 'point-history', 'address', 'phone-numbers', 'wish-list', 'cart-items','negative-balance', 'order-history','contact-messages', 'product-reviews', 'brought-coupon', 'coupon-usage', 'product-queries'];
                            $view = in_array($action, $allowedActions) ? $action : 'general-information';
                        @endphp

                        @include('backend.customers.view.'. $view)
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
@push('script')
    <script src="{{ asset('backend/assets/js/pages/user-info.js') }}"></script>
@endpush
