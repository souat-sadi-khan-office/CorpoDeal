@extends('backend.layouts.app')
@section('title', 'Website Header Configuration')
@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/dropify.min.css') }}">
@endpush
@section('content')
<div class="row mt-5">
    <div class="col-lg-7 mx-auto col-md-7">
        <div class="card mb-4">
            <div class="card-header">
                <h1 class="h5 mb-0">Website Header Configuration</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="content_form">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 form-group mb-3">
                            <div class="align-items-start">
                                <div class="row">
                                    <div class="col-md-6 mb-3 form-group">
                                        <label for="system_show_language_switcher">Show Language Switcher</label>
                                        <select name="system_show_language_switcher" id="system_show_language_switcher" class="form-control select">
                                            <option {{ get_settings('system_show_language_switcher') == 1 ? 'selected' : '' }} value="1">Show</option>
                                            <option {{ get_settings('system_show_language_switcher') == 0 ? 'selected' : '' }} value="0">Don't show</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3 form-group">
                                        <label for="system_show_currency_switcher">Show Currency Switcher</label>
                                        <select name="system_show_currency_switcher" id="system_show_currency_switcher" class="form-control select">
                                            <option {{ get_settings('system_show_currency_switcher') == 1 ? 'selected' : '' }} value="1">Show</option>
                                            <option {{ get_settings('system_show_currency_switcher') == 0 ? 'selected' : '' }} value="0">Don't show</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3 form-group">
                                        <label for="system_sticky_header">Enable Sticky Header</label>
                                        <select name="system_sticky_header" id="system_sticky_header" class="form-control select">
                                            <option {{ get_settings('system_sticky_header') == 1 ? 'selected' : '' }} value="1">Sticky</option>
                                            <option {{ get_settings('system_sticky_header') == 0 ? 'selected' : '' }} value="0">Non Sticky</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3 form-group">
                                        <label for="system_show_top_bar_banner">Show TopBar Banner</label>
                                        <select name="system_show_top_bar_banner" id="system_show_top_bar_banner" class="form-control select">
                                            <option {{ get_settings('system_show_top_bar_banner') == 1 ? 'selected' : '' }} value="1">Yes</option>
                                            <option {{ get_settings('system_show_top_bar_banner') == 0 ? 'selected' : '' }} value="0">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3 form-group">
                                        <label for="system_topbar_banner">Topbar Banner</label>
                                        <input type="file" name="system_topbar_banner" id="system_topbar_banner" class="form-control dropify" data-default-file="{{ get_settings('system_topbar_banner') ? asset(get_settings('system_topbar_banner')) : '' }}">
                                    </div>
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="system_topbar_banner_link">Topbar Banner Link </label>
                                        <input type="url" name="system_topbar_banner_link" id="system_topbar_banner_link" value="{{ get_settings('system_topbar_banner_link') }}" class="form-control" placeholder="Link with http:// or https://">
                                    </div>
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="system_help_line_number">Helpline Number </label>
                                        <input type="text" name="system_help_line_number" id="system_help_line_number" value="{{ get_settings('system_help_line_number') }}" class="form-control">
                                    </div>

                                    <div class="col-md-12 form-group mb-3">
                                        <h5>Header Nav Menu</h5>

                                        <div class="header-nav-menu">
                                            @if (get_settings('header_menu_labels') != null)
                                                @php
                                                    $rand = rand(10000, 1000000);
                                                @endphp
                                                @foreach ( json_decode(get_settings('header_menu_labels')) as $key => $value)
                                                    <div class="row mt-3" id="data-{{ $rand}}">
                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="Label" name="header_menu_labels[]" required value="{{ $value }}">
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" placeholder="Link with http:// or https://" name="header_menu_links[]" value="{{ json_decode(App\Models\ConfigurationSetting::where('type', 'header_menu_links')->first()->value, true)[$key] }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger remove-parent" data-parent="{{ $rand }}">
                                                                <i class="bi bi-x"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <button
                                            type="button"
                                            class="btn mt-4 btn-primary btn-sm add-more">
                                            Add New
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 form-group text-end">
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
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
    <script src="{{ asset('backend/assets/js/dropify.min.js') }}"></script>
    <script>
        _componentSelect();
        _formValidation();

        $('.dropify').dropify();

        $(document).on('click', '.add-more', function() {
            let id = Math.floor((Math.random() * 10000000) + 1);
            let content = `<div class="row mt-3" id="data-`+ id +`">
                    <div class="col-4">
                        <div class="form-group">
                            <input type="text" required class="form-control" placeholder="Label" name="header_menu_labels[]">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <input type="text" required class="form-control" placeholder="Link with http:// or https://" name="header_menu_links[]">
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger remove-parent" data-parent="`+ id+`">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>`;
            $('.header-nav-menu').append(content);
        });

        $(document).on('click', '.remove-parent', function() {
            let id = $(this).data('parent');
            $('#data-'+id).remove();
        })
    </script>
@endpush