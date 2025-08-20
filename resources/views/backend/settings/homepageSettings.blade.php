@extends('backend.layouts.app')
@section('title', 'Home Page Configuration | frontend')
@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/dropify.min.css') }}">
@endpush
@section('content')
    <div class="row mt-5">
        <div class="col-lg-12 col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h1 class="h5 mb-0">HomePage Settings</h1>
                    <p>
                        Last Updated by - {{ homepage_setting('last_updated_by') }} at 
                        {{get_system_date(homepage_setting('last_updated_at'))}}
                        {{get_system_time(homepage_setting('last_updated_at'))}}
                    </p>

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="bannerSection">Banner Section <span class="text-danger">Necessery</span></label>
                            <div class="form-check form-switch"style=" padding-left: 2.9em!important;">
                                <input data-url="#"
                                    class="form-check-input" type="checkbox" role="switch" name="bannerSection"
                                    id="bannerSection" checked disabled>
                            </div>
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label for="sliderSection">Slider Section <span class="text-danger">*</span></label>
                            <div class="form-check form-switch"style=" padding-left: 2.9em!important;">
                                <input data-url="{{ route('admin.homepage.settings.status', 'sliderSection') }}"
                                    class="form-check-input" type="checkbox" role="switch" name="sliderSection"
                                    id="sliderSection" {{ homepage_setting('sliderSection') == 1 ? 'checked' : '' }}>
                            </div>

                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="midBanner">Mid Banner Section <span class="text-danger">*</span></label>
                            <div class="form-check form-switch"style=" padding-left: 2.9em!important;">
                                <input data-url="{{ route('admin.homepage.settings.status', 'midBanner') }}"
                                    class="form-check-input" type="checkbox" role="switch" name="midBanner" id="midBanner"
                                    {{ homepage_setting('midBanner') == 1 ? 'checked' : '' }}>
                            </div>

                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="dealOfTheDay">Deal of the Day Section <span class="text-danger">*</span></label>
                            <div class="form-check form-switch"style=" padding-left: 2.9em!important;">
                                <input data-url="{{ route('admin.homepage.settings.status', 'dealOfTheDay') }}"
                                    class="form-check-input" type="checkbox" role="switch" name="dealOfTheDay"
                                    id="dealOfTheDay" {{ homepage_setting('dealOfTheDay') == 1 ? 'checked' : '' }}>
                            </div>

                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="trending">Trending Section <span class="text-danger">*</span></label>
                            <div class="form-check form-switch"style=" padding-left: 2.9em!important;">
                                <input data-url="{{ route('admin.homepage.settings.status', 'trending') }}"
                                    class="form-check-input" type="checkbox" role="switch" name="trending" id="trending"
                                    {{ homepage_setting('trending') == 1 ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="brands">Brands Section <span class="text-danger">*</span></label>
                            <div class="form-check form-switch"style=" padding-left: 2.9em!important;">
                                <input data-url="{{ route('admin.homepage.settings.status', 'brands') }}"
                                    class="form-check-input" type="checkbox" role="switch" name="brands" id="brands"
                                    {{ homepage_setting('brands') == 1 ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="popularANDfeatured">Popular & Featured Section <span
                                    class="text-danger">*</span></label>
                            <div class="form-check form-switch"style=" padding-left: 2.9em!important;">
                                <input data-url="{{ route('admin.homepage.settings.status', 'popularANDfeatured') }}"
                                    class="form-check-input" type="checkbox" role="switch" name="popularANDfeatured"
                                    id="popularANDfeatured"
                                    {{ homepage_setting('popularANDfeatured') == 1 ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="newslatter">Newslatter Section <span class="text-danger">*</span></label>
                            <div class="form-check form-switch"style=" padding-left: 2.9em!important;">
                                <input data-url="{{ route('admin.homepage.settings.status', 'newslatter') }}"
                                    class="form-check-input" type="checkbox" role="switch" name="newslatter"
                                    id="newslatter" {{ homepage_setting('newslatter') == 1 ? 'checked' : '' }}>
                            </div>
                        </div>

                        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="content_form">
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="show_star_banner">Show Star Banner</label>
                                    <select name="show_star_banner" id="show_star_banner" class="form-control select" >
                                        <option {{ get_settings('show_star_banner') == 1 ? 'selected' : '' }} value="1">Show</option>
                                        <option {{ get_settings('show_star_banner') == 0 ? 'selected' : '' }} value="0">Hide</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 form-group mb-3">
                                    <label for="star_url_open_in_another_tab">Open URL in a new Tab?</label>
                                    <select name="star_url_open_in_another_tab" id="star_url_open_in_another_tab" class="form-control select" >
                                        <option {{ get_settings('star_url_open_in_another_tab') == 1 ? 'selected' : '' }} value="1">Yes</option>
                                        <option {{ get_settings('star_url_open_in_another_tab') == 0 ? 'selected' : '' }} value="0">No</option>
                                    </select>
                                </div>
    
                                <div class="col-md-12 form-group mb-3">
                                    <label for="star_image">Image</label>
                                    <input type="file" name="star_image" id="star_image" class="form-control dropify" data-default-file="{{ get_settings('star_image') ? asset(get_settings('star_image')) : '' }}">
                                </div>
    
                                <div class="col-md-12 form-group mb-3">
                                    <label for="star_header">Header <span class="text-danger">*</span></label>
                                    <input type="text" name="star_header" id="star_header" class="form-control" required value="{{ get_settings('star_header') }}">
                                </div>
    
                                <div class="col-md-12 form-group mb-3">
                                    <label for="star_content">Content</label>
                                    <textarea name="star_content" id="star_content" cols="30" rows="3" class="form-control">{{ get_settings('star_content') }}</textarea>
                                </div>
    
                                <div class="col-md-6 form-group mb-3">
                                    <label for="star_button_text">Button Text <span class="text-danger">*</span></label>
                                    <input type="text" name="star_button_text" id="star_button_text" class="form-control" value="{{ get_settings('star_button_text') }}">
                                </div>
    
                                <div class="col-md-6 form-group mb-3">
                                    <label for="star_button_url">Button URL</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" id="basic-addon1">{{ route('home') }}</span>
                                        <input type="text" class="form-control" name="star_button_url" id="star_button_url" value="{{ get_settings('star_button_url') }}">
                                    </div>
                                </div>
    
                                <div class="col-md-12 form-group text-end">
                                    <button type="submit" id="submit" class="btn btn-soft-success">
                                        <i class="bi bi-send"></i>
                                        Update
                                    </button>
                                    <button class="btn btn-soft-warning" style="display: none;" id="submitting"
                                            type="button"
                                            disabled>
                                        <span class="spinner-border spinner-border-sm" role="status"
                                              aria-hidden="true"></span>
                                        Loading...
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('backend/assets/js/dropify.min.js') }}"></script>
    <script>
        // Update section visibility
        $(document).on('change', 'input[type="checkbox"]', function() {
            var status = this.checked ? 1 : 0;
            var url = $(this).data('url');
            var name = $(this).attr('name');

            $.ajax({
                url: url, 
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), 
                    [name]: status
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message); 
                    }
                },
                error: function(xhr) {
                    toastr.error('An error occurred while updating the status.');
                }
            });
        });

        _componentSelect();
        _formValidation();

        $('.dropify').dropify({
            imgFileExtensions: ['png', 'svg', 'jpg', 'jpeg', 'gif', 'bmp', 'webp']
        });
    </script>
@endpush
