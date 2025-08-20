@extends('frontend.layouts.app', ['title' => 'Search - '. get_settings('system_name') ])

@push('page_meta_information')

    <meta property="og:image:width" content="200">
    <meta property="og:image:height" content="200">
    <meta property="og:site_name" content="{{ get_settings('system_name') }}">
    
    <meta name="title" content="{{ $search . ' '. get_settings('sysem_name') }}">
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
                        @if ($search)
                            <li class="breadcrumb-item active">
                                {{ $search }}
                            </li>
                        @endif
                        @if ($sort)
                            <li class="breadcrumb-item active">
                                @switch($sort)
                                    @case('popularity')
                                        Sorting by Top Rated Products
                                    @break
                                    @case('featured')
                                        Sorting by Featured Products
                                    @break
                                    @case('latest')
                                        Sorting by Latest Products
                                    @break
                                    @case('on-sale')
                                        Sorting by On Sale Products
                                    @break
                                    @case('price')
                                        Sorting by Price: Low to High                                    
                                    @break
                                    @default
                                        Sorting by Price: High to Low
                                @endswitch
                            </li>
                        @endif
                        
                    </ol>
                </div>

                {{-- <div class="brands col-md-6">

                    @if (isset($brands))
                        <h6>Related Brands:</h6>
                        @foreach ($brands as $brand)
                            <a class="btn btn-sm btn-fill-out rounded" href="{{ route('slug.handle',$brand->slug) }}">
                                {{ $brand->name }}
                            </a>
                        @endforeach
                    @endif
                </div>
                <div class="categories col-md-6">

                    @if (isset($categories))
                        <h6>Related categories:</h6>
                        @foreach ($categories as $categories)
                            <a class="btn btn-sm btn-fill-out rounded" href="{{ route('slug.handle',$categories->slug) }}">
                                {{ $categories->name }}
                            </a>
                        @endforeach
                    @endif
                </div> --}}
            </div>
        </div>
    </div>
@endpush
@push('styles')
    
@endpush
@section('content')
    <div class="section bg_gray">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row align-items-center mb-4 pb-1">
                        <div class="col-12">
                            <div class="product_header py-3 bg_white">
                                <div class="product_header_left px-2">
                                    <h4><b>{{ $search }} </b></h4>
                                </div>
                                <div class="product_header_right">
                                    <div class="products_view">
                                        <a href="javascript:;" class="shorting_icon grid active">
                                            <i class="fas fa-th-large"></i>
                                        </a>
                                        <a href="javascript:;" class="shorting_icon list">
                                            <i class="fas fa-list"></i>
                                        </a>
                                    </div>
                                    <div class="custom_select">
                                        <select id="sort-by" class="form-control form-control-sm">
                                            <option {{ $sort == 'popularity' ? 'selected' : '' }} value="popularity">Sort by Top Rated</option>
                                            <option {{ $sort == 'featured' ? 'selected' : '' }} value="featured">Sort by Featured</option>
                                            <option {{ $sort == 'latest' ? 'selected' : '' }} value="latest">Sort by Latest</option>
                                            <option {{ $sort == 'on-sale' ? 'selected' : '' }} value="on-sale">Sort by On Sale</option>
                                            <option {{ $sort == 'price' ? 'selected' : '' }} value="price">Sort by price: low to high</option>
                                            <option {{ $sort == 'price-desc' ? 'selected' : '' }} value="price-desc">Sort by price: high to low</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="shop_container grid" id="product-area">
                        @include('frontend.components.product_list',compact('products'))
                    </div>
                    
                    @include('frontend.components.paginate',compact('products'))
                    
                    
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="search_text" value="{{ $search }}">
@endsection

@push('scripts')
    <script>
        $(document).on('change', '#sort-by', function() {
            let value = $(this).val();
            let searchParams = new URLSearchParams(window.location.search);

            // Update the 'sort' parameter
            searchParams.set('sort', value);

            // Construct the updated URL
            let updatedUrl = window.location.pathname + '?' + searchParams.toString();

            // Redirect to the updated URL
            window.location.href = updatedUrl;
        });
    </script>
@endpush