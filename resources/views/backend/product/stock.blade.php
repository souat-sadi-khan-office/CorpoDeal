@extends('backend.layouts.app', ['modal' => 'lg'])
@section('title', 'Stock management')
@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/dropify.min.css') }}">
@endpush

@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="h3 mb-0">Stock Report</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.product.index') }}">
                                Product Management
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Stock Report</li>
                    </ol>
                </div>
                <div class="col-sm-6 text-end">
                    @if (Auth::guard('admin')->user()->hasPermissionTo('product.view'))
                        <a href="{{ route('admin.product.index') }}" class="btn btn-soft-danger">
                            <i class="bi bi-backspace"></i>
                            Back
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="data-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Added By</th>
                                <th>SKU</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($models as $model)
                                <tr>
                                    <td>{{ get_system_date($model->created_at) }} {{ get_system_time($model->created_at) }}</td>
                                    <td>{{ $model->admin->name }}</td>
                                    <td>{{ $model->sku }}</td>
                                    <td>{{ $model->quantity }}</td>
                                    <td>{{ format_price($model->unit_price) }}</td>
                                    <td>{{ format_price($model->unit_price * $model->quantity) }}</td>
                                    <td>
                                        @if ($model->is_sellable)
                                            <span class="badge bg-success">Sellable</span>
                                        @else    
                                            <span class="badge bg-danger">Not Sellable</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="javascript:;" data-url="{{ route('admin.stock.show', $model->id) }}" class="btn btn-soft-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="Top" title="View" id="content_management">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(function () {
            _componentRemoteModalLoadAfterAjax();
        });
    </script>
@endpush