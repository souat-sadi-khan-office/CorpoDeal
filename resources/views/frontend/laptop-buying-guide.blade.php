@extends('frontend.layouts.app', ['title' => get_settings('laptop_buying_guide_site_title')])
@section('meta')
<meta property="og:image:width" content="200">
    <meta property="og:image:height" content="200">
    <meta property="og:site_name" content="{{ get_settings('system_name') }}">
    
    <meta name="title" content="{{ get_settings('laptop_buying_guide_meta_title') }}">
    <meta name="author" content="{{ get_settings('system_name') }} : {{ route('home') }} : {{ get_settings('system_footer_contact_email') }}">
    <meta name="description" content="{{ get_settings('laptop_buying_guide_meta_description') }}">	

    <!-- For Open Graph -->
    <meta property="og:url" content="{{ url()->current() }}">	
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ get_settings('laptop_buying_guide_meta_title') }}">	
    <meta property="og:description" content="{{ get_settings('laptop_buying_guide_meta_description') }}.">	
    <meta property="og:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">	

    <!-- For Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:creator" content="{{ get_settings('system_name') }}" /> 
    <meta name="twitter:title" content="{{ get_settings('laptop_buying_guide_meta_title') }}" />
    <meta name="twitter:description" content="{{ get_settings('laptop_buying_guide_meta_description') }}" />	
    <meta name="twitter:site" content="{{ route('home') }}" />		
    <meta name="twitter:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">
    
    {!! get_settings('laptop_buying_guide_meta_article_tag') !!}
@endsection


@push('breadcrumb')
    <div class="breadcrumb_section page-title-mini">
        <div class="custom-container">
            <div class="row align-items-center">
                <div class="col-md-12 mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">
                                <i class="linearicons-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Laptop Buying Guide
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endpush
@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/bd-wizard.css') }}">
    <style>
        /* Styles scoped to .custom-radio-group */
        .custom-radio-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .custom-radio-group .custom-radio {
            display: flex;
            align-items:flex-start;
            padding: 10px 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .custom-radio-group .custom-radio:hover {
            border-color: #FF324D;
        }

        .custom-radio-group .custom-radio input[type="radio"] {
            display: none;
        }

        .custom-radio-group .radio-description {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
            line-height: 1.4;
        }

        .custom-radio-group .radio-label {
            font-weight: bold;
            color: #333;
        }

        .custom-radio-group .custom-radio input[type="radio"]:checked + .radio-label {
            font-weight: bold;
            color: #FF324D;
        }

        .custom-radio-group .custom-radio input[type="radio"]:checked + .radio-label::before {
            content: "• ";
            color: #FF324D;
            font-size: 20px;
        }

        /* Styles scoped to .custom-checkbox-group */
        .custom-checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .custom-checkbox-group .custom-checkbox {
            display: flex;
            flex-direction: column;
            padding: 10px 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .custom-checkbox-group .custom-checkbox:hover {
            border-color: #FF324D;
        }

        .custom-checkbox-group .custom-checkbox input[type="checkbox"] {
            display: none;
        }

        .custom-checkbox-group .checkbox-label {
            font-weight: bold;
            color: #333;
        }

        .custom-checkbox-group .checkbox-description {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
            line-height: 1.4;
        }

        .custom-checkbox-group .custom-checkbox input[type="checkbox"]:checked + .checkbox-label {
            font-weight: bold;
            color: #FF324D;
        }

        .custom-checkbox-group .custom-checkbox input[type="checkbox"]:checked + .checkbox-label::before {
            content: "✔ ";
            color: #FF324D;
            font-size: 18px;
        }

        /* Styles scoped to .custom-radio-group */
        .custom-radio-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .custom-radio-group .custom-radio {
            display: flex;
            flex-direction: column;
            padding: 10px 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .custom-radio-group .custom-radio:hover {
            border-color: #FF324D;
        }

        .custom-radio-group .custom-radio input[type="radio"] {
            display: none;
        }

        .custom-radio-group .radio-label {
            font-weight: bold;
            color: #333;
        }

        .custom-radio-group .radio-description {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
            line-height: 1.4;
        }

        .custom-radio-group .custom-radio input[type="radio"]:checked + .radio-label {
            font-weight: bold;
            color: #FF324D;
        }

        .custom-radio-group .custom-radio input[type="radio"]:checked + .radio-label::before {
            content: "● ";
            color: #FF324D;
            font-size: 18px;
        }
    </style>
@endpush
@section('content')

    <div class="container pb-5">
        <main class="mx-auto bg-white p-4 mw-100">
            <div class="search-preloader">
                <div class="lds-ellipsis">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            <div style="display: none;" id="wizard" class="mx-auto laptop-bugying-guide-area">
                <h3>Budget</h3>
                <section class="text-center">
                    <h2 class="step-heading"> What's your <span>budget</span>? </h2>
                    <div class="container mt-5">
                        <div class="custom-radio-group scrollbar">
                            @if (count($laptopBudgets) > 0)
                                @foreach ($laptopBudgets as $budget)
                                    <label class="custom-radio">
                                        <input {{ Session::has('laptop_finder_budget') && Session::get('laptop_finder_budget') == $budget['id'] ? 'checked' : '' }} required="true" type="radio" name="laptop_budget_id" value="{{ $budget['id'] }}">
                                        <span class="radio-label">Up to {{ format_price(convert_price($budget['name'])) }}</span>
                                    </label>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </section>

                <h3>Purpose</h3>
                <section>
                    <h2 class="text-center step-heading"> What is the primary <span>purpose</span> of your laptop?</h2>
                    <div class="custom-checkbox-group mt-5 scrollbar">
                        @if (count($laptopPurposes) > 0)
                            @foreach ($laptopPurposes as $laptopPurpose)
                                <label class="custom-checkbox">
                                    <input {{ Session::has('laptop_finder_purpose_id') && in_array($laptopPurpose['id'], Session::get('laptop_finder_purpose_id')) ? 'checked' : '' }} type="checkbox" name="purpose" value="{{ $laptopPurpose['id'] }}">
                                    <span class="checkbox-label">{{ $laptopPurpose['name'] }}</span>
                                    <p class="checkbox-description">{{ $laptopPurpose['details'] }}</p>
                                </label>
                            @endforeach
                        @endif
                    </div>
                </section>
                
                <h3>Screen size</h3>
                <section>
                    <h2 class="text-center step-heading"> What <span>screen size</span> do you prefer?</h2>
                    <div class="custom-radio-group scrollbar mt-5">
                        @if (count($laptopScreenSizes) > 0)
                            @foreach ($laptopScreenSizes as $laptopScreenSize)
                                <label class="custom-radio">
                                    <input {{ Session::has('laptop_finder_screen_size_id') && Session::get('laptop_finder_screen_size_id') == $laptopScreenSize['id'] ? 'checked' : '' }} type="radio" name="screen_size" value="{{ $laptopScreenSize['id'] }}">
                                    <span class="radio-label">{{ $laptopScreenSize['name'] }}</span>
                                    <p class="radio-description">{{ $laptopScreenSize['details'] }}</p>
                                </label>
                            @endforeach
                        @endif
                    </div>
                </section>

                <h3>Portability</h3>
                <section>
                    <h2 class="text-center step-heading">Is <span>portability</span> important to you?</h2>
                    <div class="custom-radio-group mt-5">
                        @if (count($laptopPortables) > 0)
                            @foreach ($laptopPortables as $laptopPortable)
                                <label class="custom-radio">
                                    <input {{ Session::has('laptop_finder_portable_id') && Session::get('laptop_finder_portable_id') == $laptopPortable['id'] ? 'checked' : '' }}  type="radio" name="portability" value="{{ $laptopPortable['id'] }}">
                                    <span class="radio-label">{{ $laptopPortable['name'] }}</span>
                                    <p class="radio-description">{{ $laptopPortable['details'] }}</p>
                                </label>
                            @endforeach
                        @endif
                    </div>
                </section>

                <h3>Features</h3>
                <section>
                    <h2 class="text-center step-heading">What other <span>features</span> are you looking for in your laptop?</h2>
                    <div class="custom-checkbox-group scrollbar mt-5">
                        @if (count($laptopFeatures) > 0)
                            @foreach ($laptopFeatures as $laptopFeature)
                                <label class="custom-checkbox">
                                    <input {{ Session::has('laptop_finder_features_id') && in_array($laptopPurpose['id'], Session::get('laptop_finder_features_id')) ? 'checked' : '' }} type="checkbox" name="features" value="{{ $laptopFeature['id'] }}">
                                    <span class="checkbox-label">{{ $laptopFeature['name'] }}</span>
                                    <p class="checkbox-description">{{ $laptopFeature['details'] }}</p>
                                </label>
                            @endforeach
                        @endif
                    </div>
                </section>

            </div>
            <div class="col-md-12 mt-5 text-center">
                <button type="button" id="go-to-laptop-search" class="btn btn-fill-out rounded">Show Matched Laptops ({{ $laptopCounter }})</button>
                @if (Session::has('laptop_finder_budget') || Session::has('laptop_finder_purpose_id') || Session::has('laptop_finder_screen_size_id') || Session::has('laptop_finder_portable_id') || Session::has('laptop_finder_features_id') )
                    <button type="button" id="clear-laptop-search" class="btn btn-dark rounded">Clear Searching Parameter</button>
                @endif
            </div>
        </main>
    </div>
    <input type="hidden" id="total_available_products" value="{{ $laptopCounter }}">
@endsection

@push('scripts')
    <script src="{{ asset('frontend/assets/js/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/bd-wizard.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('.search-preloader').hide();
            $('.laptop-bugying-guide-area').show();
        })
    </script>
@endpush