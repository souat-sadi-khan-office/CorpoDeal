@if (isset($cart_updated) && $cart_updated == 1)
    <div class="alert alert-warning" role="alert">
        <h4>
            <i style="color:#ffaf38;" class="fas fa-exclamation-triangle"></i>
            <b>Important messages about items in your Cart:</b>
        </h4>
        <p class="mb-0 pb-0">Some items in your cart cannot be shipped to your selected delivery location. So for this reason those products are removed from your cart.</p>
    </div>
@endif

@if(count($models) > 0)
    @foreach($models as $model)
    <li class="list-group-item py-3 ps-0 border-bottom">
        <div class="row align-items-center">
            <div class="col-7 col-md-8 col-lg-8">
                <div class="d-flex">
                    <img width="64" height="64" src="{{ $model['thumb_image'] }}" alt="{{ $model['name'] }}" class="icon-shape icon-xxl">
                    <div class="ms-3">
                        <a href="{{ route('slug.handle', $model['slug']) }}" class="text-inherit">
                            <h6 class="mb-0">{{ Illuminate\Support\Str::limit($model['name'], 25, '...') }}</h6>
                        </a>

                        <div class="mt-2 small lh-1">
                            <a href="javascript:;" class="text-decoration-none text-inherit remove-item-from-cart text-danger" data-id="{{ $model['id'] }}">
                                <span class="me-1 align-text-bottom">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-success">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg>
                                </span>
                                <span class="text-muted">Remove</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-1 col-md-1 col-lg-1">
                <div class="input-group input-spinner">
                    {{-- <input type="button" value="-" class="button-minus btn btn-sm" data-field="quantity"> --}}
                    <input type="text" readonly step="1" max="10" value="{{ $model['quantity'] }} " name="quantity" class="quantity-field number form-control-sm form-input">
                    {{-- <input type="button" value="+" class="button-plus btn btn-sm" data-field="quantity"> --}}
                </div>
            </div>

            <div class="col-4 text-lg-end text-start text-md-end col-md-3">
                  <span class="fw-bold">{{ format_price(convert_price($model['quantity'] * $model['price'])) }}</span>
            </div>
        </div>
    </li>
    @endforeach
@else
    <div class="empty-content">
        <p class="text-center">Your shopping cart is empty!</p>
    </div>
@endif    