@extends('frontend.layouts.app', ['title' => $model->site_title ])

@push('page_meta_information')

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
    {!! $model->meta_article_tags !!}

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
                        <li class="breadcrumb-item active">
                            {{ $model->name }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endpush
@push('styles')

@endpush
@section('content')
    <div class="section bg_gray">
        <div class="custom-container">
            <div class="row">
                <div class="col-md-12 mb-3 align-item-center">
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

                <!-- Filtering Options -->
                <aside class="col-lg-3 col-md-4 mb-6 mb-md-0">
                    <div class="offcanvas offcanvas-start offcanvas-collapse w-md-50" tabindex="-1" id="offcanvasCategory" aria-labelledby="offcanvasCategoryLabel">
                        <div class="offcanvas-header d-lg-none">
                            <h5 class="offcanvas-title" id="offcanvasCategoryLabel">Filter</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body listing-canvas pt-lg-0">
                            <div class="accordion" id="categoryFilterOptions">

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
                            </div>
                        </div>
                    </div>
                </aside>

                <section class="col-lg-9">
                    <div class="row align-items-center mb-4 pb-1">
                        <div class="d-lg-flex justify-content-between align-items-center">
                            <div class="mb-3 mb-lg-0">
                                <p class="mb-0">
                                    Showing <span class="text-dark">{{ $productCount }}</span> <b></b> out of <span class="text-dark">{{ $allProductCount }}</span>  Products
                                </p>
                            </div>

                            <!-- icon -->
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
                                            <option value="popularity">Sort by popularity</option>
                                            <option value="date">Sort by newness</option>
                                            <option value="price">Sort by price: low to high</option>
                                            <option value="price-desc">Sort by price: high to low</option>
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


        function updateActiveSort() {
            var currentSort = $('#sort-by').val();  // Get the current selected sort value
            $('.nav-link').removeClass('active');  // Remove active class from all links

            // Add active class to the correct sort option
            $('.nav-link[data-sort="' + currentSort + '"]').addClass('active');
        }

        $(document).ready(function() {

            //Price Range Filter
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
            var debounceTimeout;
            function updatePriceRange(minValue, maxValue) {
                var priceSign = $priceFilter.data('price-sign');  // Get the currency symbol

                $priceDisplay.text(priceSign + minValue + " - " + priceSign + maxValue);

                $priceFirst.val(minValue);
                $priceSecond.val(maxValue);
                clearTimeout(debounceTimeout);

                debounceTimeout = setTimeout(function () {
                    filterProducts();
                }, 1500);
            }

            // Trigger filterProducts on checkbox or select changes (including brand/specification changes)
            $('.form-check, .custom_select select').on('change', function() {
                filterProducts();
            });

            $(document).on('change', '#sort-by', function() {
                filterProducts();
            });

            // Brand filter
            $('input[name^="brand"]').on('change', function () {
                filterProducts();
            });

            //Rating Filter
            $('.rating-checkbox').on('change', function () {
                filterProducts();

            });

            // Specification filter
            $('input[name^="specification"]').on('change', function () {
                filterProducts();
            });

            // Handle sorting by clicking on nav links
            $(document).on('click', '.sort-option', function() {
                var sortBy = $(this).data('sort'); // Get the sorting value from data-sort attribute
                $('#sort-by').val(sortBy);  // Update the hidden select dropdown to match the selected sort option

                // Update the active class for the sorting links
                $('.nav-link').removeClass('active');
                $(this).addClass('active');

                // Trigger the filter function to apply new sort criteria
                filterProducts();
            });

            // Filter products function
            function filterProducts() {
                // Get checkbox values
                var in_stock = $('#in_stock_availability').is(':checked');
                var out_of_stock = $('#out_of_stock_availability').is(':checked');
                var pre_order = $('#pre_order_availability').is(':checked');
                var up_coming = $('#up_coming_availability').is(':checked');
                var price_range=[
                    $('#price_first').val(),
                    $('#price_second').val(),
                ];
                // Get the selected sort value from the hidden select dropdown
                var sortBy = $('#sort-by').val();
                var showData = $('.number-of-data-show select').val();

                var brands = [];
                var specifications = [];


                // Get selected brands
                $('input[name^="brand"]:checked').each(function () {
                    brands.push($(this).val());
                });

                // Get selected specifications
                $('input[name^="specification"]:checked').each(function () {
                    specifications.push($(this).val());
                });

                // Get category ID
                var catId = $('#routeCID').val();
                var rating=[];
                var selectedRatings = $('.rating-checkbox:checked').map(function () {
                    return parseInt($(this).val());
                }).get();

                if (selectedRatings.length > 0) {
                    var minRating = Math.min.apply(null, selectedRatings);
                    var maxRating = Math.max.apply(null, selectedRatings) + 0.99;

                    maxRating = maxRating > 5 ? 5 : maxRating;
                        rating=[
                        minRating,
                        maxRating,
                    ];
                }

                // Show loading overlay
                // $('.preloader').show();

                // Send AJAX request
                $.ajax({
                    url: '{{ route('filter.products')}}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        in_stock: in_stock == true ? in_stock : null,
                        out_of_stock: out_of_stock == true ? out_of_stock : null,
                        pre_order: pre_order == true ? pre_order : null,
                        up_coming: up_coming == true ? pre_order : null,
                        sortBy: sortBy,
                        brands: brands,
                        specifications: specifications,
                        price_range: price_range,
                        rating: rating,
                        showData: showData
                    },
                    success: function(response) {
                        // Hide loading overlay and update the product area
                        $('#product-area').html(response);
                        $('.preloader').hide();
                    },
                    error: function(xhr, status, error) {
                        $('.preloader').hide();
                    }
                });
            }
        });
    </script>
@endpush