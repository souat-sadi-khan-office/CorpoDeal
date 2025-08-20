@extends('backend.layouts.app')
@section('title', 'Shipping Configuration')
@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" class="content_form">
    @csrf
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h1 class="h5 mb-0">Shipping Configuration</h1>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="align-items-start">
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <div class="form-check">
                                            <input {{ get_settings('shipping_cost_type') == 'free_shipping' ? 'checked' : '' }} class="form-check-input" type="radio" name="shipping_cost_type" id="free_shipping_cost" value="free_shipping">
                                            <label class="text-info form-check-label" for="free_shipping_cost">
                                                Free shipping
                                            </label>
                                            <p>Free shipping is applicable if Free shipping method is enabled. <b>Free shipping Cost calculation</b>: How many products a customer purchase, doesn't matter. Shipping cost is always free.</p>
                                        </div>
                                        
                                        <div class="form-check">
                                            <input {{ get_settings('shipping_cost_type') == 'product_wise' ? 'checked' : '' }} class="form-check-input" type="radio" name="shipping_cost_type" id="product_wise_shipping_cost" value="product_wise">
                                            <label class="text-info form-check-label" for="product_wise_shipping_cost">
                                                Product Wise Shipping Cost
                                            </label>
                                            <p><b>Product Wise Shipping Cost calculation</b>: Shipping cost is calculate by addition of each product shipping cost.</p>
                                        </div>
                                        
                                        <div class="form-check">
                                            <input {{ get_settings('shipping_cost_type') == 'flat_rate' ? 'checked' : '' }} class="form-check-input" type="radio" name="shipping_cost_type" id="flat_rate_shipping_cost" value="flat_rate">
                                            <label class="text-info form-check-label" for="flat_rate_shipping_cost">
                                                Flat Rate Shipping Cost
                                            </label>
                                            <p>Flat rate shipping cost is applicable if Flat rate shipping is enabled. <b>Flat Rate Shipping Cost calculation</b>: How many products a customer purchase, doesn't matter. Shipping cost is fixed.</p>
                                        </div>
                                        <div class="form-check">
                                            <input {{ get_settings('shipping_cost_type') == 'carrier_wise' ? 'checked' : '' }} class="form-check-input" type="radio" name="shipping_cost_type" id="carrier_wise_shipping_cost" value="area_wise">
                                            <label class="text-info form-check-label" for="carrier_wise_shipping_cost">
                                                Carrier Based Shipping Cost
                                            </label>
                                            <p><b>Carrier Based Shipping Cost calculation</b>: Shipping cost calculate in addition with carrier. In each carrier you can set free shipping cost or can set weight range or price range or quantity range shipping cost. To configure carrier based shipping cost go to <a href="{{ route('admin.carrier.index') }}">Shipping Carriers</a>.</p>
                                        </div>
                                        <div class="form-check">
                                            <input {{ get_settings('shipping_cost_type') == 'area_wise' ? 'checked' : '' }} class="form-check-input" type="radio" name="shipping_cost_type" id="area_wise_shipping_cost" value="area_wise">
                                            <label class="text-info form-check-label" for="area_wise_shipping_cost">
                                                Area Wise Flat Shipping Cost
                                            </label>
                                            <p><b>Area Wise Flat Shipping Cost calculation</b>: Fixed rate for each area. If customers purchase multiple products from one seller shipping cost is calculated by the customer shipping area. To configure area wise shipping cost go to Shipping Zone, Country, Cities Records.</p>
                                        </div>
                                    </div>

                                    <div style="display: {{ get_settings('shipping_cost_type') == 'flat_rate' ? 'block' : 'none' }}" class="col-md-12 flat_type form-group mb-3">
                                        <label for="system_default_delivery_charge">Flat Rate Shipping Cost <span class="text-danger">*</span></label>
                                        <input type="number" name="system_default_delivery_charge" value="{{ round(covert_to_defalut_currency(get_settings('system_default_delivery_charge'))) }}" class="form-control">
                                    </div>

                                    <div style="display: {{ get_settings('shipping_cost_type') == 'area_wise' ? 'block' : 'none' }}" class="col-md-12 area_type form-group mb-3">
                                        <label for="shipping_area_type">Shipping Area Type</label>
                                        <select name="shipping_area_type" id="shipping_area_type" class="form-control">
                                            <option {{ get_settings('shipping_area_type') == 'zone_wise' ? 'selected' : '' }} value="zone_wise">Zone Wise</option>
                                            <option {{ get_settings('shipping_area_type') == 'country_wise' ? 'selected' : '' }} value="country_wise">Country Wise</option>
                                            <option {{ get_settings('shipping_area_type') == 'city_wise' ? 'selected' : '' }} value="city_wise">City Wise</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-3 form-group text-end">
            @if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.update') === false)
                <button type="submit" id="submit" class="btn btn-soft-success">
                    <i class="bi bi-send"></i>
                    Update
                </button>
                <button type="button" style="display: none;" id="submitting" class="btn btn-soft-warning">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </button>
            @endif
        </div>
    </div>
</form>
@endsection
@push('script')
    <script>
        _formValidation();

        $("input[name='shipping_cost_type']").on('change', function () {
            if ($("#area_wise_shipping_cost").is(':checked')) {
                $('.flat_type').hide();
                $('.area_type').show();
            } else if ($('#flat_rate_shipping_cost').is(':checked')) {
                $('.area_type').hide();
                $('.flat_type').show();
            } else {
                $('.flat_type').hide();
                $('.area_type').hide();
            }
        });
    </script>
@endpush