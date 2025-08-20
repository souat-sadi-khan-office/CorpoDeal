@extends('frontend.layouts.app', ['title' => 'The page you requested cannot be found'])
@push('page_meta_information')
    
    <link rel="canonical" href="{{ route('home') }}" />
    <meta name="referrer" content="origin">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">

    <meta name="title" content="The page you requested cannot be found | {{ get_settings('system_name') }}">
@endpush

@push('breadcrumb')
    <div class="breadcrumb_section page-title-mini">
        <div class="custom-container">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">
                                <i class="linearicons-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Page not found
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endpush

@section('content')
    <div class="main_content">
        <div class="section">
            <div class="error_wrap">
                <div class="container">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-lg-6 col-md-10 order-lg-first">
                            <div class="text-center">
                                <div class="error_txt">404</div>
                                <h5 class="mb-2 mb-sm-3">oops! The page you requested was not found!</h5> 
                                <p>The page you are looking for was moved, removed, renamed or might never existed.</p>
                                <a href="{{ route('home') }}" class="btn btn-fill-out">
                                    Back To Home
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>   
@endsection