@if (count($products))
    <div class="row">
        @foreach ($products as $product)
            <div class="col-md-4 col-sm-12 col-xl-3">
                @include('frontend.components.product_main', ['listing' => 'main', 'tag' => 'discount_price'])
            </div>
        @endforeach
    </div>
@else
    <div class="row">
        <div class="col-md-12">
            <div class="bg_white text-center pb-5">
                <img src="{{ asset('pictures/none.gif') }}" alt="Nothing Found"> <br>
                <p><b>No product found with this criteria </b></p>
            </div>
        </div>
    </div>
@endif