@extends('frontend.layouts.app', ['title' => get_settings('pc_builder_site_title')])
@section('meta')
<meta property="og:image:width" content="200">
    <meta property="og:image:height" content="200">
    <meta property="og:site_name" content="{{ get_settings('system_name') }}">
    
    <meta name="title" content="{{ get_settings('pc_builder_meta_title') }}">
    <meta name="author" content="{{ get_settings('system_name') }} : {{ route('home') }} : {{ get_settings('system_footer_contact_email') }}">
    <meta name="description" content="{{ get_settings('pc_builder_meta_description') }}">	

    <!-- For Open Graph -->
    <meta property="og:url" content="{{ url()->current() }}">	
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ get_settings('pc_builder_meta_title') }}">	
    <meta property="og:description" content="{{ get_settings('pc_builder_meta_description') }}.">	
    <meta property="og:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">	

    <!-- For Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:creator" content="{{ get_settings('system_name') }}" /> 
    <meta name="twitter:title" content="{{ get_settings('pc_builder_meta_title') }}" />
    <meta name="twitter:description" content="{{ get_settings('pc_builder_meta_description') }}" />	
    <meta name="twitter:site" content="{{ route('home') }}" />		
    <meta name="twitter:image" content="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}">
    
    {!! get_settings('pc_builder_meta_article_tag') !!}
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
                            PC Builder
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endpush
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/parsley.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/toastr.min.css') }}">
@endpush
@section('content')
<div class="main_content bg_gray py-5">

    <div class="custom-container">
        <div class="pcb-container" id="pc-builder-container">
            <div class="pcb-head">
                <div class="logo">
                    @php
                        $darkLogo = get_settings('system_logo_dark');
                    @endphp
                    <img class="white_dark" src="{{ $darkLogo ? asset($darkLogo) : asset('pictures/default-logo-dark.png') }}" alt="System dark logo" title="System dark logo">
                </div>
                <div class="actions">
                    <div class="all-actions">
                        <a class="action" href="{{ route('pc-builder-add-to-cart') }}">
                            <i style="font-size: 22px;" class="fas fa-shopping-basket"></i>
                            <span class="action-text">Add to Cart</span>
                        </a>
                        <a class="action" href="{{ route('save-pc') }}?back=pc-builder">
                            <i style="font-size: 22px;" class="far fa-save"></i>
                            <span class="action-text">Save PC</span>
                        </a>
                        <a class="action m-hide" href="{{ route('print-pc') }}">
                            <i style="font-size: 22px;" class="fas fa-print"></i>
                            <span class="action-text">Print</span>
                        </a>
                        <a id="screenshot-btn" class="action m-hide" href="javascript:;">
                            <i style="font-size: 22px;" class="fas fa-camera"></i>
                            <span class="action-text">Screenshot</span>
                        </a>
                    </div>
                </div>
            </div>
    
            <div class="pcb-inner-content">
                <div class="pcb-top-content">
                    <div class="left">
                        <h1 class="m-hide">PC Builder - Build Your Own Computer - {{ get_settings('system_name') }}</h1>
                        <div class="checkbox-inline">
                            <input type="checkbox" name="hide" id="input-hide">
                            <label for="input-hide">Hide Unconfigured Components</label>
                        </div>
                    </div>
                    <div class="right">
                        <div class="total-amount estimated-watt">
                            <span class="amount">{{ $total_tdp }}W</span><br>
                            <span class="items">Estimated Wattage</span>
                        </div>
                        <div class="total-amount t-price">
                            <span class="amount">{{ format_price(convert_price($total_cost)) }}</span><br>
                            <span class="items">{{ $item_counter }} Items</span>
                        </div>
                    </div>
                </div>
                <div class="pcb-content">
                    <div class="content-label">Core Components</div>
                    <!-- cpu -->
                    @if (Session::get('pc_builder_item_cpu') !== null && is_array(Session::get('pc_builder_item_cpu')) && count(Session::get('pc_builder_item_cpu')) > 0)
                        <div class="c-item selected">
                            <div class="img">
                                <a target="_blank" href="{{ route('slug.handle', Session::get('pc_builder_item_cpu')['slug']) }}">
                                    <img src="{{ Session::get('pc_builder_item_cpu')['image'] }}" alt="{{ Session::get('pc_builder_item_cpu')['name'] }}" width="80" height="80">
                                </a>
                            </div>
                            <div class="details">
                                <div class="component-name">
                                    <span>
                                        Processor 
                                        <span class="text-danger">*</span>
                                    </span>
                                </div>
                                <div class="product-name">
                                    <a target="_blank" href="{{ route('slug.handle', Session::get('pc_builder_item_cpu')['slug']) }}">{{ Str::limit(Session::get('pc_builder_item_cpu')['name'], 50) }}</a>
                                </div>
                            </div>
                            <div class="item-price">
                                <div class="price">{{ Session::get('pc_builder_item_cpu')['price'] }}</div>
                            </div>
                            <div class="actions">
                                <div class="action-items">
                                    <a class="action" href="{{ route('pc-builder.remove', 'cpu') }}" title="Remove">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    <a class="action" href="{{ route('pc-builder.choose', 'cpu') }}" title="Choose">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else   
                        <div class="c-item blank">
                            <div class="img">
                                <span class="img-ico cpu" data-src="../images/icons/cpu.svg"></span>
                            </div>
                            <div class="details">
                                <div class="component-name">
                                    <span>
                                        CPU
                                        <span class="text-danger">*</span>
                                    </span>
                                </div>
                                <div class="product-name"></div>
                            </div>
                            <div class="item-price">
                            </div>
                            <div class="actions">
                                <a class="btn btn-sm btn-line-fill" href="{{ route('pc-builder.choose', 'cpu') }}">Choose</a>
                            </div>
                        </div>
                    @endif

                    <!-- mother board -->
                    @if (Session::get('pc_builder_item_motherboard') !== null && is_array(Session::get('pc_builder_item_motherboard')) && count(Session::get('pc_builder_item_motherboard')) > 0)
                        <div class="c-item selected">
                            <div class="img">
                                <a target="_blank" href="{{ route('slug.handle', Session::get('pc_builder_item_motherboard')['slug']) }}">
                                    <img src="{{ Session::get('pc_builder_item_motherboard')['image'] }}" alt="{{ Session::get('pc_builder_item_motherboard')['name'] }}" width="80" height="80">
                                </a>
                            </div>
                            <div class="details">
                                <div class="component-name">
                                    <span>
                                        Motherboard
                                        <span class="text-danger">*</span>
                                    </span>
                                </div>
                                <div class="product-name">
                                    <a target="_blank" href="{{ route('slug.handle', Session::get('pc_builder_item_motherboard')['slug']) }}">{{ Str::limit(Session::get('pc_builder_item_motherboard')['name'], 50) }}</a>
                                </div>
                            </div>
                            <div class="item-price">
                                <div class="price">{{ Session::get('pc_builder_item_motherboard')['price'] }}</div>
                            </div>
                            <div class="actions">
                                <div class="action-items">
                                    <a class="action" href="{{ route('pc-builder.remove', 'motherboard') }}" title="Remove">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    <a class="action" href="{{ route('pc-builder.choose', 'motherboard') }}" title="Choose">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else   
                        <div class="c-item blank">
                            <div class="img">
                                <span class="img-ico motherboard"></span>
                            </div>
                            <div class="details">
                                <div class="component-name">
                                    <span>
                                        Motherboard
                                        <span class="text-danger">*</span>
                                    </span>
                                </div>
                                <div class="product-name"></div>
                            </div>
                            <div class="item-price">
                            </div>
                            <div class="actions">
                                <a class="btn btn-sm btn-line-fill" href="{{ route('pc-builder.choose', 'motherboard') }}">Choose</a>
                            </div>
                        </div>
                    @endif

                    <!-- RAM -->
                    @if (Session::get('pc_builder_item_ram') !== null && is_array(Session::get('pc_builder_item_ram')) && count(Session::get('pc_builder_item_ram')) > 0)
                        @php
                            $ramItems = Session::get('pc_builder_item_ram');
                        @endphp
                        @if (count($ramItems) > 0)
                            @foreach ($ramItems as $ramKey => $ram)
                                {{-- @dd($ram) --}}
                                <div class="c-item selected">
                                    <div class="img">
                                        <a target="_blank" href="{{ route('slug.handle', $ram['slug']) }}">
                                            <img src="{{ $ram['image'] }}" alt="{{ $ram['name'] }}" width="80" height="80">
                                        </a>
                                    </div>
                                    <div class="details">
                                        <div class="component-name">
                                            <span>
                                                RAM
                                                <span class="text-danger">*</span>
                                            </span>
                                        </div>
                                        <div class="product-name">
                                            <a target="_blank" href="{{ route('slug.handle', $ram['slug']) }}">{{ Str::limit($ram['name'], 50) }}</a>
                                            @if ($ramKey == 0 && (Session::get('pc_builder_item_motherboard')['condition']['mb_number_of_ram'] ?? 0) > count(Session::get('pc_builder_item_ram')) )
                                                <a href="{{ route('pc-builder.choose', 'ram') }}" class="btn btn-sm btn-fill-line p-1">Add Another</a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item-price">
                                        <div class="price">{{ $ram['price'] }}</div>
                                    </div>
                                    <div class="actions">
                                        <div class="action-items">
                                            <a class="action" href="{{ route('pc-builder.remove', 'ram') }}?key={{$ramKey}}" title="Remove">
                                                <i class="fas fa-times"></i>
                                            </a>
                                            <a class="action" href="{{ route('pc-builder.choose', 'ram') }}" title="Choose">
                                                <i class="fas fa-sync-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    @else   
                        <div class="c-item blank">
                            <div class="img">
                                <span class="img-ico ram"></span>
                            </div>
                            <div class="details">
                                <div class="component-name">
                                    <span>
                                        RAM
                                        <span class="text-danger">*</span>
                                    </span>
                                </div>
                                <div class="product-name"></div>
                            </div>
                            <div class="item-price">
                            </div>
                            <div class="actions">
                                <a class="btn btn-sm btn-line-fill" href="{{ route('pc-builder.choose', 'ram') }}">Choose</a>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Storare/SSD -->
                    @if (Session::get('pc_builder_item_ssd') !== null && is_array(Session::get('pc_builder_item_ssd')) && count(Session::get('pc_builder_item_ssd')) > 0)
                        @php
                            $ssdItems = Session::get('pc_builder_item_ssd');
                        @endphp
                        @if (count($ssdItems) > 0)
                            @foreach ($ssdItems as $ssdKey => $ssd)
                                <div class="c-item selected">
                                    <div class="img">
                                        <a target="_blank" href="{{ route('slug.handle', $ssd['slug']) }}">
                                            <img src="{{ $ssd['image'] }}" alt="{{ $ssd['name'] }}" width="80" height="80">
                                        </a>
                                    </div>
                                    <div class="details">
                                        <div class="component-name">
                                            <span>
                                                Storage
                                                <span class="text-danger">*</span>
                                            </span>
                                        </div>
                                        <div class="product-name">
                                            <a target="_blank" href="{{ route('slug.handle', $ssd['slug']) }}">{{ Str::limit($ssd['name'], 50) }}</a>
                                            @if ($ssdKey == 0 && (Session::get('pc_builder_item_motherboard')['condition']['mb_number_of_m2_support']) > count(Session::get('pc_builder_item_ssd')) )
                                            <br>
                                                <a href="{{ route('pc-builder.choose', 'ssd') }}" class="btn btn-sm btn-fill-line p-1">Add Another</a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item-price">
                                        <div class="price">{{ $ssd['price'] }}</div>
                                    </div>
                                    <div class="actions">
                                        <div class="action-items">
                                            <a class="action" href="{{ route('pc-builder.remove', 'ssd') }}?key={{$ssdKey}}" title="Remove">
                                                <i class="fas fa-times"></i>
                                            </a>
                                            <a class="action" href="{{ route('pc-builder.choose', 'ssd') }}" title="Choose">
                                                <i class="fas fa-sync-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    @else   
                        <div class="c-item blank">
                            <div class="img">
                                <span class="img-ico storage"></span>
                            </div>
                            <div class="details">
                                <div class="component-name">
                                    <span>
                                        Storage
                                        <span class="text-danger">*</span>
                                    </span>
                                </div>
                                <div class="product-name"></div>
                            </div>
                            <div class="item-price">
                            </div>
                            <div class="actions">
                                <a class="btn btn-sm btn-line-fill" href="{{ route('pc-builder.choose', 'ssd') }}">Choose</a>
                            </div>
                        </div>
                    @endif

                    <!-- graphics card -->
                    @if (Session::get('pc_builder_item_gc') !== null && is_array(Session::get('pc_builder_item_gc')) && count(Session::get('pc_builder_item_gc')) > 0)
                        <div class="c-item selected">
                            <div class="img">
                                <a target="_blank" href="{{ route('slug.handle', Session::get('pc_builder_item_gc')['slug']) }}">
                                    <img src="{{ Session::get('pc_builder_item_gc')['image'] }}" alt="{{ Session::get('pc_builder_item_gc')['name'] }}" width="80" height="80">
                                </a>
                            </div>
                            <div class="details">
                                <div class="component-name">
                                    <span>Graphics Card</span>
                                </div>
                                <div class="product-name">
                                    <a target="_blank" href="{{ route('slug.handle', Session::get('pc_builder_item_gc')['slug']) }}">{{ Str::limit(Session::get('pc_builder_item_gc')['name'], 50) }}</a>
                                </div>
                            </div>
                            <div class="item-price">
                                <div class="price">{{ Session::get('pc_builder_item_gc')['price'] }}</div>
                            </div>
                            <div class="actions">
                                <div class="action-items">
                                    <a class="action" href="{{ route('pc-builder.remove', 'gc') }}" title="Remove">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    <a class="action" href="{{ route('pc-builder.choose', 'gc') }}" title="Choose">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else   
                        <div class="c-item blank">
                            <div class="img">
                                <span class="img-ico graphics-card"></span>
                            </div>
                            <div class="details">
                                <div class="component-name">
                                    <span>Graphics Card</span>
                                </div>
                                <div class="product-name"></div>
                            </div>
                            <div class="item-price">
                            </div>
                            <div class="actions">
                                <a class="btn btn-sm btn-line-fill" href="{{ route('pc-builder.choose', 'gc') }}">Choose</a>
                            </div>
                        </div>
                    @endif

                    <!-- cpu_cooler -->
                    @if (Session::get('pc_builder_item_cooler') !== null && is_array(Session::get('pc_builder_item_cooler')) && count(Session::get('pc_builder_item_cooler')) > 0)
                        <div class="c-item selected">
                            <div class="img">
                                <a target="_blank" href="{{ route('slug.handle', Session::get('pc_builder_item_cooler')['slug']) }}">
                                    <img src="{{ Session::get('pc_builder_item_cooler')['image'] }}" alt="{{ Session::get('pc_builder_item_cooler')['name'] }}" width="80" height="80">
                                </a>
                            </div>
                            <div class="details">
                                <div class="component-name">
                                    <span>
                                        CPU Cooler 
                                    </span>
                                </div>
                                <div class="product-name">
                                    <a target="_blank" href="{{ route('slug.handle', Session::get('pc_builder_item_cooler')['slug']) }}">{{ Str::limit(Session::get('pc_builder_item_cooler')['name'], 50) }}</a>
                                </div>
                            </div>
                            <div class="item-price">
                                <div class="price">{{ Session::get('pc_builder_item_cooler')['price'] }}</div>
                            </div>
                            <div class="actions">
                                <div class="action-items">
                                    <a class="action" href="{{ route('pc-builder.remove', 'cooler') }}" title="Remove">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    <a class="action" href="{{ route('pc-builder.choose', 'cooler') }}" title="Choose">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else   
                        <div class="c-item blank">
                            <div class="img">
                                <span class="img-ico cpu-cooler"></span>
                            </div>
                            <div class="details">
                                <div class="component-name"><span>CPU Cooler</span></div>
                                <div class="product-name"></div>
                            </div>
                            <div class="item-price">
                            </div>
                            <div class="actions">
                                <a class="btn btn-sm btn-line-fill" href="{{ route('pc-builder.choose', 'cooler') }}">Choose</a>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Casing -->
                    @if (Session::get('pc_builder_item_casing') !== null && is_array(Session::get('pc_builder_item_casing')) && count(Session::get('pc_builder_item_casing')) > 0)
                        <div class="c-item selected">
                            <div class="img">
                                <a target="_blank" href="{{ route('slug.handle', Session::get('pc_builder_item_casing')['slug']) }}">
                                    <img src="{{ Session::get('pc_builder_item_casing')['image'] }}" alt="{{ Session::get('pc_builder_item_casing')['name'] }}" width="80" height="80">
                                </a>
                            </div>
                            <div class="details">
                                <div class="component-name">
                                    <span>Casing</span>
                                </div>
                                <div class="product-name">
                                    <a target="_blank" href="{{ route('slug.handle', Session::get('pc_builder_item_casing')['slug']) }}">{{ Str::limit(Session::get('pc_builder_item_casing')['name'], 50) }}</a>
                                </div>
                            </div>
                            <div class="item-price">
                                <div class="price">{{ Session::get('pc_builder_item_casing')['price'] }}</div>
                            </div>
                            <div class="actions">
                                <div class="action-items">
                                    <a class="action" href="{{ route('pc-builder.remove', 'casing') }}" title="Remove">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    <a class="action" href="{{ route('pc-builder.choose', 'casing') }}" title="Choose">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else   
                        <div class="c-item blank">
                            <div class="img">
                                <span class="img-ico casing"></span>
                            </div>
                            <div class="details">
                                <div class="component-name"><span>Casing</span></div>
                                <div class="product-name"></div>
                            </div>
                            <div class="item-price">
                            </div>
                            <div class="actions">
                                <a class="btn btn-sm btn-line-fill" href="{{ route('pc-builder.choose', 'casing') }}">Choose</a>
                            </div>
                        </div>
                    @endif

                    <!-- psu -->
                    @if (Session::get('pc_builder_item_psu') !== null && is_array(Session::get('pc_builder_item_psu')) && count(Session::get('pc_builder_item_psu')) > 0)
                        <div class="c-item selected">
                            <div class="img">
                                <a target="_blank" href="{{ route('slug.handle', Session::get('pc_builder_item_psu')['slug']) }}">
                                    <img src="{{ Session::get('pc_builder_item_psu')['image'] }}" alt="{{ Session::get('pc_builder_item_psu')['name'] }}" width="80" height="80">
                                </a>
                            </div>
                            <div class="details">
                                <div class="component-name">
                                    <span>Power Supply</span>
                                </div>
                                <div class="product-name">
                                    <a target="_blank" href="{{ route('slug.handle', Session::get('pc_builder_item_psu')['slug']) }}">{{ Str::limit(Session::get('pc_builder_item_psu')['name'], 50) }}</a>
                                </div>
                            </div>
                            <div class="item-price">
                                <div class="price">{{ Session::get('pc_builder_item_psu')['price'] }}</div>
                            </div>
                            <div class="actions">
                                <div class="action-items">
                                    <a class="action" href="{{ route('pc-builder.remove', 'psu') }}" title="Remove">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    <a class="action" href="{{ route('pc-builder.choose', 'psu') }}" title="Choose">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else   
                        <div class="c-item blank">
                            <div class="img">
                                <span class="img-ico power-supply"></span>
                            </div>
                            <div class="details">
                                <div class="component-name"><span>Power Supply</span></div>
                                <div class="product-name"></div>
                            </div>
                            <div class="item-price">
                            </div>
                            <div class="actions">
                                <a class="btn btn-sm btn-line-fill" href="{{ route('pc-builder.choose', 'psu') }}">Choose</a>
                            </div>
                        </div>
                    @endif
                    
                    <div class="content-label">Peripherals &amp; Others</div>

                    <!-- Keyboard -->
                    @if (Session::get('pc_builder_item_monitor') !== null && is_array(Session::get('pc_builder_item_monitor')) && count(Session::get('pc_builder_item_monitor')) > 0)
                        <div class="c-item selected">
                            <div class="img">
                                <a target="_blank" href="{{ route('slug.handle', Session::get('pc_builder_item_monitor')['slug']) }}">
                                    <img src="{{ Session::get('pc_builder_item_monitor')['image'] }}" alt="{{ Session::get('pc_builder_item_monitor')['name'] }}" width="80" height="80">
                                </a>
                            </div>
                            <div class="details">
                                <div class="component-name">
                                    <span>Keyboard</span>
                                </div>
                                <div class="product-name">
                                    <a target="_blank" href="{{ route('slug.handle', Session::get('pc_builder_item_monitor')['slug']) }}">{{ Str::limit(Session::get('pc_builder_item_monitor')['name'], 50) }}</a>
                                </div>
                            </div>
                            <div class="item-price">
                                <div class="price">{{ Session::get('pc_builder_item_monitor')['price'] }}</div>
                            </div>
                            <div class="actions">
                                <div class="action-items">
                                    <a class="action" href="{{ route('pc-builder.remove', 'monitor') }}" title="Remove">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    <a class="action" href="{{ route('pc-builder.choose', 'monitor') }}" title="Choose">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else   
                        <div class="c-item blank">
                            <div class="img">
                                <span class="img-ico monitor"></span>
                            </div>
                            <div class="details">
                                <div class="component-name"><span>Monitor</span></div>
                                <div class="product-name"></div>
                            </div>
                            <div class="item-price">
                            </div>
                            <div class="actions">
                                <a class="btn btn-sm btn-line-fill" href="{{ route('pc-builder.choose', 'monitor') }}">Choose</a>
                            </div>
                        </div>
                    @endif

                    <!-- casing_fan -->
                    @if (Session::get('pc_builder_item_casing_fan') !== null && is_array(Session::get('pc_builder_item_casing_fan')) && count(Session::get('pc_builder_item_casing_fan')) > 0)
                        @php
                            $total_fan = 0;
                            
                            if(Session::has('pc_builder_item_casing')) {
                                $total_fan = Session::get('pc_builder_item_casing')['condition']['casing_number_of_fan_front'] + Session::get('pc_builder_item_casing')['condition']['casing_number_of_fan_top'] + Session::get('pc_builder_item_casing')['condition']['casing_number_of_fan_back'];
                            }
                            
                            $fantems = Session::get('pc_builder_item_casing_fan');
                        @endphp
                        @if (count($fantems) > 0)
                            @foreach ($fantems as $fanKey => $fan)
                                <div class="c-item selected">
                                    <div class="img">
                                        <a target="_blank" href="{{ route('slug.handle', $fan['slug']) }}">
                                            <img src="{{ $fan['image'] }}" alt="{{ $fan['name'] }}" width="80" height="80">
                                        </a>
                                    </div>
                                    <div class="details">
                                        <div class="component-name">
                                            <span>
                                                Casing Fan 
                                            </span>
                                        </div>
                                        <div class="product-name">
                                            <a target="_blank" href="{{ route('slug.handle', $fan['slug']) }}">{{ Str::limit($fan['name'], 50) }}</a>
                                            @if ($fanKey == 0 && ($total_fan) > count(Session::get('pc_builder_item_casing_fan')) )
                                            <br>
                                                <a href="{{ route('pc-builder.choose', 'casing-fan') }}" class="btn btn-sm btn-fill-line p-1">Add Another</a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item-price">
                                        <div class="price">{{ $fan['price'] }}</div>
                                    </div>
                                    <div class="actions">
                                        <div class="action-items">
                                            <a class="action" href="{{ route('pc-builder.remove', 'casing-fan') }}?key={{$fanKey}}" title="Remove">
                                                <i class="fas fa-times"></i>
                                            </a>
                                            <a class="action" href="{{ route('pc-builder.choose', 'casing-fan') }}" title="Choose">
                                                <i class="fas fa-sync-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    @else
                        <div class="c-item blank">
                            <div class="img">
                                <span class="img-ico casing-cooler"></span>
                            </div>
                            <div class="details">
                                <div class="component-name"><span>Casing Cooler</span></div>
                                <div class="product-name"></div>
                            </div>
                            <div class="item-price">
                            </div>
                            <div class="actions">
                                <a class="btn btn-sm btn-line-fill" href="{{ route('pc-builder.choose', 'casing-fan') }}">Choose</a>
                            </div>
                        </div>
                    @endif

                    <!-- keyboard -->
                    @if (Session::get('pc_builder_item_keyboard') !== null && is_array(Session::get('pc_builder_item_keyboard')) && count(Session::get('pc_builder_item_keyboard')) > 0)
                        <div class="c-item selected">
                            <div class="img">
                                <a target="_blank" href="{{ route('slug.handle', Session::get('pc_builder_item_keyboard')['slug']) }}">
                                    <img src="{{ Session::get('pc_builder_item_keyboard')['image'] }}" alt="{{ Session::get('pc_builder_item_keyboard')['name'] }}" width="80" height="80">
                                </a>
                            </div>
                            <div class="details">
                                <div class="component-name">
                                    <span>Keyboard</span>
                                </div>
                                <div class="product-name">
                                    <a target="_blank" href="{{ route('slug.handle', Session::get('pc_builder_item_keyboard')['slug']) }}">{{ Str::limit(Session::get('pc_builder_item_keyboard')['name'], 50) }}</a>
                                </div>
                            </div>
                            <div class="item-price">
                                <div class="price">{{ Session::get('pc_builder_item_keyboard')['price'] }}</div>
                            </div>
                            <div class="actions">
                                <div class="action-items">
                                    <a class="action" href="{{ route('pc-builder.remove', 'keyboard') }}" title="Remove">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    <a class="action" href="{{ route('pc-builder.choose', 'keyboard') }}" title="Choose">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else   
                        <div class="c-item blank">
                            <div class="img">
                                <span class="img-ico keyboard"></span>
                            </div>
                            <div class="details">
                                <div class="component-name"><span>Keyboard</span></div>
                                <div class="product-name"></div>
                            </div>
                            <div class="item-price">
                            </div>
                            <div class="actions">
                                <a class="btn btn-sm btn-line-fill" href="{{ route('pc-builder.choose', 'keyboard') }}">Choose</a>
                            </div>
                        </div>
                    @endif

                    <!-- mouse -->
                    @if (Session::get('pc_builder_item_mouse') !== null && is_array(Session::get('pc_builder_item_mouse')) && count(Session::get('pc_builder_item_mouse')) > 0)
                        <div class="c-item selected">
                            <div class="img">
                                <a target="_blank" href="{{ route('slug.handle', Session::get('pc_builder_item_mouse')['slug']) }}">
                                    <img src="{{ Session::get('pc_builder_item_mouse')['image'] }}" alt="{{ Session::get('pc_builder_item_mouse')['name'] }}" width="80" height="80">
                                </a>
                            </div>
                            <div class="details">
                                <div class="component-name">
                                    <span>Mouse</span>
                                </div>
                                <div class="product-name">
                                    <a target="_blank" href="{{ route('slug.handle', Session::get('pc_builder_item_mouse')['slug']) }}">{{ Str::limit(Session::get('pc_builder_item_mouse')['name'], 50) }}</a>
                                </div>
                            </div>
                            <div class="item-price">
                                <div class="price">{{ Session::get('pc_builder_item_mouse')['price'] }}</div>
                            </div>
                            <div class="actions">
                                <div class="action-items">
                                    <a class="action" href="{{ route('pc-builder.remove', 'mouse') }}" title="Remove">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    <a class="action" href="{{ route('pc-builder.choose', 'mouse') }}" title="Choose">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @else   
                        <div class="c-item blank">
                            <div class="img">
                                <span class="img-ico mouse"></span>
                            </div>
                            <div class="details">
                                <div class="component-name"><span>Mouse</span></div>
                                <div class="product-name"></div>
                            </div>
                            <div class="item-price">
                            </div>
                            <div class="actions">
                                <a class="btn btn-sm btn-line-fill" href="{{ route('pc-builder.choose', 'mouse') }}">Choose</a>
                            </div>
                        </div>
                    @endif
                    
                    <!-- anti-virus -->
                    @if (Session::get('pc_builder_item_anti_virus') !== null && is_array(Session::get('pc_builder_item_anti_virus')) && count(Session::get('pc_builder_item_anti_virus')) > 0)
                        <div class="c-item selected">
                            <div class="img">
                                <a target="_blank" href="{{ route('slug.handle', Session::get('pc_builder_item_anti_virus')['slug']) }}">
                                    <img src="{{ Session::get('pc_builder_item_anti_virus')['image'] }}" alt="{{ Session::get('pc_builder_item_anti_virus')['name'] }}" width="80" height="80">
                                </a>
                            </div>
                            <div class="details">
                                <div class="component-name">
                                    <span>Headphone</span>
                                </div>
                                <div class="product-name">
                                    <a target="_blank" href="{{ route('slug.handle', Session::get('pc_builder_item_anti_virus')['slug']) }}">{{ Str::limit(Session::get('pc_builder_item_anti_virus')['name'], 50) }}</a>
                                </div>
                            </div>
                            <div class="item-price">
                                <div class="price">{{ Session::get('pc_builder_item_anti_virus')['price'] }}</div>
                            </div>
                            <div class="actions">
                                <div class="action-items">
                                    <a class="action" href="{{ route('pc-builder.remove', 'anti-virus') }}" title="Remove">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    <a class="action" href="{{ route('pc-builder.choose', 'anti-virus') }}" title="Choose">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else   
                        <div class="c-item blank">
                            <div class="img">
                                <span class="img-ico anti-virus"></span>
                            </div>
                            <div class="details">
                                <div class="component-name"><span>Anti Virus</span></div>
                                <div class="product-name"></div>
                            </div>
                            <div class="item-price">
                            </div>
                            <div class="actions">
                                <a class="btn btn-sm btn-line-fill" href="{{ route('pc-builder.choose', 'anti-virus') }}">Choose</a>
                            </div>
                        </div>
                    @endif

                    <!-- headphone -->
                    @if (Session::get('pc_builder_item_headphone') !== null && is_array(Session::get('pc_builder_item_headphone')) && count(Session::get('pc_builder_item_headphone')) > 0)
                        <div class="c-item selected">
                            <div class="img">
                                <a target="_blank" href="{{ route('slug.handle', Session::get('pc_builder_item_headphone')['slug']) }}">
                                    <img src="{{ Session::get('pc_builder_item_headphone')['image'] }}" alt="{{ Session::get('pc_builder_item_headphone')['name'] }}" width="80" height="80">
                                </a>
                            </div>
                            <div class="details">
                                <div class="component-name">
                                    <span>Headphone</span>
                                </div>
                                <div class="product-name">
                                    <a target="_blank" href="{{ route('slug.handle', Session::get('pc_builder_item_headphone')['slug']) }}">{{ Str::limit(Session::get('pc_builder_item_headphone')['name'], 50) }}</a>
                                </div>
                            </div>
                            <div class="item-price">
                                <div class="price">{{ Session::get('pc_builder_item_headphone')['price'] }}</div>
                            </div>
                            <div class="actions">
                                <div class="action-items">
                                    <a class="action" href="{{ route('pc-builder.remove', 'headphone') }}" title="Remove">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    <a class="action" href="{{ route('pc-builder.choose', 'headphone') }}" title="Choose">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else   
                        <div class="c-item blank">
                            <div class="img">
                                <span class="img-ico headphone"></span>
                            </div>
                            <div class="details">
                                <div class="component-name"><span>Headphone</span></div>
                                <div class="product-name"></div>
                            </div>
                            <div class="item-price">
                            </div>
                            <div class="actions">
                                <a class="btn btn-sm btn-line-fill" href="{{ route('pc-builder.choose', 'headphone') }}">Choose</a>
                            </div>
                        </div>
                    @endif

                    <!-- ups -->
                    @if (Session::get('pc_builder_item_ups') !== null && is_array(Session::get('pc_builder_item_ups')) && count(Session::get('pc_builder_item_ups')) > 0)
                        <div class="c-item selected">
                            <div class="img">
                                <a target="_blank" href="{{ route('slug.handle', Session::get('pc_builder_item_ups')['slug']) }}">
                                    <img src="{{ Session::get('pc_builder_item_ups')['image'] }}" alt="{{ Session::get('pc_builder_item_ups')['name'] }}" width="80" height="80">
                                </a>
                            </div>
                            <div class="details">
                                <div class="component-name">
                                    <span>UPS</span>
                                </div>
                                <div class="product-name">
                                    <a target="_blank" href="{{ route('slug.handle', Session::get('pc_builder_item_ups')['slug']) }}">{{ Str::limit(Session::get('pc_builder_item_ups')['name'], 50) }}</a>
                                </div>
                            </div>
                            <div class="item-price">
                                <div class="price">{{ Session::get('pc_builder_item_ups')['price'] }}</div>
                            </div>
                            <div class="actions">
                                <div class="action-items">
                                    <a class="action" href="{{ route('pc-builder.remove', 'ups') }}" title="Remove">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    <a class="action" href="{{ route('pc-builder.choose', 'ups') }}" title="Choose">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else   
                        <div class="c-item blank">
                            <div class="img">
                                <span class="img-ico ups"></span>
                            </div>
                            <div class="details">
                                <div class="component-name"><span>UPS</span></div>
                                <div class="product-name"></div>
                            </div>
                            <div class="item-price">
                            </div>
                            <div class="actions">
                                <a class="btn btn-sm btn-line-fill" href="{{ route('pc-builder.choose', 'ups') }}">Choose</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
@push('scripts')
    <script src="{{ asset('backend/assets/js/parsley.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/toastr.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        $(document).on('click', '#input-hide', function() {
            if($('#input-hide').is(':checked')) {
                $('.blank').hide();
            } else {
                $('.blank').show();
            }
        });

        document.getElementById('screenshot-btn').addEventListener('click', function() {
            var container = document.getElementById('pc-builder-container');
            
            html2canvas(container).then(function(canvas) {
                var link = document.createElement('a');
                link.href = canvas.toDataURL();
                link.download = 'pc-builder-screenshot.png';
                link.click();
            });
        });
    </script>
@endpush
