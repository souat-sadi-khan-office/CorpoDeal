@php
    $breadCrumbCounter = 1;
    $schemaBreadcrumbs = [
        [
            '@type' => 'ListItem',
            'position' => $breadCrumbCounter,
            'name' => 'Home',
            'item' => route('home')
        ]
    ];

    foreach ($breadcrumb as $category) { 
        $schemaBreadcrumbs[] = [
            '@type' => 'ListItem',
            'position' => ++$breadCrumbCounter,
            'name' => $category->name,
            'item' => route('slug.handle', ['slug' => $category->slug])
        ];
    }

    $schemaBreadcrumbsJson = json_encode($schemaBreadcrumbs);

    $productSchemaArray = [];
    foreach ($products as $key => $product) {
        $productSchemaArray[] = [
            '@type'     => 'ListItem',
            'position'  => ++$key,
            'url'       =>  route('slug.handle', $product['slug']),
            'item'      => [
                '@type' => 'Product',
                'name'  => $product['name'],
                'image' => asset($product['thumb_image']),
                'description' => "Dell laptop with Intel i5, 8GB RAM, and SSD storage.",
                'sku'   => $product['sku'],
                "offers" => [
                    "@type"=> "Offer",
                    "priceCurrency" => "BDT",
                    "price" => number_format(convert_price($product['unit_price']), 2, '.', ''),
                    "availability" => "https://schema.org/InStock",
                    "url" => route('slug.handle', $product['slug']),
                    "itemCondition" => "https://schema.org/NewCondition"
                ]
            ]
        ];
    }

    $schemaProductsJson = json_encode($productSchemaArray);

@endphp
@extends('frontend.layouts.app', ['title' => $model->site_title ])

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
    <meta property="og:image" content="{{ asset($model->photo) }}">

    <!-- For Twitter --> 
    <meta name="twitter:card" content="Product" />
    <meta name="twitter:creator" content="{{ get_settings('system_name') }}" />
    <meta name="twitter:title" content="{{ $model->meta_title }}" />
    <meta name="twitter:description" content="{{ $model->meta_description }}" />
    <meta name="twitter:site" content="{{ route('home') }}" />
    <meta name="twitter:image" content="{{ asset($model->photo) }}">
    
    <!-- For Schema --> 
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "CollectionPage",
            "name": "{{ $model->name }}",
            "description": "{{ $model->meta_description }}",
            "url": "{{ url()->current() }}",
            "breadcrumb": {
                "@type": "BreadcrumbList",
                "itemListElement": {!! $schemaBreadcrumbsJson !!}
            },
            "mainEntity": {
                "@type": "ItemList",
                "name": "{{ $model->name }} Products",
                "itemListOrder": "http://schema.org/ItemListOrderAscending",
                "numberOfItems": {{ count($products) }},
                "itemListElement": {!! $schemaProductsJson !!}
            }
        }
    </script>
    {{-- {!! $model->meta_article_tag !!} --}}
@endsection
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        .custom-carousel-btn {
            width: 25px;
            height: 25px;
            background: #fff;
            border-radius: 50%;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 5;
            transition: all 0.3s ease;
        }

        .custom-carousel-btn:hover {
            background: var(--primary-color);
            color: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        }

        .carousel-control-prev {
            left: -10px;
        }
        .carousel-control-next {
            right: -10px;
        }

        .custom-carousel-btn i {
            font-size: 14px;
            line-height: 1;
        }
    </style>
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
                        @foreach ($breadcrumb as $category)
                            <li class="breadcrumb-item">
                                <a href="{{ route('slug.handle', ['slug' => $category->slug]) }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                        <li class="breadcrumb-item active">
                            {{ $model->name }}
                        </li>
                    </ol>
                </div>

                <div class="col-md-12 listing-headers">
                    @if ($model->header)
                        <h1>{{ $model->header }}</h1>
                    @endif
                    @if ($model->short_description)
                        <p>{!! $model->short_description !!}</p>
                    @endif
                </div>

                {{-- <div class="sub-categories col-md-12">
                    @if ($model->children)
                        @foreach ($model->children as $sub_category)
                            <a class="btn btn-dark btn-sm" href="{{ $sub_category->slug }}">
                                {{ $sub_category->name }}
                            </a>
                        @endforeach
                    @endif
                </div> --}}

                <div class="mt-10">
                    @if ($model->children)
                        <div id="categoryCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3000">
                            <div class="carousel-inner">
                                @foreach ($model->children->chunk(6) as $chunkIndex => $chunk)
                                    <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                                        <div class="row g-3">
                                            @foreach ($chunk as $sub_category)
                                                <div class="col-6 border col-md-4 col-lg-2">
                                                    <div class="pt-2 box-category hover-up text-center">
                                                        <div class="text-info">
                                                            <a class="font-sm color-gray-900 font-bold" href="{{ $sub_category->slug }}">
                                                                {{ $sub_category->name }}
                                                            </a>
                                                            <p class="mb-0 font-xs color-gray-500">
                                                                {{ $sub_category->getAllProductsCount() }} products
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Professional Rounded Navigation -->
                            <button class="carousel-control-prev custom-carousel-btn" type="button" data-bs-target="#categoryCarousel" data-bs-slide="prev">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <button class="carousel-control-next custom-carousel-btn" type="button" data-bs-target="#categoryCarousel" data-bs-slide="next">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endpush

@section('content')
    <div class="section bg_gray pt-2">
        <div class="custom-container">
            <div class="row">
                <div class="col-md-12 mb-3 align-item-center">

                    @if ($after_breadcrumb_section_banners = $model->banners()->where('position', 'after_breadcrumb_section')->get())
                        @foreach ($after_breadcrumb_section_banners as $after_breadcrumb_section_banner)
                            <div class="mb-3 position-relative">
                                <img  src="{{ asset($after_breadcrumb_section_banner->picture) }}" alt="{{ $after_breadcrumb_section_banner->name }}" class="img-fluid rounded">
                            </div>
                        @endforeach
                    @endif

                    <div class="card bg-light border-0">
                        <div class="card-body p-0">
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flush-headingOne">
                                        <button class="bg-light accordion-button collapsed px-3 pt-3 pb-2" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                            <h1 class="mb-0 fs-3">{{ $model->name }}</h1>
                                        </button>
                                    </h2>
                                    <div id="flush-collapseOne" class="bg-light accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body listing-details">
                                            {!! $model->description !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">

                @if ($after_title_and_description_banners = $model->banners()->where('position', 'after_title_and_description')->get())
                    @foreach ($after_title_and_description_banners as $after_title_and_description_banner)
                        <div class="mb-3 position-relative">
                            <img  src="{{ asset($after_title_and_description_banner->picture) }}" alt="{{ $after_title_and_description_banner->name }}" class="img-fluid rounded">
                        </div>
                    @endforeach
                @endif

                <!-- Filtering Options -->
                <aside class="col-lg-3 col-md-4 mb-6 mb-md-0">
                    <div class="offcanvas offcanvas-start offcanvas-collapse w-md-50" tabindex="-1" id="offcanvasCategory" aria-labelledby="offcanvasCategoryLabel">
                        <div class="offcanvas-header d-lg-none">
                            <h5 class="offcanvas-title" id="offcanvasCategoryLabel">Filter</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body listing-canvas pt-lg-0">
                            <div class="accordion" id="categoryFilterOptions">

                                @if ($on_left_sidebar_start_banners = $model->banners()->where('position', 'on_left_sidebar_start')->get())
                                    @foreach ($on_left_sidebar_start_banners as $on_left_sidebar_start_banner)
                                        <div class="mb-3 position-relative">
                                            <img style="max-height: 450px;" src="{{ asset($on_left_sidebar_start_banner->picture) }}" alt="{{ $on_left_sidebar_start_banner->name }}" class="img-fluid rounded">
                                        </div>
                                    @endforeach
                                @endif

                                <div class="widget mb-3">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flash-price-range">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#price-range-filter" aria-expanded="false" aria-controls="price-range-filter">
                                                Price Range
                                            </button>
                                        </h2>
                                        <div id="price-range-filter" class="accordion-collapse collapse show" aria-labelledby="flash-price-range">
                                            <div class="accordion-body">
                                                <div class="filter_price">
                                                    <div id="price_filter" data-min="0" data-max="{{round(convert_price(99999))}}" data-min-value="0" data-max-value="{{round(convert_price(99999))}}" data-price-sign="{{currency_symbol()}}"></div>
                                                    <div class="price_range">
                                                        <span>Price: <span id="flt_price"></span></span>
                                                        <input type="hidden" id="price_first">
                                                        <input type="hidden" id="price_second">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                @if ($after_left_sidebar_price_range_banners = $model->banners()->where('position', 'after_left_sidebar_price_range')->get())
                                    @foreach ($after_left_sidebar_price_range_banners as $after_left_sidebar_price_range_banner)
                                        <div class="mb-3 position-relative">
                                            <img style="max-height: 450px;" src="{{ asset($after_left_sidebar_price_range_banner->picture) }}" alt="{{ $after_left_sidebar_price_range_banner->name }}" class="img-fluid rounded">
                                        </div>
                                    @endforeach
                                @endif

                                <!-- Availability -->
                                <div class="widget mb-3">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#stock-availability-filter" aria-expanded="false" aria-controls="stock-availability-filter">
                                                Stock Availability
                                            </button>
                                        </h2>
                                        <div id="stock-availability-filter" class="accordion-collapse collapse show" aria-labelledby="flash-stock-availability">
                                            <div class="accordion-body">
                                                <ul class="list_brand">
                                                    <li>
                                                        <div class="custome-checkbox">
                                                            <input class="form-check-input form-check" type="checkbox" name="in_stock_availability" id="in_stock_availability" value="">
                                                            <label class="form-check-label" for="in_stock_availability"><span>In Stock</span></label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="custome-checkbox">
                                                            <input class="form-check-input form-check" type="checkbox" name="out_of_stock_availability" id="out_of_stock_availability" value="">
                                                            <label class="form-check-label" for="out_of_stock_availability"><span>Out of Stock</span></label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="custome-checkbox">
                                                            <input class="form-check-input form-check" type="checkbox" name="pre_order_availability" id="pre_order_availability" value="">
                                                            <label class="form-check-label" for="pre_order_availability"><span>Pre Order</span></label>
                                                        </div>
                                                    </li>
                                                    {{-- <li>
                                                        <div class="custome-checkbox">
                                                            <input class="form-check-input form-check-input" type="checkbox" name="up_coming_availability" id="up_coming_availability" value="">
                                                            <label class="form-check-label" for="up_coming_availability"><span>Up Comming</span></label>
                                                        </div>
                                                    </li> --}}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if ($after_left_sidebar_stock_banners = $model->banners()->where('position', 'after_left_sidebar_stock')->get())
                                    @foreach ($after_left_sidebar_stock_banners as $after_left_sidebar_stock_banner)
                                        <div class="mb-3 position-relative">
                                            <img style="max-height: 450px;" src="{{ asset($after_left_sidebar_stock_banner->picture) }}" alt="{{ $after_left_sidebar_stock_banner->name }}" class="img-fluid rounded">
                                        </div>
                                    @endforeach
                                @endif


                                <!-- Brand -->
                                <div class="widget mb-3">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#brand-filter" aria-expanded="false" aria-controls="brand-filter">
                                                Brand
                                            </button>
                                        </h2>
                                        <div id="brand-filter" class="accordion-collapse collapse show" aria-labelledby="flash-brand">
                                            <div class="accordion-body scrollbar">
                                                <ul class="list_brand">
                                                    @php
                                                        $brands = App\Models\Brand::select('id','name', 'slug')->where('status', 1)->orderBy('name', 'ASC')->get();
                                                    @endphp
                                                    @foreach ($brands as $brand)
                                                        <li>
                                                            <div class="custome-checkbox">
                                                                <input class="form-check-input" type="checkbox" name="brand" id="brand-{{ $brand->id }}" value="{{ $brand->id }}">
                                                                <label class="form-check-label" for="brand-{{ $brand->id }}"><span>{{ $brand->name }}</span></label>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if ($after_left_sidebar_brand_banners = $model->banners()->where('position', 'after_left_sidebar_brand')->get())
                                    @foreach ($after_left_sidebar_brand_banners as $after_left_sidebar_brand_banner)
                                        <div class="mb-3 position-relative">
                                            <img style="max-height: 450px;" src="{{ asset($after_left_sidebar_brand_banner->picture) }}" alt="{{ $after_left_sidebar_brand_banner->name }}" class="img-fluid rounded">
                                        </div>
                                    @endforeach
                                @endif

                                <!-- Rating -->
                                <div class="widget mb-3">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#rating-filter" aria-expanded="false" aria-controls="rating-filter">
                                                Rating
                                            </button>
                                        </h2>
                                        <div id="rating-filter" class="accordion-collapse collapse show" aria-labelledby="flash-rating">
                                            <div class="accordion-body">
                                                <ul class="list_brand">
                                                    <li>
                                                        <div class="custome-checkbox">
                                                            <input class="form-check-input rating-checkbox" type="checkbox" value="5" id="ratingFive">
                                                            <label class="form-check-label" for="ratingFive">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                            </label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="custome-checkbox">
                                                            <input class="form-check-input rating-checkbox" type="checkbox" value="4" id="ratingFour">
                                                            <label class="form-check-label" for="ratingFour">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="far fa-star text-warning"></i>
                                                            </label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="custome-checkbox">
                                                            <input class="form-check-input rating-checkbox" type="checkbox" value="3" id="ratingThree">
                                                            <label class="form-check-label" for="ratingThree">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="far fa-star text-warning"></i>
                                                                <i class="far fa-star text-warning"></i>
                                                            </label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="custome-checkbox">
                                                            <input class="form-check-input rating-checkbox" type="checkbox" value="2" id="ratingTwo">
                                                            <label class="form-check-label" for="ratingTwo">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="far fa-star text-warning"></i>
                                                                <i class="far fa-star text-warning"></i>
                                                                <i class="far fa-star text-warning"></i>
                                                            </label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="custome-checkbox">
                                                            <input class="form-check-input rating-checkbox" type="checkbox" value="1" id="ratingOne">
                                                            <label class="form-check-label" for="ratingOne">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="far fa-star text-warning"></i>
                                                                <i class="far fa-star text-warning"></i>
                                                                <i class="far fa-star text-warning"></i>
                                                                <i class="far fa-star text-warning"></i>
                                                            </label>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if ($after_left_sidebar_rating_banners = $model->banners()->where('position', 'after_left_sidebar_rating')->get())
                                    @foreach ($after_left_sidebar_rating_banners as $after_left_sidebar_rating_banner)
                                        <div class="mb-3 position-relative">
                                            <img style="max-height: 450px;" src="{{ asset($after_left_sidebar_rating_banner->picture) }}" alt="{{ $after_left_sidebar_rating_banner->name }}" class="img-fluid rounded">
                                        </div>
                                    @endforeach
                                @endif

                                @if ($keys = \App\Models\SpecificationKey::where('status', 1)->whereIn('category_id', $categoryIdArray)->get())
                                    @foreach($keys as $key)
                                        @if ($types = $key->types->where('status', 1)->where('show_on_filter', 1))
                                            @foreach ($types as $type)
                                                <div class="widget mb-3">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#{{ Str::slug($type->name) }}-filter" aria-expanded="false" aria-controls="{{ Str::slug($type->name) }}-filter">
                                                                {{ $type->filter_name }}
                                                            </button>
                                                        </h2>
                                                        <div id="{{ Str::slug($type->name) }}-filter" class="accordion-collapse collapse show" aria-labelledby="flash-{{ Str::slug($type->name) }}">
                                                            <div class="accordion-body scrollbar">
                                                                <ul class="list_brand">
                                                                    @if ($attributes = $type->attributes->where('status', 1))
                                                                        @foreach ($attributes as $attr)
                                                                            <li>
                                                                                <div class="custome-checkbox">
                                                                                    <input class="form-check-input" type="checkbox" name="specification" id="specification-{{ $attr->id }}" value="{{ $attr->id }}">
                                                                                    <label class="form-check-label" for="specification-{{ $attr->id }}"><span>{{ $attr->name }}</span></label>
                                                                                </div>
                                                                            </li>
                                                                        @endforeach
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                        @endif
                                    @endforeach
                                @endif

                                @if ($after_left_sidebar_specification_key_banners = $model->banners()->where('position', 'after_left_sidebar_specification_key')->get())
                                    @foreach ($after_left_sidebar_specification_key_banners as $after_left_sidebar_specification_key_banner)
                                        <div class="mb-3 position-relative">
                                            <img style="max-height: 450px;" src="{{ asset($after_left_sidebar_specification_key_banner->picture) }}" alt="{{ $after_left_sidebar_specification_key_banner->name }}" class="img-fluid rounded">
                                        </div>
                                    @endforeach
                                @endif

                                @if ($on_left_sidebar_footer_banners = $model->banners()->where('position', 'on_left_sidebar_footer')->get())
                                    @foreach ($on_left_sidebar_footer_banners as $on_left_sidebar_footer_banner)
                                        <div class="mb-3 position-relative">
                                            <img style="max-height: 450px;" src="{{ asset($on_left_sidebar_footer_banner->picture) }}" alt="{{ $on_left_sidebar_footer_banner->name }}" class="img-fluid rounded">
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                        </div>
                    </div>
                </aside>

                <section class="col-lg-9">
                    @if ($on_right_sidebar_top_banners = $model->banners()->where('position', 'on_right_sidebar_top')->get())
                        @foreach ($on_right_sidebar_top_banners as $on_right_sidebar_top_banner)
                            <div class="mb-3 position-relative">
                                <img style="max-height: 450px;" src="{{ asset($on_right_sidebar_top_banner->picture) }}" alt="{{ $on_right_sidebar_top_banner->name }}" class="img-fluid rounded">
                            </div>
                        @endforeach
                    @endif

                    <div class="row align-items-center mb-4 pb-1">
                        <div class="d-lg-flex justify-content-between align-items-center">
                            <div class="mb-3 mb-lg-0">
                                <p class="mb-0">
                                    {{-- Showing <span class="text-dark">{{ $productCount }}</span> <b></b> out of <span class="text-dark">{{ $allProductCount }}</span>  Products --}}
                                    Showing <span class="text-dark">{{ $productCount . ' '. $model->name }}</span> Products. 
                                </p>
                            </div>

                            <div class="d-md-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <a href="javascript:;" class="shorting_icon grid active">
                                            <i class="fas fa-th-large"></i>
                                        </a>
                                        <a href="javascript:;" class="shorting_icon list">
                                            <i class="fas fa-list"></i>
                                        </a>
                                    </div>
                                    <div class="ms-2 d-lg-none">
                                        <a class="btn btn-outline-gray-400 text-muted" data-bs-toggle="offcanvas" href="#offcanvasCategory" role="button" aria-controls="offcanvasCategory">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter me-2">
                                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                            </svg>
                                            Filters
                                        </a>
                                    </div>
                                </div>

                                <div class="d-flex mt-2 mt-lg-0">
                                    <div class="mobile-full-width">
                                        <select id="sort-by" class="form-control form-control-sm">
                                            <option value="popularity">Sort by Popularity</option>
                                            <option value="date">Sort by Newest</option>
                                            <option value="price">Sort by Price: Low to High</option>
                                            <option value="price-desc">Sort by Price: High to Low</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="shop_container grid" id="product-area">
                        @include('frontend.components.product_list')

                        @include('frontend.components.paginate')
                    </div>
                </section>
            </div>
        </div>
    </div>
<input type="hidden" id="sort-by" value="0">
<input type="hidden" id="routeCID" name="routeCID" value="{{$model->id}}">
@endsection
@php
    $currentUrl = url()->current();
@endphp
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new Swiper(".categorySwiper", {
                slidesPerView: "auto",
                spaceBetween: 15,
                loop: false,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                breakpoints: {
                    640: { slidesPerView: 3 },
                    768: { slidesPerView: 5 },
                    1024: { slidesPerView: 7 },
                }
            });
        });

        $(document).ready(function () {
            var params = new URLSearchParams(window.location.search);

            // Availability filters restore
            if (params.get('in_stock') == 1) {
                $('#in_stock_availability').prop('checked', true);
            }
            if (params.get('out_of_stock') == 1) {
                $('#out_of_stock_availability').prop('checked', true);
            }
            if (params.get('pre_order') == 1) {
                $('#pre_order_availability').prop('checked', true);
            }
            if (params.get('up_coming') == 1) {
                $('#up_coming_availability').prop('checked', true);
            }

            // Sort restore
            if (params.get('sort')) {
                $('#sort-by').val(params.get('sort'));
            }

            // Show restore
            if (params.get('show')) {
                $('.number-of-data-show select').val(params.get('show'));
            }

            // Brand restore
            var brands = params.getAll('brand[]');
            brands.forEach(function (b) {
                $('input[name^="brand"][value="' + b + '"]').prop('checked', true);
            });

            // Specification restore
            var specs = params.getAll('specification[]');
            specs.forEach(function (s) {
                $('input[name^="specification"][value="' + s + '"]').prop('checked', true);
            });

            // Rating restore
            var ratings = params.getAll('rating[]');
            ratings.forEach(function (r) {
                $('.rating-checkbox[value="' + r + '"]').prop('checked', true);
            });
            
            var $priceFilter = $('#price_filter');
            var minPrice = parseInt($priceFilter.data('min-value'));
            var maxPrice = parseInt($priceFilter.data('max-value'));
            var $priceDisplay = $('#flt_price');
            var $priceFirst = $('#price_first');
            var $priceSecond = $('#price_second');

            $priceFilter.slider({
                range: true,
                min: minPrice,
                max: maxPrice,
                values: [minPrice, maxPrice],
                slide: function (event, ui) {
                    updatePriceRange(ui.values[0], ui.values[1]);
                }
            });

            function updatePriceRange(minValue, maxValue) {
                var priceSign = $priceFilter.data('price-sign');
                $priceDisplay.text(priceSign + minValue + " - " + priceSign + maxValue);
                $priceFirst.val(minValue);
                $priceSecond.val(maxValue);
                applyFilters();
            }

            $('.form-check, .custom_select select, #sort-by, .rating-checkbox, input[name^="brand"], input[name^="specification"]')
                .on('change', applyFilters);

            $(document).on('click', '.sort-option', function () {
                var sortBy = $(this).data('sort');
                $('#sort-by').val(sortBy);
                $('.nav-link').removeClass('active');
                $(this).addClass('active');
                applyFilters();
            });

            function applyFilters() {
                var params = new URLSearchParams(window.location.search);

                // Availability filters
                if ($('#in_stock_availability').is(':checked')) {
                    params.set('in_stock', 1);
                } else {
                    params.delete('in_stock');
                }

                if ($('#out_of_stock_availability').is(':checked')) {
                    params.set('out_of_stock', 1);
                } else {
                    params.delete('out_of_stock');
                }

                if ($('#pre_order_availability').is(':checked')) {
                    params.set('pre_order', 1);
                } else {
                    params.delete('pre_order');
                }

                if ($('#up_coming_availability').is(':checked')) {
                    params.set('up_coming', 1);
                } else {
                    params.delete('up_coming');
                }

                // Price filter (only if changed)
                let minPrice = $('#price_first').val();
                let maxPrice = $('#price_second').val();
                if (minPrice && maxPrice) {
                    params.set('price_min', minPrice);
                    params.set('price_max', maxPrice);
                } else {
                    params.delete('price_min');
                    params.delete('price_max');
                }

                // Sort filter (only if selected)
                let sort = $('#sort-by').val();
                if (sort) {
                    params.set('sort', sort);
                } else {
                    params.delete('sort');
                }

                // Show filter (only if selected)
                let show = $('.number-of-data-show select').val();
                if (show) {
                    params.set('show', show);
                } else {
                    params.delete('show');
                }

                // Brand filter
                var brands = $('input[name^="brand"]:checked').map(function () {
                    return $(this).val();
                }).get();
                params.delete('brand[]');
                if (brands.length) {
                    brands.forEach(b => params.append('brand[]', b));
                }

                // Specification filter
                var specs = $('input[name^="specification"]:checked').map(function () {
                    return $(this).val();
                }).get();
                params.delete('specification[]');
                if (specs.length) {
                    specs.forEach(s => params.append('specification[]', s));
                }

                // Rating filter
                var ratings = $('.rating-checkbox:checked').map(function () {
                    return $(this).val();
                }).get();
                params.delete('rating[]');
                if (ratings.length) {
                    ratings.forEach(r => params.append('rating[]', r));
                }

                // Redirect with only selected filters
                window.location = window.location.pathname + '?' + params.toString();
            }

        });

        // function updatePriceRange(minValue, maxValue) {
        //     var priceSign = $priceFilter.data('price-sign');

        //     $priceDisplay.text(priceSign + minValue + " - " + priceSign + maxValue);

        //     $priceFirst.val(minValue);
        //     $priceSecond.val(maxValue);

        //     clearTimeout(debounceTimeout);
        //     debounceTimeout = setTimeout(function () {
        //         let params = new URLSearchParams(window.location.search);
        //         params.set('price_min', minValue);
        //         params.set('price_max', maxValue);
        //         window.location.search = params.toString();
        //     }, 500);
        // }


    </script>
@endpush
