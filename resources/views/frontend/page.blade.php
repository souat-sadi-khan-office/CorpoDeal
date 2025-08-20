@extends('frontend.layouts.app', ['title' => $model->title . ' - '. get_settings('system_name') ])

@section('meta')

    <meta property="og:image:width" content="200">
    <meta property="og:image:height" content="200">
    <meta property="og:site_name" content="{{ get_settings('system_name') }}">
    
    <meta name="title" content="{{ $model->meta_title }}">
    <meta name="author" content="{{ get_settings('system_name') }} : {{ route('home') }}">
    <meta name="keywords" content="{{ $model->meta_keyword }}" />
    <meta name="description" content="{{ $model->meta_description }}">	

    <!-- For Open Graph -->
    <meta property="og:url" content="{{ route('home') }}">	
    <meta property="og:type" content="Product">
    <meta property="og:title" content="{{ $model->meta_title }}">	
    <meta property="og:description" content="{{ $model->meta_description }}">	
    <meta property="og:image" content="{{ asset($model->meta_image ? $$model->meta_image : get_settings('system_logo_dark')) }}">	

    <!-- For Twitter -->
    <meta name="twitter:card" content="Product" />
    <meta name="twitter:creator" content="{{ get_settings('system_name') }}" /> 
    <meta name="twitter:title" content="{{ $model->meta_title }}" />
    <meta name="twitter:description" content="{{ $model->meta_description }}" />	
    <meta name="twitter:site" content="{{ route('home') }}" />		
    <meta name="twitter:image" content="{{ asset($model->meta_image ? $$model->meta_image : get_settings('system_logo_dark')) }}">	
    {!! $model->meta_article_tag !!}   
@endsection

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
                            {{ $model->title }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endpush
@push('styles')
    <style>
        .description ul{
            margin-left: 20px;
            margin-bottom: 10px;
        }
    </style>
@endpush
@section('content')
<div class="main_content bg_gray py-5">

    <div class="custom-container">
        <div class="row">
            <div style="text-align: justify;" class="col-md-12 description bg_white">
                {!! $model->content !!}
            </div>
        </div>
    </div>

</div>
@endsection
@push('scripts')
    
@endpush
