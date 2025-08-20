@extends('frontend.layouts.app', ['title' => $model->site_title ])

@push('page_meta_information')

    <meta property="og:image:width" content="200">
    <meta property="og:image:height" content="200">
    <meta property="og:site_name" content="{{ get_settings('system_name') }}">

    <meta name="title" content="{{ $model->meta_title }}">
    <meta name="author" content="{{ get_settings('system_name') }} : {{ route('home') }}">
    <meta name="description" content="{{ $model->meta_description }}">

    <!-- For Open Graph -->
    <meta property="og:url" content="{{ route('home') }}">
    <meta property="og:type" content="Product">
    <meta property="og:title" content="{{ $model->meta_title }}">
    <meta property="og:description" content="{{ $model->meta_description }}">
    <meta property="og:image" content="{{ asset($model->image) }}">

    <!-- For Twitter -->
    <meta name="twitter:card" content="Product" />
    <meta name="twitter:creator" content="{{ get_settings('system_name') }}" />
    <meta name="twitter:title" content="{{ $model->meta_title }}" />
    <meta name="twitter:description" content="{{ $model->meta_description }}" />
    <meta name="twitter:site" content="{{ route('home') }}" />
    <meta name="twitter:image" content="{{ asset($model->meta_description) }}">
    {!! $model->meta_article_tag !!}
@endpush

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
                        <li class="breadcrumb-item">
                            <a href="{{ route('flash-deals') }}">
                                Flash Deals
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ $model->title }}
                        </li>
                    </ol>
                </div>
                <div class="col-md-12 text-center">
                    {!! $model->description !!}
                </div>
                <div class="col-md-8 my-3 mx-auto">
                    @if (!$isCrossedDeadline)
                        <h4 class="text-center mb-3" style="color: var(--primary-color)">Expire In</h4>
                        <div class="countdown">
                            {{-- <div class="bloc-time days" data-init-value="{{ $days }}">
                                <span class="count-title">Days</span>
                            
                                <div class="figure days days-1">
                                    <span class="top">0</span>
                                    <span class="top-back">
                                        <span>0</span>
                                    </span>
                                    <span class="bottom">0</span>
                                    <span class="bottom-back">
                                        <span>0</span>
                                    </span>
                                </div>
                        
                                <div class="figure days days-2">
                                    <span class="top">0</span>
                                    <span class="top-back">
                                        <span>0</span>
                                    </span>
                                    <span class="bottom">0</span>
                                    <span class="bottom-back">
                                        <span>0</span>
                                    </span>
                                </div>
                            </div> --}}
                            
                            <div class="bloc-time hours" data-init-value="{{ $hours }}">
                                <span class="count-title">Hours</span>
                            
                                <div class="figure hours hours-1">
                                    <span class="top">0</span>
                                    <span class="top-back">
                                        <span>0</span>
                                    </span>
                                    <span class="bottom">0</span>
                                    <span class="bottom-back">
                                        <span>0</span>
                                    </span>
                                </div>
                        
                                <div class="figure hours hours-2">
                                    <span class="top">0</span>
                                    <span class="top-back">
                                        <span>0</span>
                                    </span>
                                    <span class="bottom">0</span>
                                    <span class="bottom-back">
                                        <span>0</span>
                                    </span>
                                </div>
                            </div>
                    
                            <div class="bloc-time min" data-init-value="{{ $minutes }}">
                                <span class="count-title">Minutes</span>
                    
                                <div class="figure min min-1">
                                    <span class="top">0</span>
                                    <span class="top-back">
                                        <span>0</span>
                                    </span>
                                    <span class="bottom">0</span>
                                    <span class="bottom-back">
                                        <span>0</span>
                                    </span>        
                                </div>
                    
                                <div class="figure min min-2">
                                    <span class="top">0</span>
                                    <span class="top-back">
                                        <span>0</span>
                                    </span>
                                    <span class="bottom">0</span>
                                    <span class="bottom-back">
                                        <span>0</span>
                                    </span>
                                </div>
                            </div>
                    
                            <div class="bloc-time sec" data-init-value="{{ $seconds }}">
                                <span class="count-title">Seconds</span>
                        
                                <div class="figure sec sec-1">
                                    <span class="top">0</span>
                                    <span class="top-back">
                                        <span>0</span>
                                    </span>
                                    <span class="bottom">0</span>
                                    <span class="bottom-back">
                                        <span>0</span>
                                    </span>          
                                </div>
                        
                                <div class="figure sec sec-2">
                                    <span class="top">0</span>
                                    <span class="top-back">
                                        <span>0</span>
                                    </span>
                                    <span class="bottom">0</span>
                                    <span class="bottom-back">
                                        <span>0</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else    
                        <h4>The deadline has already passed</h4>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
@endpush
@section('content')
<div class="main_content bg_gray">
    <div class="section">
        <div class="custom-container">
            @include('frontend.components.product_list')
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"></script>
<script src="{{ asset('frontend/assets/js/pages/flash-deal.js') }}"></script>
@endpush