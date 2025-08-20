@extends('backend.layouts.app')
@section('title', 'SEO Configuration | '. $page)
@section('content')
<div class="row mt-5">
    <div class="col-lg-12 mx-auto col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h1 class="h5 mb-0">SEO Configuration | {{ $page }}</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update') }}" method="POST" class="content_form">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 form-group mb-3">
                            <div class="align-items-start">
                                <div class="row">
                                    <!-- {{ $slug }}_site_title -->
                                    <div class="col-md-12 mb-3 form-group">
                                        <label for="{{ $slug }}_site_title">Site Title <span class="text-danger">*</span></label>
                                        <input type="text" name="{{ $slug }}_site_title" id="{{ $slug }}_site_title" class="form-control" required value="{{ get_settings($slug. '_site_title') }}">
                                    </div>

                                    <!-- {{ $slug }}_meta_title -->
                                    <div class="col-md-12 mb-3 form-group">
                                        <label for="{{ $slug }}_meta_title">Meta Title <span class="text-danger">*</span></label>
                                        <input type="text" name="{{ $slug }}_meta_title" id="{{ $slug }}_meta_title" class="form-control" required value="{{ get_settings($slug. '_meta_title') }}">
                                    </div>

                                    <!-- {{ $slug }}_meta_description -->
                                    <div class="col-md-12 mb-3 form-group">
                                        <label for="{{ $slug }}_meta_description">Meta Description <span class="text-danger">*</span></label>
                                        <textarea name="{{ $slug }}_meta_description" id="{{ $slug }}_meta_description" cols="30" rows="3" class="form-control" required>{{ get_settings( $slug .'_meta_description') }}</textarea>
                                    </div>

                                    <!-- {{ $slug }}_meta_article_tag -->
                                    <div class="col-md-12 mb-3 form-group">
                                        <label for="{{ $slug }}_meta_article_tag">Meta Article Tag</label>
                                        <textarea name="{{ $slug }}_meta_article_tag" id="{{ $slug }}_meta_article_tag" cols="30" rows="3" class="form-control">{{ get_settings($slug .'_meta_article_tag') }}</textarea>
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