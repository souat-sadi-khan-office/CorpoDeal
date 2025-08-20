@php
    $total_amount = 0;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>View Customer Saved Pc - PC Builder - {{ get_settings('system_name') }}</title>
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

            <!-- processor -->
            <tr class="details">
                <td class="component">CPU</td>
                <td class="name">
                    @if ($model->processor)
                        {{ Str::limit($model->processor->name, 50) }}
                    @endif
                </td>
                <td class="price">
                    @if ($model->processor)
                        {{ format_price(convert_price(get_product_price($model->processor)['discounted_price'])) }}
                        @php
                            $total_amount += get_product_price($model->processor)['discounted_price'];
                        @endphp
                    @endif
                </td>
            </tr>
            
            <!-- CPU Cooler -->
            <tr class="details">
                <td class="component">CPU Cooler</td>
                <td class="name">
                    @if ($model->cpu_cooler)
                        {{ Str::limit($model->cpu_cooler->name, 50) }}
                    @endif
                </td>
                <td class="price">
                    @if ($model->cpu_cooler)
                        {{ format_price(convert_price(get_product_price($model->cpu_cooler)['discounted_price'])) }}
                        @php
                            $total_amount += get_product_price($model->cpu_cooler)['discounted_price'];
                        @endphp
                    @endif
                </td>
            </tr>
            
            <!-- Motherboard -->
            <tr class="details">
                <td class="component">Motherboard</td>
                <td class="name">
                    @if ($model->motherboard)
                        {{ Str::limit($model->motherboard->name, 50) }}
                    @endif
                </td>
                <td class="price">
                    @if ($model->motherboard)
                        {{ format_price(convert_price(get_product_price($model->motherboard)['discounted_price'])) }}
                        @php
                            $total_amount += get_product_price($model->motherboard)['discounted_price'];
                        @endphp
                    @endif
                </td>
            </tr>
            
            @if ($model->ram)
                @if (count($model->ram) > 0)
                    @foreach ($model->ram as $ramKey => $ram)
                        <tr class="details">
                            <td class="component">
                                Casing Cooler
                                @if ($ramKey > 0)
                                    {{ $ramKey + 1 }}
                                @endif
                            </td>
                            <td class="name">
                                @if ($ram->product)
                                    {{ Str::limit($ram->product['name'], 50) }}
                                @endif
                            </td>
                            <td class="price">
                                @if ($ram->product)
                                    {{ format_price(convert_price(get_product_price($ram->product)['discounted_price'])) }}
                                    @php
                                        $total_amount += get_product_price($ram->product)['discounted_price'];
                                    @endphp
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            @endif

            @if ($model->storage)
                @if (count($model->storage) > 0)
                    @foreach ($model->storage as $ssdKey => $ssd)
                        <tr class="details">
                            <td class="component">
                                Casing Cooler
                                @if ($ssdKey > 0)
                                    {{ $ssdKey + 1 }}
                                @endif
                            </td>
                            <td class="name">
                                @if ($ssd->product)
                                    {{ Str::limit($ssd->product['name'], 50) }}
                                @endif
                            </td>
                            <td class="price">
                                @if ($ssd->product)
                                    {{ format_price(convert_price(get_product_price($ssd->product)['discounted_price'])) }}
                                    @php
                                        $total_amount += get_product_price($ssd->product)['discounted_price'];
                                    @endphp
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            @endif
            
            <!-- graphics-card -->
            <tr class="details">
                <td class="component">Graphics Card</td>
                <td class="name">
                    @if ($model->graphics_card)
                        {{ Str::limit($model->graphics_card->name, 50) }}
                    @endif
                </td>
                <td class="price">
                    @if ($model->graphics_card)
                        {{ format_price(convert_price(get_product_price($model->graphics_card)['discounted_price'])) }}
                        @php
                            $total_amount += get_product_price($model->graphics_card)['discounted_price'];
                        @endphp
                    @endif
                </td>
            </tr>
            
            <!-- psu -->
            <tr class="details">
                <td class="component">Power Supply</td>
                <td class="name">
                    @if ($model->psu)
                        {{ Str::limit($model->psu->name, 50) }}
                    @endif
                </td>
                <td class="price">
                    @if ($model->psu)
                        {{ format_price(convert_price(get_product_price($model->psu)['discounted_price'])) }}
                        @php
                            $total_amount += get_product_price($model->psu)['discounted_price'];
                        @endphp
                    @endif
                </td>
            </tr>
            
            <!-- casing -->
            <tr class="details">
                <td class="component">Casing</td>
                <td class="name">
                    @if ($model->casing)
                        {{ Str::limit($model->casing->name, 50) }}
                    @endif
                </td>
                <td class="price">
                    @if ($model->casing)
                        {{ format_price(convert_price(get_product_price($model->casing)['discounted_price'])) }}
                        @php
                            $total_amount += get_product_price($model->casing)['discounted_price'];
                        @endphp
                    @endif
                </td>
            </tr>
            
            <!-- casing fan -->
            @if ($model->fan)
                @if (count($model->fan) > 0)
                    @foreach ($model->fan as $fanKey => $fan)
                        <tr class="details">
                            <td class="component">
                                Casing Cooler
                                @if ($fanKey > 0)
                                    {{ $fanKey + 1 }}
                                @endif
                            </td>
                            <td class="name">
                                @if ($fan->product)
                                    {{ Str::limit($fan->product['name'], 50) }}
                                @endif
                            </td>
                            <td class="price">
                                @if ($fan->product)
                                    {{ format_price(convert_price(get_product_price($fan->product)['discounted_price'])) }}
                                    @php
                                        $total_amount += get_product_price($fan->product)['discounted_price'];
                                    @endphp
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            @endif
            
            <!-- Monitor -->
            <tr class="details">
                <td class="component">Monitor</td>
                <td class="name">
                    @if ($model->monitor)
                        {{ Str::limit($model->monitor->name, 50) }}
                    @endif
                </td>
                <td class="price">
                    @if ($model->monitor)
                        {{ format_price(convert_price(get_product_price($model->monitor)['discounted_price'])) }}
                        @php
                            $total_amount += get_product_price($model->monitor)['discounted_price'];
                        @endphp
                    @endif
                </td>
            </tr>
            
            <!-- Keyboard -->
            <tr class="details">
                <td class="component">Keyboard</td>
                <td class="name">
                    @if ($model->keyboard)
                        {{ Str::limit($model->keyboard->name, 50) }}
                    @endif
                </td>
                <td class="price">
                    @if ($model->keyboard)
                        {{ format_price(convert_price(get_product_price($model->keyboard)['discounted_price'])) }}
                        @php
                            $total_amount += get_product_price($model->keyboard)['discounted_price'];
                        @endphp
                    @endif
                </td>
            </tr>
            
            <!-- Mouse -->
            <tr class="details">
                <td class="component">Mouse</td>
                <td class="name">
                    @if ($model->mouse)
                        {{ Str::limit($model->mouse->name, 50) }}
                    @endif
                </td>
                <td class="price">
                    @if ($model->mouse)
                        {{ format_price(convert_price(get_product_price($model->mouse)['discounted_price'])) }}
                        @php
                            $total_amount += get_product_price($model->mouse)['discounted_price'];
                        @endphp
                    @endif
                </td>
            </tr>
            
            <!-- Anti Virus -->
            <tr class="details">
                <td class="component">Anti Virus</td>
                <td class="name">
                    @if ($model->anti_virus)
                        {{ Str::limit($model->anti_virus->name, 50) }}
                    @endif
                </td>
                <td class="price">
                    @if ($model->anti_virus)
                        {{ format_price(convert_price(get_product_price($model->anti_virus)['discounted_price'])) }}
                        @php
                            $total_amount += get_product_price($model->anti_virus)['discounted_price'];
                        @endphp
                    @endif
                </td>
            </tr>
            
            <!-- Headphone -->
            <tr class="details">
                <td class="component">Headphone</td>
                <td class="name">
                    @if ($model->headphone)
                        {{ Str::limit($model->headphone->name, 50) }}
                    @endif
                </td>
                <td class="price">
                    @if ($model->headphone)
                        {{ format_price(convert_price(get_product_price($model->headphone)['discounted_price'])) }}
                        @php
                            $total_amount += get_product_price($model->headphone)['discounted_price'];
                        @endphp
                    @endif
                </td>
            </tr>
            
            <!-- UPS -->
            <tr class="details">
                <td class="component">UPS</td>
                <td class="name">
                    @if ($model->ups)
                        {{ Str::limit($model->ups->name, 50) }}
                    @endif
                </td>
                <td class="price">
                    @if ($model->ups)
                        {{ format_price(convert_price(get_product_price($model->ups)['discounted_price'])) }}
                        @php
                            $total_amount += get_product_price($model->ups)['discounted_price'];
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
    // window.print()
</script>
</body>
</html>