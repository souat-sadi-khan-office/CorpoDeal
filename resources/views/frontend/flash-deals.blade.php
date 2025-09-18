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

    <div class="section" style="padding: 20px 0px;">
        <div class="custom-container">
            <div class="row">
                @if (count($deals) > 0)
                    @foreach ($deals as $deal)
                        <div class="col-md-6">
                            <div class="blog_post blog_style1 box_shadow1 p-3 rounded bg-white h-100">
                                <div class="row g-3 align-items-center">
                                    <!-- Left Side Image -->
                                    <div class="col-md-5">
                                        <div class="blog_img overflow-hidden rounded">
                                            <img src="{{ asset($deal->image) }}" alt="{{ $deal->name }}" class="img-fluid w-100 h-auto object-fit-cover">
                                        </div>
                                    </div>

                                    <!-- Right Side Content -->
                                    <div class="col-md-7">
                                        <div class="blog_text text-start">

                                            <!-- Title & Content -->
                                            <h5 class="blog_title mb-1">{{ $deal->title }}</h5>
                                            <p class="mb-2 text-muted small">{!! nl2br($deal->short_content) !!}</p>

                                            <!-- Countdown -->
                                            <div class="countdown_time countdown_style4 mb-3"
                                                     data-time="{{ date('Y-m-d H:i:s',strtotime("{$deal->starting_time} + {$deal->deadline_time} {$deal->deadline_type}"))}}">
                                                <div class="d-flex gap-2 flex-wrap">
                                                    @foreach(['days' => 'Days', 'hours' => 'Hours', 'minutes' => 'Minutes', 'seconds' => 'Seconds'] as $unit => $label)
                                                        <div class="countdown_box text-center">
                                                            <div class="countdown-wrap px-2">
                                                                <span class="countdown {{ $unit }}">00</span>
                                                                <div class="cd_text small text-muted">{{ $label }}</div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- CTA -->
                                            <a href="{{ route('slug.handle', $deal->slug) }}"
                                            class="btn btn-sm btn-fill-out rounded view py-2 px-3">
                                                View Details
                                            </a>
                                        </div>
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
            /* font-size: 24px!important; */
            color: var(--primary-color)!important;
        }
        .cd_text{
            font-size: 14px;
        }
        .countdown_style4 .countdown_box .countdown-wrap {
            display: flex;
            padding: 10px !important;
            flex-wrap: wrap;
            justify-content: center;
        }

        .countdown_time .countdown_box {
            background: #f7f7f7;
            border-radius: 6px;
            padding: 6px 10px;
            width: 60px;
            text-align: center;
        }

        .countdown_time .countdown {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            display: block;
        }

        .countdown_time .cd_text {
            font-size: 12px;
            color: #666;
        }

        .blog_post .blog_img img {
            border-radius: 6px;
            transition: transform 0.3s ease-in-out;
        }

        .blog_post .blog_img:hover img {
            transform: scale(1.05);
        }
        @media (max-width: 767.98px) {
            .blog_post .row {
                flex-direction: column;
            }

            .blog_post .col-md-5,
            .blog_post .col-md-7 {
                max-width: 100%;
                flex: 0 0 100%;
            }

            .countdown_time .countdown_box {
                min-width: 50px;
            }
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
