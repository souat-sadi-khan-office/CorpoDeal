@if(count($models) > 0)
    @foreach($models as $product)
        <li>
            <a href="javascript:;" class="item_remove">
                <i class="ion-close"></i>
            </a>
            <a href="{{ $product['slug'] }}">
                <img src="{{ asset($product['thumb_image']) }}" alt="{{ $product['name'] }}">
                {{ $product['name'] }}
            </a>
            <span class="cart_quantity"> 
                {{ $product['quantity'] }} x <span class="cart_amount"> 
                    <span class="price_symbole">$</span></span>{{ $product['price'] }}
            </span>
        </li>
    @endforeach
@else
    <p>Your cart is empty!</p>
@endif