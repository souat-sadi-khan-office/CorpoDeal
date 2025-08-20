@extends('backend.layouts.app')
@section('title', 'Website Appearance Configuration | General')
@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/dropify.min.css') }}">
@endpush
@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="h3 mb-0">Website Appearance Configuration</h1>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <form action="{{ route('admin.settings.update') }}" method="POST" class="content_form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0">General</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3 form-group">
                                <label for="system_website_name">Frontend Website Name</label>
                                <input type="text" name="system_website_name" id="system_website_name" class="form-control" value="{{ get_settings('system_website_name') }}">
                            </div>
                            <div class="col-md-12 mb-3 form-group">
                                <label for="system_motto">Site Motto</label>
                                <input type="text" name="system_motto" id="system_motto" class="form-control" value="{{ get_settings('system_motto') }}">
                            </div>
                            <div class="col-md-12 mb-3 form-group">
                                <label for="system_icon">Site Icon</label>
                                <input type="file" name="system_icon" id="system_icon" class="form-control dropify" data-default-file="{{ get_settings('system_icon') ? asset(get_settings('system_icon')) : '' }}">
                            </div>
                            <div class="col-md-12 form-group mb-3">
                                <label for="system_base_color">Website Base Color</label>
                                <input type="text" name="system_base_color" id="system_base_color" class="form-control" value="{{ get_settings('system_base_color') }}">
                                <small class="text-dark">Hex Color Code</small>
                            </div>
                            <div class="col-md-12 form-group mb-3">
                                <label for="system_hover_color">Website Hover Color</label>
                                <input type="text" name="system_hover_color" id="system_hover_color" class="form-control" value="{{ get_settings('system_hover_color') }}">
                                <small class="text-dark">Hex Color Code</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0">Global SEO</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3 form-group">
                                <label for="system_global_meta_title">Meta Title</label>
                                <input type="text" name="system_global_meta_title" id="system_global_meta_title" class="form-control" value="{{ get_settings('system_global_meta_title') }}">
                            </div>
                            <div class="col-md-12 mb-3 form-group">
                                <label for="system_global_meta_keywords">Meta Keyword</label>
                                <textarea name="system_global_meta_keywords" id="system_global_meta_keywords" cols="30" rows="4" class="form-control">{{ get_settings('system_global_meta_keywords') }}</textarea>
                            </div>
                            <div class="col-md-12 mb-3 form-group">
                                <label for="system_global_meta_description">Meta Description</label>
                                <textarea name="system_global_meta_description" id="system_global_meta_description" cols="30" rows="4" class="form-control">{{ get_settings('system_global_meta_description') }}</textarea>
                            </div>
                            <div class="col-md-12 mb-3 form-group">
                                <label for="system_global_meta_image">Meta Image</label>
                                <input type="file" name="system_global_meta_image" id="system_global_meta_image" class="form-control dropify" data-default-file="{{ get_settings('system_global_meta_image') ? asset(get_settings('system_global_meta_image')) : '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" id="submit" class="btn btn-soft-success">
                                    <i class="bi bi-send"></i>
                                    Update
                                </button>
                                <button class="btn btn-soft-warning" style="display: none;" id="submitting" type="button" disabled>
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Loading...
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('script')
    <script src="{{ asset('backend/assets/js/dropify.min.js') }}"></script>
    <script>
        _componentSelect();
        _formValidation();

        $('.dropify').dropify();
    </script>
@endpush