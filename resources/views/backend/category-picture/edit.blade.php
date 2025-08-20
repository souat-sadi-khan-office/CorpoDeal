@extends('backend.layouts.app')
@section('title', 'Update Home Page Category Information')
@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/dropify.min.css') }}">
@endpush
@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-9">
                    <h1 class="h3 mb-0">Update Home Page Category Information</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.category-banner.index') }}">Home Page Category Management</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Update Home Page Category Information</li>
                    </ol>
                </div>

                {{-- @if (Auth::guard('admin')->user()->hasPermissionTo('brand.view')) --}}
                    <div class="col-sm-3 text-end">
                        <a href="{{ route('admin.category-banner.index') }}" class="btn btn-soft-danger">
                            <i class="bi bi-backspace"></i>
                            Back
                        </a>
                    </div>
                {{-- @endif --}}
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 mx-auto">
            <form action="{{ route('admin.category-banner.update', $model->id) }}" enctype="multipart/form-data" class="content_form" method="POST">
                @method('PATCH')
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 form-group mb-3">
                                <label for="category_id">Category <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="{{ $model->category_id }}">{{ $model->category->name }}</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3 form-group">
                                <label for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Enter Brand Name" name="name" id="name" class="form-control" required value="{{ $model->name }}">
                            </div>

                            <div class="col-md-12 mb-3 form-group">
                                <label for="picture">Picture </label>
                                <input type="file" accept=".jpg, .png, .webp"  name="picture" id="picture" class="form-control dropify" data-default-file="{{ asset($model->picture) }}">
                                <span class="text-danger">Left sidebar Banner size is <b>375 X 450</b> pixel. Right sidebar Banner size is <b>1200 X 450</b> pixel. Please use <b>.WEBP</b> format picture for better performance.</span>
                            </div>

                            <div class="col-md-12 mb-3 form-group">
                                <label for="position">Position <span class="text-danger">*</span></label>
                                <select name="position" id="position" class="form-control select" required>
                                    <option {{ $model->position == 'after_breadcrumb_section' ? 'selected' : '' }} value="after_breadcrumb_section">After BreadCrumb Section</option>
                                    <option {{ $model->position == 'after_title_and_description' ? 'selected' : '' }} value="after_title_and_description">After Title & Description</option>
                                    <option {{ $model->position == 'on_left_sidebar_start' ? 'selected' : '' }} value="on_left_sidebar_start">On Left Sidebar Start</option>
                                    <option {{ $model->position == 'on_left_sidebar_footer' ? 'selected' : '' }} value="on_left_sidebar_footer">On Left Sidebar Footer</option>
                                    <option {{ $model->position == 'after_left_sidebar_price_range' ? 'selected' : '' }} value="after_left_sidebar_price_range">After Left Sidebar Price Range Filter</option>
                                    <option {{ $model->position == 'after_left_sidebar_stock' ? 'selected' : '' }} value="after_left_sidebar_stock">After Left Sidebar Stock Filter</option>
                                    <option {{ $model->position == 'after_left_sidebar_brand' ? 'selected' : '' }} value="after_left_sidebar_brand">After Left Sidebar Brand Filter</option>
                                    <option {{ $model->position == 'after_left_sidebar_rating' ? 'selected' : '' }} value="after_left_sidebar_rating">After Left Sidebar Rating Filter</option>
                                    <option {{ $model->position == 'after_left_sidebar_specification_key' ? 'selected' : '' }} value="after_left_sidebar_specification_key">After Left Sidebar Specification Filter</option>
                                    <option {{ $model->position == 'on_right_sidebar_top' ? 'selected' : '' }} value="on_right_sidebar_top">On Right Sidebar Top</option>
                                    <option {{ $model->position == 'on_right_sidebar_bottom' ? 'selected' : '' }} value="on_right_sidebar_bottom">On Right Sidebar Bottom</option>
                                    <option {{ $model->position == 'on_right_sidebar_after_filter' ? 'selected' : '' }} value="on_right_sidebar_after_filter">On Right Sidebar After Filter</option>
                                </select>
                            </div>
                            
                            <div class="col-md-12 mb-3 form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control select" required>
                                    <option {{ $model->status == 1 ? 'selected' : ''}} value="1">Active</option>
                                    <option {{ $model->status == 0 ? 'selected' : ''}} value="0">Inactive</option>
                                </select>
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
                                <a href="{{ route('admin.category-banner.index') }}" class="btn btn-soft-danger">
                                    <i class="bi bi-backspace"></i>
                                    Back
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('backend/assets/js/dropify.min.js') }}"></script>
    <script>
        _formValidation();
        _componentSelect();

        $('.dropify').dropify({
            imgFileExtensions: ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp']
        });

        $('#category_id').select2({
            width: '100%',
            placeholder: 'Select category',
            templateResult: formatCategoryOption,
            templateSelection: formatCategorySelection,
            ajax: {
                url: '/search/category',
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
                        results:response
                    };
                }
            }
        });

        function formatCategoryOption(category) {
            if (!category.id) {
                return category.text;
            }

            var categoryImage = '<img src="' + category.image_url + '" class="img-flag" style="height: 20px; width: 20px; margin-right: 10px;" />';
            var categoryOption = $('<span>' + categoryImage + category.text + '</span>');
            return categoryOption;
        }

        function formatCategorySelection(category) {

            if (!category.id) {
                return category.text;
            }

            var defaultImageUrl = $('#default_category_image').val();
            var image_url = category.image_url ? category.image_url : defaultImageUrl;

            var categoryImage = '<img src="' + image_url + '" class="img-flag" style="height: 20px; width: 20px; margin-right: 10px;" />';
            return $('<span>' + categoryImage + category.text + '</span>');
        }
    </script>
@endpush
