@extends('backend.layouts.app')
@section('title', 'Push Email')
@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="h3 mb-0">Notification/Custom Mail for All Users</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Push Email</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <form action="{{ route('admin.mail.send') }}" method="POST"
          enctype="multipart/form-data" class="content_form">
        @csrf
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h2 class="h5 mb-0">Email Message <span class="text-danger">*</span></h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="subject">Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control" id="subject">
                        </div>
                    </div>

                    <div class="row">
                        @include('backend.components.descriptionInput')
                    </div>

                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="attachments">Attachments <span class="text-success">(optional)</span></label>
                            <p>Max File Size 512 KB</p>
                            <input type="file" name="attachments" id="attachments" class="form-control" data-max-file-size="2M" >
                        </div>

                    </div>
                    <div class="col-md-12 form-group text-end">
                        <button type="submit" id="DeliveryCharge" class="btn btn-success istiyak bw-2 mt-2">
                            <i class="bi bi-send"></i>
                            Push
                        </button>
                        <button class="btn btn-soft-warning mt-2" style="display: none;"
                                id="submitting_DeliveryCharge" type="button" disabled>
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                  aria-hidden="true"></span>
                            Loading...
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </form>
@endsection
@push('script')
    <script>
        _initCkEditor("editor");
        _formValidation();

    </script>
@endpush
