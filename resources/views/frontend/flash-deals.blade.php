@extends('frontend.layouts.app', ['title' => get_settings('flash_deals_site_title') ])

@section('meta')

    <meta property="og:image:width" content="200">
    <meta property="og:image:height" content="200">
    <meta property="og:site_name" content="{{ get_settings('system_name') }}">

    <meta name="title" content="{{ get_settings('flash_deals_meta_title') }}">
    <meta name="author" content="{{ get_settings('system_name') }} : {{ route('home') }}">
    <meta name="description" content="{{ get_settings('flash_deals_meta_description') }}">

    <!-- For Open Graph -->
    <meta property="og:url" content="{{ route('home') }}">
    <meta property="og:type" content="Product">
    <meta property="og:title" content="{{ get_settings('flash_deals_meta_title') }}">
    <meta property="og:description" content="{{ get_settings('flash_deals_meta_description') }}">
    <meta property="og:image" content="{{ asset(get_settings('system_logo_dark')) }}">

    <!-- For Twitter -->
    <meta name="twitter:card" content="Product" />
    <meta name="twitter:creator" content="{{ get_settings('system_name') }}" />
    <meta name="twitter:title" content="{{ get_settings('flash_deals_meta_title') }}" />
    <meta name="twitter:description" content="{{ get_settings('flash_deals_meta_description') }}" />
    <meta name="twitter:site" content="{{ route('home') }}" />
    <meta name="twitter:image" content="{{ asset(get_settings('system_logo_dark')) }}">
    {!! get_settings('flash_deals_meta_article_tag') !!}
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
                            Flash Deals
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endpush
@section('content')
<div class="main_content bg_gray">

    <div class="section">
        <div class="custom-container">
            <div class="row">
                @if (count($deals) > 0)
                    @foreach ($deals as $deal)
                        <div class="col-md-4">
                            <div class="blog_post blog_style1 box_shadow1">
                                <div class="blog_content bg-white">
                                    <div class="blog_img">
                                        <img src="{{ asset($deal->image) }}" alt="{{ $deal->name }}">
                                    </div>
                                    <div class="blog_text mt-3 text-center">
                                        <ul class="list_none d-flex blog_meta">
                                            <li class="mx-auto">
                                                <div class="countdown_time countdown_style4 mb-4"
                                                     data-time="{{ date('Y-m-d H:i:s',strtotime("{$deal->starting_time} + {$deal->deadline_time} {$deal->deadline_type}"))}}">
                                                    <div class="countdown_box">
                                                        <div class="countdown-wrap">
                                                            <span class="countdown days">00</span>
                                                            <span class="cd_text">Days</span>
                                                        </div>
                                                    </div>
                                                    <div class="countdown_box">
                                                        <div class="countdown-wrap">
                                                            <span class="countdown hours">00</span>
                                                            <span class="cd_text">Hours</span>
                                                        </div>
                                                    </div>
                                                    <div class="countdown_box">
                                                        <div class="countdown-wrap">
                                                            <span class="countdown minutes">00</span>
                                                            <span class="cd_text">Minutes</span>
                                                        </div>
                                                    </div>
                                                    <div class="countdown_box">
                                                        <div class="countdown-wrap">
                                                            <span class="countdown seconds">00</span>
                                                            <span class="cd_text">Seconds</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                        <h5 class="blog_title mb-0">
                                            {{ $deal->title }}
                                        </h5>
                                        <p>{!! nl2br($deal->short_content) !!}</p>
                                        <a  title="Visit {{ $deal->title }} Page" href="{{ route('slug.handle', $deal->slug) }}" class="btn btn-sm btn-fill-out rounded view py-2">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset("backend/assets/css/custom-animate.css")}}">
    <style>
        .countdown{
            font-size: 24px!important;
            color: var(--primary-color)!important;
        }
        .cd_text{
            font-size: 14px;
        }
        .countdown_style4 .countdown_box .countdown-wrap {
            display: flex;
            padding: 15px!important;
            flex-wrap: wrap;
            justify-content: center;
        }

    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.countdown_time').each(function() {
                var endTime = new Date($(this).data('time')).getTime();

                var countdownInterval = setInterval(() => {
                    var now = new Date().getTime();
                    var distance = endTime - now;

                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    $(this).find('.days').text(days < 10 ? '0' + days : days);
                    $(this).find('.hours').text(hours < 10 ? '0' + hours : hours);
                    $(this).find('.minutes').text(minutes < 10 ? '0' + minutes : minutes);
                    $(this).find('.seconds').text(seconds < 10 ? '0' + seconds : seconds);

                    if (distance <= 0) {
                        clearInterval(countdownInterval);
                        $(this).html("<span class='text-danger'>EXPIRED</span>");
                        $(this).closest('div.blog_text').find('.view').remove();
                    }
                }, 1000);
            });
        });

    </script>
@endpush
