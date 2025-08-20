@extends('backend.layouts.app')
@section('title', 'Create new Banner')
@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="h3 mb-0">Create new Banner</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.banner.index') }}">Banner Management</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Create new Banner</li>
                    </ol>
                </div>

                @if (Auth::guard('admin')->user()->hasPermissionTo('banner.view'))
                    <div class="col-sm-6 text-end">
                        <a href="{{ route('admin.banner.index') }}" class="btn btn-soft-danger">
                            <i class="bi bi-backspace"></i>
                            Back
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/dropify.min.css') }}">
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('admin.banner.store') }}" enctype="multipart/form-data" class="content_form" method="post">
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-12 form-group mb-3">
                                <div class="alert alert-info">
                                    <h6>Banner Information</h6>
                                    <ul>
                                        <li><b>Title</b>: This is optional Field. If you do'nt fill this field, it will show blank.</li>
                                        <li><b>Content</b>: This is optional Field. If you do'nt fill this field, it will show blank.</li>
                                        <li><b>Old Offer</b>: This is optional Field. If you do'nt fill this field, it will show blank.</li>
                                        <li><b>New Offer</b>: This is optional Field. If you do'nt fill this field, it will show blank.</li>
                                        <li>
                                            <b>Image Size</b>: Here is 3 types of banner type, those are given below:
                                            <ol>
                                                <li><b>Main Banner</b>: Width: 825px, Height: 550px</li>
                                                <li><b>Main Sidebar Banner</b>: Width: 255px, Height: 255px</li>
                                                <li><b>Mid Website Banner</b>: Width: 450px, Height: 250px</li>
                                            </ol>
                                        </li>
                                        <li><b>Link</b>: This is required. You must have to give any url here.</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3 form-group">
                                <label for="name">Title</label>
                                <textarea name="header_title" id="header_title" placeholder="Enter Banner Header Title" cols="30" rows="3" class="form-control"></textarea>
                            </div>

                            <div class="col-md-6 mb-3 form-group">
                                <label for="name">Content</label>
                                <textarea name="name" id="name" placeholder="Enter Banner Name" cols="30" rows="3" class="form-control"></textarea>
                            </div>
                            
                            <div class="col-md-6 mb-3 form-group">
                                <label for="name">Old Offer</label>
                                <input type="text" placeholder="If Have" name="old_offer" id="old_offer" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3 form-group">
                                <label for="name">New Offer </label>
                                <input type="text" placeholder="If Have" name="new_offer" id="new_offer" class="form-control">
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="banner_type">Banner type <span class="text-danger">*</span></label>
                                <select name="banner_type" id="banner_type" class="form-control select">
                                    <option selected value="main">Main Banner</option>
                                    <option value="main_sidebar">Main Sidebar Banner</option>
                                    <option value="Mid">Mid Website Banner</option>
                                    {{-- <option value="Footer">Footer Banner</option> --}}
                                </select>
                            </div>

                            {{-- <div class="col-md-12 form-group mb-3">
                                <label for="source_type">Source type</label>
                                <select name="source_type" id="source_type" class="form-control select" data-placeholder="--Select Source--">
                                    <option value="">--Select Source--</option>
                                    <option value="category">Category</option>
                                    <option value="product">Product</option>
                                    <option value="brand">Brand</option>
                                </select>
                                <p class="text-danger" style="font-size: small;margin: 0;">Select only if you are adding Banners for any Specific Category/Product or Brand.</p>
                            </div>
                            
                            <div class="col-md-12 form-group mb-3" id="sourceContainer" style="display: none;">
                                <label for="source_id" id="sourceLabel">Source Name <span class="text-danger">*</span></label>
                                <select name="source_id" id="source_id" class="form-control select">
                                    <option selected value="" disabled>--Select Source--</option>
                                </select>
                            </div> --}}
                    
                            <div class="col-md-12 mb-3 form-group">
                                <label for="image">Image <span class="text-danger">*</span></label>
                                <input type="file" accept=".jpg, .png, .webp"  name="image" id="image" class="form-control dropify" required>
                                {{-- <span class="text-danger">Image size is <b>825 X 550</b> or <b>250 X 355</b> pixrl. Please use <b>.WEBP</b> format picture for better performance.</span> --}}
                            </div>

                            <div class="col-md-4 form-group mb-3">
                                <label for="alt_tag">Image Alter Tag</label>
                                <input type="text" name="alt_tag" id="alt_tag" class="form-control">
                            </div>

                            <div class="col-md-4 form-group mb-3">
                                <label for="link">Link</label>
                                <input type="url" name="link" id="link" placeholder="starts with https:// or http://" class="form-control" required>
                            </div>

                            <div class="col-md-4 mb-3 form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control" required>
                                    <option selected value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>

                            <div class="col-md-12 form-group">
                                @if (Auth::guard('admin')->user()->hasPermissionTo('banner.create'))
                                    <button type="submit" class="btn btn-soft-success"  id="submit">
                                        <i class="bi bi-send"></i>
                                        Create
                                    </button>
                                    <button class="btn btn-soft-warning" style="display: none;" id="submitting" type="button" disabled>
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Loading...
                                    </button>
                                @endif
                                @if (Auth::guard('admin')->user()->hasPermissionTo('banner.view'))
                                    <a href="{{ route('admin.stuff.index') }}" class="btn btn-soft-danger">
                                        <i class="bi bi-backspace"></i>
                                        Back
                                    </a>
                                @endif
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
        
    </script>
  <script>
    $(document).ready(function() {
        $('#source_type').on('change', function() {
            var sourceType = $(this).val();

            $('#source_id').empty().append('<option selected value="" disabled>--Select Source--</option>');

            if (sourceType) {
                $('#sourceContainer').show();

                var typeName = sourceType.charAt(0).toUpperCase() + sourceType.slice(1); 
                $('#sourceLabel').text(typeName + ' Name'); 

                $.ajax({
                    url: '/admin/banner/source/' + sourceType, 
                    type: 'GET',
                    success: function(response) {
                        if (response.source && response.source.length > 0) {
                            $.each(response.source, function(index, item) {
                                $('#source_id').append('<option value="' + item.id + '">' + item.name + '</option>');
                            });
                        } else {
                            $('#source_id').append('<option value="" disabled>No sources found</option>');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('An error occurred while fetching the sources.');
                    }
                });
            } else {
                $('#sourceContainer').hide();
            }
        });
    });
</script>
@endpush