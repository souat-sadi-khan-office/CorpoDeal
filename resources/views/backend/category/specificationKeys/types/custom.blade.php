@extends('backend.layouts.app')
@section('title', 'Category Specification Type Management')
@push('style')
@endpush
@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="h3 mb-0">Category Specification Type Management</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.category.index') }}">
                                {{ $key->category->name }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Specification Types</li>
                    </ol>
                </div>

                {{-- @if (Auth::guard('admin')->user()->hasPermissionTo('category.create')) --}}

                {{-- @endif --}}
            </div>
        </div>
    </div>
@endsection
@section('content')

    <div class="card">
        <div class="card-body">
            <form method="POST" class="content_form"
                  action="{{ route('admin.category.specification.type.update', $key->id) }}">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <h5>Manage Specification Types for <b>{{ $key->category->name }}</b> category</h5>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <div class="alert alert-warning">
                            <b>Warning</b>: If you delete any Specification from here, It will delete all the
                            Specification attributes accordition to the Specification Type. Be careful about this.
                        </div>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-house-add-fill"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.category.index') }}">
                                    Parent Categories
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.category.index.sub') }}">
                                    Sub Categories
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.category.index') }}">
                                    {{ $key->category->name }}
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.category.keys', $key->category->id) }}">
                                    {{ $key->name }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Specification Types</li>
                        </ol>
                    </div>
                    <div class="col-md-12 mb-3 table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="data-table">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <th width="7%">Position</th>
                                <th>Show on Filter</th>
                                <th width="20%">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="key-table">
                            @if (count($types) > 0)
                                @foreach ($types as $key => $model)
                                    <tr id="data_{{ $model->id }}">
                                        <td>
                                            <input type="hidden" name="id[]" class="form-control"
                                                   value="{{ $model->id }}">
                                            <input type="text" name="name[]" class="form-control"
                                                   value="{{ $model->name }}">
                                        </td>
                                        <td>
                                            <select name="status[]" id="status_{{ $key }}" class="form-control select">
                                                <option {{ $model->status == 1 ? 'selected' : '' }} value="1">Active
                                                </option>
                                                <option {{ $model->status == 0 ? 'selected' : '' }} value="0">Inactive
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="position[]" id="position_{{ $key }}"
                                                   class="form-control number" value="{{ $model->position }}">
                                        </td>
                                        <td>
                                            <select name="show_on_filter[]" data-id="{{ $model->id }}"
                                                    class="form-control show_on_filter select" required>
                                                <option {{ $model->show_on_filter == 1 ? 'selected' : '' }} value="1">
                                                    Yes
                                                </option>
                                                <option {{ $model->show_on_filter == 0 ? 'selected' : '' }} value="0">
                                                    No
                                                </option>
                                            </select>
                                            <div class="filter_name_area" id="filter_area_{{ $model->id }}"
                                                 style="display:{{ $model->show_on_filter == 0 ? 'none' : 'block' }};">
                                                <input type="text" name="filter_name[]" class="form-control"
                                                       value="{{ $model->filter_name }}">
                                            </div>
                                        </td>
                                        <td>
                                            <a href="javascript:;" class="btn remove-id btn-sm btn-danger remove-item"
                                               data-id="{{ $model->id }}">
                                                <i class="bi bi-trash3"></i>
                                            </a>
                                            <a href="{{ route('admin.category.specification.type.attributes', $model->id) }}"
                                               class="btn btn-sm btn-icon btn-outline-info">
                                                Add Attributes
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <input type="hidden" id="counter" value="{{ count($types) }}">
                        <a href="javascript:;" class="add-new btn btn-sm btn-primary">
                            <i class="bi bi-plus"></i>
                            Add New
                        </a>
                    </div>
                    <div id="submit_area" class="col-md-6 text-end">
                        <button type="submit" id="submit" class="btn btn-sm btn-success">
                            <i class="bi bi-send"></i>
                            Update
                        </button>
                        <button class="btn btn-soft-warning" style="display: none;" id="submitting" type="button"
                                disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        </button>
                    </div>
                </div>
                <input type="hidden" name="remove_ids" id="remove_ids" value="">
            </form>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function () {
            _checkForData();
        });

        var _checkForData = function () {
            if ($("#key-table tr").length > 0) {
                $('#submit_area').show();
            } else {
                $('#submit_area').hide();
            }
        }

        _componentSelect();
        _formValidation();

        $(document).on('click', '.remove-id', function () {
            let id = $(this).data('id');
            let removeIds = $('#remove_ids').val();

            if (removeIds) {
                removeIds += ',' + id;
            } else {
                removeIds = id;
            }

            $('#remove_ids').val(removeIds);
        });

        var _removeRow = function (id) {
            $('#data_' + id).remove();
            _checkForData();
        }

        $(document).on('click', '.add-new', function () {
            let counter = $('#counter').val();
            let position = parseInt(counter) + 1;
            $('#counter').val(position);
            let content = `
                <tr id="data_` + position + `">
                    <td>
                        <input type="hidden" name="id[]" class="form-control">
                        <input required type="text" name="name[]" class="form-control" value="">
                    </td>
                    <td>
                        <select name="status[]" class="form-control select" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="position[]" value="` + position + `" class="form-control" required>
                    </td>
                    <td>
                        <select name="show_on_filter[]" data-id="` + position + `" class="form-control show_on_filter select" required>
                            <option value="1">Yes</option>
                            <option selected value="0">No</option>
                        </select>
                        <div class="filter_name_area" id="filter_area_` + position + `" style="display:none;">
                            <input type="text" name="filter_name[]" class="form-control">
                        </div>
                    </td>
                    <td>
                        <a href="javascript:;" class="btn btn-sm btn-danger remove-item" data-id="` + position + `">
                            <i class="bi bi-trash3"></i>
                        </a>
                    </td>
                </tr>
            `;

            $('#key-table').append(content);
            _componentSelect();
            _checkForData();
        })

        $(document).on('change', '.show_on_filter', function () {
            let val = $(this).val();
            let id = $(this).data('id');
            if (val == 0) {
                $('#filter_area_' + id).hide();
            } else {
                $('#filter_area_' + id).show();
            }
        })

        $(document).on('click', '.remove-item', function () {
            let id = $(this).data('id');
            _removeRow(id);
        })
    </script>
@endpush
