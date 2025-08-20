@extends('frontend.layouts.app', ['title' => 'My Questions | ', get_settings('system_name')])

@push('breadcrumb')
    <div class="breadcrumb_section page-title-mini">
        <div class="custom-container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">
                                <i class="linearicons-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                Account
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Quote
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endpush

@section('content')
    <div class="section bg_gray">
        <div class="custom-container">
            <div class="row">
                @include('frontend.customer.partials.sidebar')
                <div class="col-lg-9 col-md-8 dashboard_content">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            @include('frontend.customer.partials.header')
                        </div>

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h1 class="h5">Quote History</h1>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group custom-list">
                                        @if (isset($quotes))

                                            @foreach ($quotes as $quote)
                                                <li class="list-group-item">
                                                    <div class="row">
                                                        <div class="col-md-9">
                                                            <div class="row">
                                                                <div class="col-auto">
                                                                    <img src="{{ asset($quote->product->thumb_image) }}"
                                                                        alt="One">
                                                                </div>
                                                                <div class="col">
                                                                    <p><a style="text-decoration:none;color: var(--bs-table-color-type);"
                                                                            href="{{ route('slug.handle', $quote->product->slug) }}">{{ $quote->product->name }}</a>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <p>{{ get_system_date($quote->created_at->format('d M Y, h:i:s A')) }}
                                                                {{ get_system_time($quote->created_at->format('d M Y, h:i:s A')) }}
                                                            </p>
                                                        </div>
                                                        <div class="row p-3">
                                                            <div class="col-auto">
                                                                <span class="btn btn-fill-out btn-sm">Quote:</span>
                                                            </div>
                                                            <div class="col">
                                                                <p>{{ $quote->message }}</p>
                                                            </div>
                                                        </div>
                                                        @if ($quote->answer)
                                                            <div class="row">
                                                                <div class="col-auto">
                                                                    <span class="btn btn-fill-line btn-sm">Response:</span>
                                                                </div>
                                                                <div class="col">
                                                                    <p>{{ $quote->answer->message }}</p>
                                                                </div>
                                                                <div class="col-md-3">

                                                                    <p>{{ get_system_date($quote->answer->created_at->format('d M Y, h:i:s A')) }}
                                                                        {{ get_system_time($quote->answer->created_at->format('d M Y, h:i:s A')) }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <hr>
                                                </li>
                                            @endforeach
                                        @else
                                            <p>No Quotes Found</p>
                                        @endif

                                    </ul>
                                    @include('frontend.components.paginate', ['products' => $quotes])

                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
