<div class="">
    @if (count($products) > 0)
        <div style="font-weight: bold;" class="px-2 py-1 text-uppercase fs-10 text-muted bg-soft-secondary">Products</div>
        <ul class="list-group">
            @foreach ($products as $key => $product)
                <li class="list-group-item">
                    <a class="text-reset" href="{{ route('slug.handle', $product['slug']) }}">
                        <div class="row">
                            <div class="col-auto">
                                <img class="search-image" src="{{ asset($product['thumb_image']) }}" alt="{{ $product['name'] }}">
                            </div>
                            <div class="col">
                                <div class="product-name text-truncate fs-14 mb-5px">
                                    {{ Illuminate\Support\Str::limit($product['name'], 30) }}
                                </div>
                                <div class="product_price">
                                    @if (isset($product['discount_type']))
                                        <span class="price">{{ format_price(convert_price($product['discounted_price'])) }}</span>
                                        <del>{{ format_price(convert_price($product['unit_price'])) }}</del>
                                        <div class="on_sale">
                                            <span>{{ $product['discount_type'] == 'amount' ? format_price(convert_price($product['discount'])) : $product['discount'] . '%' }}
                                                Off</span>
                                        </div>
                                    @else
                                        <span class="price">{{ format_price(convert_price($product['unit_price'])) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
<div class="">
    @if (count($categories) > 0)
        <div style="font-weight: bold;" class="px-2 py-1 text-uppercase fs-10 text-muted bg-soft-secondary">Category Suggestions</div>
        <ul class="list-group list-group-raw">
            @foreach ($categories as $key => $category)
                <li class="list-group-item my-1 py-1">
                    <a class="text-reset hov-text-primary" href="{{ route('slug.handle', $category->slug) }}">{{ $category->name }}</a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
<div class="">
    @if (count($brands) > 0)
        <div style="font-weight: bold;" class="px-2 py-1 text-uppercase fs-10 text-muted bg-soft-secondary">Brand Suggestions</div>
        <ul class="list-group list-group-raw">
            @foreach ($brands as $key => $brand)
                <li class="list-group-item my-1 py-1">
                    <a class="text-reset hov-text-primary" href="{{ route('slug.handle', $brand->slug) }}">{{ $brand->name }}</a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
