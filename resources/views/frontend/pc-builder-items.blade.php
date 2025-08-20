@extends('frontend.layouts.app', ['title' => 'Choose '. $item . ' | '. get_settings('system_name')  ])

@push('page_meta_information')
    <meta property="og:image:width" content="200">
    <meta property="og:image:height" content="200">
    <meta property="og:site_name" content="{{ get_settings('system_name') }}">
    
    <meta name="title" content="{{ 'Choose '. $item . ' | '. get_settings('system_name') }}">
    <meta name="author" content="{{ get_settings('system_name') }} : {{ route('home') }}">
    <meta name="keywords" content="{{ 'Choose '. $item . ' | '. get_settings('system_name') }}" />
    <meta name="description" content="{{ 'Choose '. $item . ' | '. get_settings('system_name')}}">	
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
                            <a href="{{ route('pc-builder') }}">PC Builder</a>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ $item }}
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
                <div class="col-lg-9">
                    <div class="row align-items-center mb-4 pb-1">
                        <div class="col-12">
                            <div class="product_header bg_white px-3 py-2">
                                <div class="product_header_left">
                                    <a class="btn btn-fill-line btn-sm" href="{{ route('pc-builder') }}">
                                        <i class="fas fa-long-arrow-alt-left"></i>
                                        Back
                                    </a>

                                    {{-- <input type="text" id="search" class="form-control"> --}}
                                </div>
                                <div class="product_header_right">
                                    {{-- <div class="custom_select">
                                        <select id="sort-by" class="form-control form-control-sm">
                                            <option value="popularity">Sort by Default</option>
                                            <option value="price">Sort by price: low to high</option>
                                            <option value="price-desc">Sort by price: high to low</option>
                                        </select>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div> 
                    @if ($item == 'motherboard')
                        <div class="shop_container mb-4 pb-1 bg-white">
                            <div class="row">
                                <div class="col-md-12 p-3 px-4">
                                    @php
                                        $condition = Session::get('pc_builder_item_cpu')['condition'];
                                    @endphp
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <b>Here Motherboards are fetching records against your CPU: </b>
                                        </li>
                                        <li class="list-group-item">
                                            <b>CPU Brand</b>: {{ ucfirst($condition['cpu_brand']) }}
                                        </li>
                                        <li class="list-group-item">
                                            <b>CPU Generation</b>: {{ $condition['cpu_generation'] }}<sup>th</sup> Generation.
                                        </li>
                                        <li class="list-group-item">
                                            <b>Socket Type</b>: {{ $condition['socket_type'] }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($item == 'ram')
                        <div class="shop_container mb-4 pb-1 bg-white">
                            <div class="row">
                                <div class="col-md-12 p-3 px-4">
                                    @php
                                        $condition = Session::get('pc_builder_item_motherboard')['condition'];
                                    @endphp
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <b>Here Ram are showing against your motherboard: </b>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Supported Ram type</b>: {{ $condition['mb_supported_memory_type'] }}
                                        </li>
                                        <li class="list-group-item">
                                            <b>XMP Support</b>: {{ $condition['mb_xmp_support'] == 1 ? 'Yes' : 'No' }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($item == 'ssd')
                        <div class="shop_container mb-4 pb-1 bg-white">
                            <div class="row">
                                <div class="col-md-12 p-3 px-4">
                                    @php
                                        $condition = Session::get('pc_builder_item_motherboard')['condition'];
                                        // dd($condition);
                                    @endphp
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <b>Here Ram are showing against your motherboard: </b>
                                        </li>
                                        <li class="list-group-item">
                                            <b>M.2 Support</b>: {{ $condition['mb_m2_storage_support'] == 1 ? 'Yes' : 'No' }}
                                        </li>
                                        <li class="list-group-item">
                                            <b>Number of M.2 Support</b>: {{ $condition['mb_number_of_m2_support'] }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($item == 'gc')
                        <div class="shop_container mb-4 pb-1 bg-white">
                            <div class="row">
                                <div class="col-md-12 p-3 px-4">
                                    @php
                                        $condition = Session::get('pc_builder_item_motherboard')['condition'];
                                    @endphp
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <b>Here Ram are showing against your motherboard: </b>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Supported Casing</b>: {{ $condition['mb_form_factor'] }}
                                        </li>
                                        <li class="list-group-item">
                                            <b>Supported PCIe slot</b>: {{ $condition['mb_pcie_slot'] }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="shop_container list" id="product-area">
                        @include('frontend.components.product_list', ['listing_type' => '1'])

                        {{-- @include('frontend.components.paginate') --}}

                    </div>
                </div>

                <!-- Filtering Options -->
                <div id="column-left" class="col-lg-3 order-lg-first mt-4 pt-2 mt-lg-0 pt-lg-0">
                    <span class="lc-close">
                        <i class="fas fa-times"></i>
                    </span>
                    <div class="sidebar filters">
                        <div class="accordion" id="categoryFilterOptions">
                            <div class="widget">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flash-price-range">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#price-range-filter" aria-expanded="false" aria-controls="price-range-filter">
                                            Price Range
                                        </button>
                                    </h2>
                                    <div id="price-range-filter" class="accordion-collapse collapse show" aria-labelledby="flash-price-range">
                                        <div class="accordion-body">
                                            <div class="filter_price">
                                                <div id="price_filter" data-min="0" data-max="50000" data-min-value="0" data-max-value="50000" data-price-sign="$"></div>
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

                            @if ($keys = \App\Models\SpecificationKey::where('status', 1)->whereIn('category_id', $categoryIdArray)->get())
                                @foreach($keys as $key) 
                                    @if ($types = $key->types->where('status', 1)->where('show_on_filter', 1))
                                        @foreach ($types as $type)
                                            <div class="widget">
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
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<input type="hidden" id="sort-by" value="0">
@endsection
@push('scripts')
    <script>

        function updateActiveSort() {
            var currentSort = $('#sort-by').val();
            $('.nav-link').removeClass('active');

            $('.nav-link[data-sort="' + currentSort + '"]').addClass('active');
        }

        $(document).ready(function() {
            $('.form-check, .custom_select select').on('change', function() {
                filterProducts();
            });

            $('input[name^="brand"]').on('change', function () {
                filterProducts();
            });

            $('input[name^="specification"]').on('change', function () {
                filterProducts();
            });

            $(document).on('click', '.sort-option', function() {
                var sortBy = $(this).data('sort');
                $('#sort-by').val(sortBy);
                
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

                // Get the selected sort value from the hidden select dropdown
                var sortBy = $('#sort-by').val();
                var showData = $('.number-of-data-show select').val();

                var specifications = [];

                // Get selected specifications
                $('input[name^="specification"]:checked').each(function () {
                    specifications.push($(this).val());
                });

                // Get category ID
                var catId = $('#routeCID').val();

                // Show loading overlay
                $('.preloader').show();

                // Send AJAX request
                $.ajax({
                    url: '{{ route('pc-builder.choose', 'item')}}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        category_id: catId,
                        sortBy: sortBy,
                        specifications: specifications,
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