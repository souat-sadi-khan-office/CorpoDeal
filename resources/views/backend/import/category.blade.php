@extends('backend.layouts.app', ['modal' => 'lg'])
@section('title', 'Category Bulk Import')
@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Category Bulk Import</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert" style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                                <strong>Step One:</strong>
                                <p>1. Download the skeleton file and fill it with proper data.</p>
                                <p>2. You can download the example file to understand how the data must be filled.</p>
                                <p>3. Once you have downloaded and filled the skeleton file, upload it in the form below and submit.</p>
                                <p>4. After uploading category you can edit them and set images and others.</p>
                            </div>
                            <br>
                            <div class="">
                                <a class="btn btn-sm btn-info" href="{{ asset('download/category_bulk_demo.xlsx') }}" download>
                                    Download CSV
                                </a>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <form action="{{ route('admin.upload.category') }}" class="content_form" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="card mt-5 ">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 form-group">
                                                <h4>Upload Category File</h4>
                                            </div>
                                            <div class="col-md-12 mt-3 form-group">
                                                <input type="file" name="file" id="file" required accept=".csv, .xls, .xlsx" class="form-control">
                                                <span class="text-danger">Maximum 200 column at a time</span>
                                            </div>
                                            <div class="col-md-12 mt-3 form-group">
                                                <button type="submit" class="btn btn-sm btn-outline-success"  id="submit">
                                                    <i class="bi bi-send"></i>
                                                    Upload
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning" style="display: none;" id="submitting" type="button" disabled>
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                    Loading...
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="alert" style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                        <strong>File Column Indexing:</strong>
                        <p>When you are filling it with proper data:</p>
                        <ul>
                            <li>
                                <b>Column - 1: (Category)</b>: If you want to add this column data as parent, write parent or write down any other category for parent.
                            </li>
                            <li>
                                <b>Column - 2: (Name)</b>: Category Name
                            </li>
                            <li>
                                <b>Column - 3: (Slug)</b>: Category Slug. Use unique slug.
                            </li>
                            <li>
                                <b>Column - 4: (Header)</b>: Add Category Page header. You can use empty also. It is not requried.
                            </li>
                            <li>
                                <b>Column - 5: (Short Description)</b>: Category Page Short Description under header. This is not required.
                            </li>
                            <li>
                                <b>Column - 6: (Site Title)</b>: This is Page Title
                            </li>
                            <li>
                                <b>Column - 7: (Meta Title)</b>: This is Meta Title
                            </li>
                            <li>
                                <b>Column - 8: (Meta Keyword)</b>: This is Meta Keyword
                            </li>
                            <li>
                                <b>Column - 9: (Meta Description)</b>: This is Meta Description
                            </li>
                            <li>
                                <b>Column - 10: (Status)</b>: Use 1 for Active and 0 for Inactive. By default 0.
                            </li>
                            <li>
                                <b>Column - 11: (Featured)</b>: Use 1 for Active and 0 for Inactive. By default 0.
                            </li>
                            <li>
                                <b>Column - 12: (Photo)</b>: This is optional. Use Photo Link. if you wnat you can upload photo <a class="text-danger" style="font-weight: bold;" href="{{ route('admin.image.index') }}">Image Upload</a> Section and use the link here.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script>
        _formValidation();
    </script>
@endpush
