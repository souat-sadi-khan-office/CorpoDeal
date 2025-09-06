@extends('backend.layouts.app', ['modal' => 'md'])
@section('title', 'Public Specification Keys')
@push('style')
    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush
@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="h3 mb-0">Public Specification Keys</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Public Specification Keys</li>
                    </ol>
                </div>

                @if (Auth::guard('admin')->user()->hasPermissionTo('specification-key.create'))
                    <div class="col-sm-6 text-end">
                        <a href="javascript:;" data-url="{{ route('admin.category.specification.key.create') }}" id="content_management" class="btn btn-sm btn-outline-dark">
                            <i class="bi bi-plus"></i>
                            Add New
                        </a>
                    </div>
                @endif
                <p class="mx-auto">
                    These Keys Can be Accessed In Every Selected Category when Product Specification Create/Update.
                </p>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <!-- @include('backend.category.specificationKeys.keysModal') -->

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="data-table">
                        <thead>
                            <tr>
                                <th>Name & Position</th>
                                <th style="width: 21%;">Creator</th>
                                <th style="width: 6%;">Status</th>
                                <!-- <th style="width: 6%;">Public</th> -->
                                <th style="width: 7%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($models) > 0)
                                @foreach ($models as $data)
                                    <tr data-row-id="{{ $data->id }}">
                                        <td>
                                            <form action="{{ route('admin.category.specification.key.position', $data->id) }}"
                                                method="POST" class="nested-form" data-id="{{ $data->id }}">
                                                @csrf
                                                @method('POST')
                                                <div class="row">
                                                    <div class="col-md-6 form-group">
                                                        <input type="text" name="name" id="name{{ $data->id }}"
                                                            class="form-control name-input" value="{{ $data->name }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-md-6 form-group">
                                                                <input type="text" name="position"
                                                                    id="position{{ $data->id }}"
                                                                    class="form-control number position-input" required
                                                                    value="{{ $data->position }}">
                                                            </div>
                                                            <div class="col-md-6 mt-1 form-group">
                                                                <button class="btn btn-sm btn-dark submit-btn" type="submit">
                                                                    <i class="bi bi-send"></i>
                                                                    Update
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                        <td>{{ $data->admin->name }}</td>
                                        <td>
                                            @if (Auth::guard('admin')->user()->hasPermissionTo('specification-key.update'))
                                                <div class="form-check form-switch">
                                                    <input
                                                        data-url="{{ route('admin.category.specification.key.status', $data->id) }}"
                                                        class="form-check-input" type="checkbox" role="switch" name="status"
                                                        id="status{{ $data->id }}" {{ $data->status == 1 ? 'checked' : '' }}
                                                        data-id="{{ $data->id }}">
                                                </div>
                                            @else
                                                <span class="badge bg-{{ $data->status == 1 ? 'success' : 'warning' }}">{{ $data->status == 1 ? 'Active' : 'Inactive' }}</span>
                                            @endif
                                        </td>
                                        <!-- <td>

                                            @if (Auth::guard('admin')->user()->hasPermissionTo('specification-key.update'))
                                                <div class="form-check form-switch">
                                                    <input
                                                        data-url="{{ route('admin.category.specification.key.is_public', $data->id) }}"
                                                        class="form-check-input" type="checkbox" role="switch" name="is_public"
                                                        id="is_public{{ $data->id }}" {{ $data->is_public == 1 ? 'checked' : '' }}
                                                        data-id="{{ $data->id }}">
                                                </div>
                                            @else
                                                <span class="badge bg-{{ $data->status == 1 ? 'success' : 'warning' }}">{{ $data->status == 1 ? 'Public' : 'Private' }}</span>
                                            @endif
                                        </td> -->
                                        <td>
                                            @if (Auth::guard('admin')->user()->hasPermissionTo('specification-key.delete'))
                                                <a href="javascript:;" id="delete_specification" data-id="{{ $data->id }}"
                                                    data-url="{{ route('admin.category.specification.key.delete', $data->id) }}"
                                                    class="corposhop-icon corposhop-icon-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else   
                                <tr>
                                    <td class="text-center" colspan="5">
                                        <b>No Public Keys to show.</b>
                                    </td>
                                </tr>
                            @endif
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if (count($models) > 0)
        <div class="d-flex justify-content-between align-items-center">
            @include('frontend.components.paginate',['products'=>$models])
        </div>
    @endif
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            _initializeMultipleFormsValidation();
            _componentRemoteModalLoadAfterAjax();
            _statusUpdate();
            _ispublicUpdate();
        });
    </script>
@endpush