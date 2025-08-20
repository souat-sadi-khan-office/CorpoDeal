@extends('backend.layouts.app', ['title' => 'Mail Template'])
@section('content')
<div class="row mt-5">
    <div class="col-lg-12 mx-auto col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h1 class="h5 mb-0">Email Templates</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update') }}" method="POST" class="content_form">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 form-group mb-3">
                            <div class="align-items-start">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                            <button class="nav-link active" id="v-pills-phone-number-verification-tab" data-bs-toggle="pill" data-bs-target="#v-pills-phone-number-verification" type="button" role="tab" aria-controls="v-pills-phone-number-verification" aria-selected="true">
                                                Forget Password
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="tab-content" id="v-pills-tabContent">
                                            <div class="tab-pane fade show active" id="v-pills-phone-number-verification" role="tabpanel" aria-labelledby="v-pills-phone-number-verification-tab">
                                                <div class="row">
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="forget_password_subject">Subject</label>
                                                        <input type="text" name="forget_password_subject" id="forget_password_subject" class="form-control" value="{{ get_settings('forget_password_subject') }}" required>
                                                    </div>
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="forget_password_template">Template</label>
                                                        <textarea name="forget_password_template" id="forget_password_template" class="form-control" cols="30" required rows="4">{{ get_settings('forget_password_template') }}</textarea>
                                                        <span class="text-danger">Do not change the variable [[ ____ ]]</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 form-group text-end">
                            <button type="submit" id="submit" class="btn btn-soft-success">
                                <i class="bi bi-send"></i>
                                Update
                            </button>
                            <button type="button" style="display: none;" id="submitting" class="btn btn-soft-warning">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
    <script>
        _componentSelect();
        _formValidation();
    </script>
@endpush