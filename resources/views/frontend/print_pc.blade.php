@php
    $total_amount = 0;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Print PC - PC Builder - {{ get_settings('system_name') }}</title>
    <style type="text/css">
        *,body{margin:0}.wrapper,img{max-width:100%}*{padding:0}img{height:auto}.wrapper{width:794px;margin:0 auto}.top-area{display:flex;justify-content:center;align-items:center;margin:20px 0}.logo{margin-right:20px}.company-info h1{color:#FF324D}.address{border-top:2px solid #FF324D;margin-top:4px;line-height:24px}table{width:100%;max-width:99%;border-collapse:collapse}table>tbody>tr>td{padding:12px;border-right:1px solid #333}table>tbody>tr>td:last-child{border:0}.component-info{background:#FF324D;color:#fff;border:1px solid #FF324D}tr.details{border:1px solid #333} .price-old{ text-decoration: line-through; } .price{text-align:right}
    </style>
</head>
<body>

<div class="wrapper">
    <div class="top-area">
        <div class="logo">
            <a href="{{ route('home') }}">
                <img class="logo_dark" src="{{ get_settings('system_logo_dark') ? asset(get_settings('system_logo_dark')) : asset('pictures/default-logo-dark.png') }}" alt="System dark logo">
            </a>
        </div>
        <div class="company-info">
            <h1>{{ get_settings('system_name') }} </h1>
            <div class="address">
                <p><strong>Phone: </strong>{{ get_settings('system_footer_contact_phone') }}, <strong>Email:</strong>{{ get_settings('system_footer_contact_email') }}</p>
                <p class="web">{{ route('pc-builder') }}</p>
            </div>
        </div>
    </div>

    <div class="all-printed-component">
        <table>
            <tr class="component-info">
                <td class="component-name"><b>Component</b></td>
                <td class="product-name"><b>Product Name</b></td>
                <td class="price"><b>Price</b></td>
            </tr>

            <tr class="details">
                <td class="component">CPU</td>
                <td class="name">
                    @if (Session::has('pc_builder_item_cpu'))
                        {{ Str::limit(Session::get('pc_builder_item_cpu')['name'], 50) }}
                    @endif
                </td>
                <td class="price">
                    @if (Session::has('pc_builder_item_cpu'))
                        {{ Session::get('pc_builder_item_cpu')['price'] }}
                        @php
                            $total_amount += Session::get('pc_builder_item_cpu')['row_price'];
                        @endphp
                    @endif
                </td>
            </tr>
            
            <tr class="details">
                <td class="component">CPU Cooler</td>
                <td class="name">
                    @if (Session::has('pc_builder_item_cooler'))
                        {{ Str::limit(Session::get('pc_builder_item_cooler')['name'], 50) }}
                    @endif
                </td>
                <td class="price">
                    @if (Session::has('pc_builder_item_cooler'))
                        {{ Session::get('pc_builder_item_cooler')['price'], 50 }}
                        @php
                            $total_amount += Session::get('pc_builder_item_cooler')['row_price'];
                        @endphp
                    @endif
                </td>
            </tr>
            
            <tr class="details">
                <td class="component">Motherboard</td>
                <td class="name">
                    @if (Session::has('pc_builder_item_motherboard'))
                        {{ Str::limit(Session::get('pc_builder_item_motherboard')['name'], 50) }}
                    @endif
                </td>
                <td class="price">
                    @if (Session::has('pc_builder_item_motherboard'))
                        {{ Session::get('pc_builder_item_motherboard')['price'] }}
                        @php
                            $total_amount += Session::get('pc_builder_item_motherboard')['row_price'];
                        @endphp
                    @endif
                </td>
            </tr>
            
            @if (Session::has('pc_builder_item_ram'))
                @if (is_array(Session::get('pc_builder_item_ram')) && count(Session::get('pc_builder_item_ram')) > 0)
                    @foreach (Session::get('pc_builder_item_ram') as $ramKey => $ram)
                        <tr class="details">
                            <td class="component">
                                RAM
                                @if ($ramKey > 0)
                                    {{ $ramKey + 1 }}
                                @endif
                            </td>
                            <td class="name">
                                @if ($ram['name'])
                                    {{ Str::limit($ram['name'], 50) }}
                                @endif
                            </td>
                            <td class="price">
                                @if ($ram['price'])
                                    {{ $ram['price'] }}
                                    @php
                                        $total_amount += $ram['row_price'];
                                    @endphp
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
                
            @endif

            @if (Session::has('pc_builder_item_ssd'))
                @if (is_array(Session::get('pc_builder_item_ssd')) && count(Session::get('pc_builder_item_ssd')) > 0)
                    @foreach (Session::get('pc_builder_item_ssd') as $ssdKey => $ssd)
                        <tr class="details">
                            <td class="component">
                                Storage
                                @if ($ssdKey > 0)
                                    {{ $ssdKey + 1 }}
                                @endif
                            </td>
                            <td class="name">
                                @if ($ram['name'])
                                    {{ Str::limit($ssd['name'], 50) }}
                                @endif
                            </td>
                            <td class="price">
                                @if ($ssd['price'])
                                    {{ $ssd['price'] }}
                                    @php
                                        $total_amount += $ssd['row_price'];
                                    @endphp
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            @endif
            
            <tr class="details">
                <td class="component">Graphics Card</td>
                <td class="name">
                    @if (Session::has('pc_builder_item_gc'))
                        {{ Str::limit(Session::get('pc_builder_item_gc')['name'], 50) }}
                    @endif
                </td>
                <td class="price">
                    @if (Session::has('pc_builder_item_gc'))
                        {{ Session::get('pc_builder_item_gc')['price'] }}
                        @php
                            $total_amount += Session::get('pc_builder_item_gc')['row_price'];
                        @endphp
                    @endif
                </td>
            </tr>
            
            <tr class="details">
                <td class="component">Power Supply</td>
                <td class="name">
                    @if (Session::has('pc_builder_item_psu'))
                        {{ Str::limit(Session::get('pc_builder_item_psu')['name'], 50) }}
                    @endif
                </td>
                <td class="price">
                    @if (Session::has('pc_builder_item_psu'))
                        {{ Session::get('pc_builder_item_psu')['price'] }}
                        @php
                            $total_amount += Session::get('pc_builder_item_psu')['row_price'];
                        @endphp
                    @endif
                </td>
            </tr>
            
            <tr class="details">
                <td class="component">Casing</td>
                <td class="name">
                    @if (Session::has('pc_builder_item_casing'))
                        {{ Str::limit(Session::get('pc_builder_item_casing')['name'], 50) }}
                    @endif
                </td>
                <td class="price">
                    @if (Session::has('pc_builder_item_casing'))
                        {{ Session::get('pc_builder_item_casing')['price'] }}
                        @php
                            $total_amount += Session::get('pc_builder_item_casing')['row_price'];
                        @endphp
                    @endif
                </td>
            </tr>
            
            @if (Session::has('pc_builder_item_casing_fan'))
                @if (is_array(Session::get('pc_builder_item_casing_fan')) && count(Session::get('pc_builder_item_casing_fan')) > 0)
                    @foreach (Session::get('pc_builder_item_casing_fan') as $fanKey => $fan)
                        <tr class="details">
                            <td class="component">
                                Casing Cooler
                                @if ($fanKey > 0)
                                    {{ $fanKey + 1 }}
                                @endif
                            </td>
                            <td class="name">
                                @if ($fan['name'])
                                    {{ Str::limit($fan['name'], 50) }}
                                @endif
                            </td>
                            <td class="price">
                                @if ($fan['price'])
                                    {{ $fan['price'] }}
                                    @php
                                        $total_amount += $fan['row_price'];
                                    @endphp
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            @endif
            
            <tr class="details">
                <td class="component">Monitor</td>
                <td class="name">
                    @if (Session::has('pc_builder_item_monitor'))
                        {{ Str::limit(Session::get('pc_builder_item_monitor')['name'], 50) }}
                    @endif
                </td>
                <td class="price">
                    @if (Session::has('pc_builder_item_monitor'))
                        {{ Session::get('pc_builder_item_monitor')['price'] }}
                        @php
                            $total_amount += Session::get('pc_builder_item_monitor')['row_price'];
                        @endphp
                    @endif
                </td>
            </tr>
            
            <tr class="details">
                <td class="component">Keyboard</td>
                <td class="name">
                    @if (Session::has('pc_builder_item_keyboard'))
                        {{ Str::limit(Session::get('pc_builder_item_keyboard')['name'], 50) }}
                    @endif
                </td>
                <td class="price">
                    @if (Session::has('pc_builder_item_keyboard'))
                        {{ Session::get('pc_builder_item_keyboard')['price'] }}
                        @php
                            $total_amount += Session::get('pc_builder_item_keyboard')['row_price'];
                        @endphp
                    @endif
                </td>
            </tr>
            
            <tr class="details">
                <td class="component">Mouse</td>
                <td class="name">
                    @if (Session::has('pc_builder_item_mouse'))
                        {{ Str::limit(Session::get('pc_builder_item_mouse')['name'], 50) }}
                    @endif
                </td>
                <td class="price">
                    @if (Session::has('pc_builder_item_mouse'))
                        {{ Session::get('pc_builder_item_mouse')['price'] }}
                        @php
                            $total_amount += Session::get('pc_builder_item_mouse')['row_price'];
                        @endphp
                    @endif
                </td>
            </tr>
            
            <tr class="details">
                <td class="component">Anti Virus</td>
                <td class="name">
                    @if (Session::has('pc_builder_item_anti_virus'))
                        {{ Str::limit(Session::get('pc_builder_item_anti_virus')['name'], 50) }}
                    @endif
                </td>
                <td class="price">
                    @if (Session::has('pc_builder_item_anti_virus'))
                        {{ Session::get('pc_builder_item_anti_virus')['price'] }}
                        @php
                            $total_amount += Session::get('pc_builder_item_anti_virus')['row_price'];
                        @endphp
                    @endif
                </td>
            </tr>
            
            <tr class="details">
                <td class="component">Headphone</td>
                <td class="name">
                    @if (Session::has('pc_builder_item_headphone'))
                        {{ Str::limit(Session::get('pc_builder_item_headphone')['name'], 50) }}
                    @endif
                </td>
                <td class="price">
                    @if (Session::has('pc_builder_item_headphone'))
                        {{ Session::get('pc_builder_item_headphone')['price'] }}
                        @php
                            $total_amount += Session::get('pc_builder_item_headphone')['row_price'];
                        @endphp
                    @endif
                </td>
            </tr>
            
            <tr class="details">
                <td class="component">UPS</td>
                <td class="name">
                    @if (Session::has('pc_builder_item_ups'))
                        {{ Str::limit(Session::get('pc_builder_item_ups')['name'], 50) }}
                    @endif
                </td>
                <td class="price">
                    @if (Session::has('pc_builder_item_ups'))
                        {{ Session::get('pc_builder_item_ups')['price'] }}
                        @php
                            $total_amount += Session::get('pc_builder_item_ups')['row_price'];
                        @endphp
                    @endif
                </td>
            </tr>
            
            <tr class="details total-amount">
                <td colspan="2" class="amount-label"><b>Total:</b></td>
                <td class="amount price">{{ format_price(convert_price($total_amount)) }} </td>
            </tr>
        </table>
    </div>
</div>
<script>
    window.print()
</script>
</body>
</html>