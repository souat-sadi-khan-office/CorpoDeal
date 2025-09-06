@extends('backend.layouts.app')
@section('title', 'Create new Page')
@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="h4 mb-0">
                        <b>Create new Page</b>
                    </h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a style="color: #000;" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a style="color: #000;" href="{{ route('admin.page.index') }}">Page Management</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Create new Page</li>
                    </ol>
                </div>

                {{-- @if (Auth::guard('admin')->user()->hasPermissionTo('brand.view')) --}}
                    <div class="col-sm-6 text-end">
                        <a href="{{ route('admin.page.index') }}" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-backspace" style="margin-right: 5px;"></i>
                            Back
                        </a>
                    </div>
                {{-- @endif --}}
            </div>
        </div>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/dropify.min.css') }}">
@endpush
@section('content')
    <form action="{{ route('admin.page.store') }}" enctype="multipart/form-data" class="content_form" method="post">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            {{-- <div class="col-md-12 form-group mb-3">
                                <label for="parent_id">Parent Page </label>
                                <select name="parent_id" id="parent_id" class="form-control select" data-placeholder="Parent Page" data-parsley-errors-container="#parent_id_error">
                                    <option value="">Parent Page</option>
                                    @foreach ($pages as $page)
                                        <option value="{{ $page->id}}">{{ $page->title }}</option>
                                    @endforeach
                                </select>
                                <span id="parent_id_error"></span>
                            </div> --}}

                            <div class="col-md-12 mb-3 form-group">
                                <label for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>

                            <div class="col-md-12 mb-3 form-group">
                                <label for="slug">Slug <span class="text-danger">*</span></label>
                                <input type="text" name="slug" id="slug" class="form-control" required>
                            </div>
                    
                            <div class="col-md-6 mb-3 form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control select" required>
                                    <option selected value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3 form-group">
                                <label for="show_on_navbar">Show on Navbar <span class="text-danger">*</span></label>
                                <select name="show_on_navbar" id="show_on_navbar" class="form-control select" required>
                                    <option selected value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>

                            @include('backend.components.descriptionInput')

                            <div class="col-md-12 mb-3 form-group">
                                <label for="meta_tile">Meta Title <span class="text-danger">*</span></label>
                                <input type="text" name="meta_title" id="meta_title" class="form-control" required placeholder="Enter your Meta Title">
                            </div>

                            <div class="col-md-6 mb-3 form-group">
                                <label for="meta_keyword">Meta Keyword</label>
                                <textarea name="meta_keyword" id="meta_keyword" cols="30" rows="4" class="form-control" placeholder="Enter your SEO Meta Keyword"></textarea>
                            </div>

                            <div class="col-md-6 mb-3 form-group">
                                <label for="meta_description">Meta Description <span class="text-danger">*</span></label>
                                <textarea name="meta_description" id="meta_description" cols="30" rows="4" class="form-control" placeholder="Enter your SEO Meta Description" required></textarea>
                            </div>

                            <div class="col-md-6 mb-3 form-group">
                                <label for="meta_article_tag">Meta Article Tag</label>
                                <textarea name="meta_article_tag" id="meta_article_tag" cols="30" rows="4" class="form-control" placeholder="Enter your SEO Meta Article Scripts"></textarea>
                            </div>

                            <div class="col-md-6 mb-3 form-group">
                                <label for="meta_script_tag">Meta Script Tag</label>
                                <textarea name="meta_script_tag" id="meta_script_tag" cols="30" rows="4" class="form-control" placeholder="Enter your SEO Meta Scripts"></textarea>
                            </div>

                            <div class="col-md-12 mb-3 form-group">
                                <label for="meta_image">Meta image</label>
                                <input type="file" accept=".jpg, .png, .webp"  name="meta_image" id="meta_image" class="form-control dropify">
                            </div>
                    
                        </div>
                    </div>
                    <div class="card-footer">
                        {{-- @if (Auth::guard('admin')->user()->hasPermissionTo('stuff.create')) --}}
                            <button type="submit" class="btn btn-sm btn-dark"  id="submit">
                                <i class="bi bi-send"></i>
                                Create
                            </button>
                            <button class="btn btn-sm btn-outline-dark" style="display: none;" id="submitting" type="button" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading...
                            </button>
                        {{-- @endif --}}
                        {{-- @if (Auth::guard('admin')->user()->hasPermissionTo('stuff.view')) --}}
                            <a href="{{ route('admin.page.index') }}" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-backspace"></i>
                                Back
                            </a>
                        {{-- @endif --}}
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('script')
    <script src="{{ asset('backend/assets/js/dropify.min.js') }}"></script>
    <script>
        _formValidation();
        _initCkEditor("editor");
        _componentSelect();

        $('.dropify').dropify({
            imgFileExtensions: ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp']
        });
        
        function generateSlug(name) {
            return name
                .toString()
                .toLowerCase()
                .trim()
                .replace(/&/g, '-and-')
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-') 
                .replace(/-+/g, '-');
        }

        $('#name').on('input', function() {
            const name = $(this).val();
            const slug = generateSlug(name);
            $('#slug').val(slug);

            // Check if the slug exists
            $.ajax({
                url: '{{ route('admin.slug.check') }}',
                type: 'GET',
                data: {
                    slug: slug,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.exists) {
                        const timestamp = Date.now();
                        $('#slug').val(slug + '-' + timestamp);
                    }
                }
            });
        });
    </script>
@endpush