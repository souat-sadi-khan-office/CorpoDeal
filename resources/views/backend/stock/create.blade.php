@extends('backend.layouts.app')
@section('title', 'Create new stock')

@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="h3 mb-0">Create new stock</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.stock.index') }}">
                                Stock Management
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Create new stock</li>
                    </ol>
                </div>
                <div class="col-sm-6 text-end">
                    @if (Auth::guard('admin')->user()->hasPermissionTo('stock.view'))
                        <a href="{{ route('admin.stock.index') }}" class="btn btn-soft-danger">
                            <i class="bi bi-backspace"></i>
                            Back
                        </a>
                    @endif 
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
   
    <form action="{{ route('admin.stock.store')}}" method="POST" class="content_form" enctype="multipart/form-data">
        @csrf
        <div class="row">
    
            <!-- Product Costing & Pricing -->
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0">Product Cosingt & Pricing </h2>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <!-- product -->
                            <div class="col-md-12 form-group mb-3">
                                <label for="product_id">Product <span class="text-danger">*</span></label>
                                <input type="hidden" id="added_product_id" value="{{ $product_id }}">
                                <input type="hidden" id="defaultProductImage" value="">
                                <select name="product_id" required data-parsley-errors-container="#product_id_error" id="product_id" class="form-control"></select>
                                <span id="product_id_error"></span>
                            </div>

                            <div id="already-data-area" style="display: none;">
                                <div class="row">
                                    <div class="col-md-4 form-group mb-3">
                                        <label for="already_available_stock">Current Stock</label>
                                        <input type="text" disabled id="already_available_stock" class="form-control" value="0">
                                    </div>
                                    <div class="col-md-4 form-group mb-3">
                                        <label for="already_stock_unit_price">Current Unit Price</label>
                                        <input type="text" disabled id="already_stock_unit_price" class="form-control" value="0">
                                    </div>
                                    <div class="col-md-4 form-group mb-3">
                                        <label for="already_stock_type">Current Stock Type</label>
                                        <input type="text" disabled id="already_stock_type" class="form-control" value="">
                                    </div>
                                </div>
                            </div>

                            <div id="data-area"  style="display:none;">
                                <div class="row">

                                    <!-- sku -->
                                    <div class="col-md-4 form-group mb-3">
                                        <label for="sku">SKU</label>
                                        <input type="text" name="sku" id="sku" class="form-control">
                                    </div>

                                    <!-- unit_price -->
                                    <div class="col-md-4 form-group mb-3">
                                        <label for="unit_price">Unit Price <span class="text-danger">*</span></label>
                                        <input type="text" name="unit_price" id="unit_price" class="form-control number" value="0" required >
                                    </div>

                                    <!-- low_stock_quantity -->
                                    <div class="col-md-4 form-group mb-3">
                                        <label for="low_stock_quantity">Low Stock Alert Quantity <span class="text-danger">*</span></label>
                                        <input type="text" name="low_stock_quantity" id="low_stock_quantity" class="form-control number" value="0" required >
                                    </div>

                                    <!-- quanitiy -->
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="quantity">Quantity <span class="text-danger">*</span></label>
                                        <input type="text" name="quantity" id="quantity" class="form-control number" value="0" required>
                                        <small class="text-muted">Product quanity must be same on the product stock adding table.</small>
                                    </div>

                                    <!-- total_price -->
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="total_price">Total Price <span class="text-danger">*</span></label>
                                        <input type="text" name="total_price" id="total_price" class="form-control" value="0" required>
                                        <small class="text-muted">Total Price = Unit Price X Quantity</small>
                                    </div>

                                    <!-- purchase_unit_price -->
                                    <div class="col-md-6 form-goup mb-3">
                                        <label for="purchase_unit_price">Purchase Unit Price </label>
                                        <input type="text" name="purchase_unit_price" id="purchase_unit_price" class="form-control number" value="0" >
                                        <small class="text-muted">This is the product purchase unit price from sellter.</small>
                                    </div>
                                    
                                    <!-- purchase_total_price -->
                                    <div class="col-md-6 form-goup mb-3">
                                        <label for="purchase_total_price">Purchase Total Price </label>
                                        <input type="text" name="purchase_total_price" id="purchase_total_price" class="form-control number" value="0" >
                                        <small class="text-muted">This is the product purchase price from sellter.</small>
                                    </div>

                                    <!-- currency_id -->
                                    <div class="col-md-4 form-group mb-3">
                                        <label for="currency_id">Currency</label>
                                        <select name="currency_id" id="currency_id" required class="form-control select">
                                            @foreach ($currencies as $currency)
                                                <option {{ get_settings('system_default_currency') == $currency->id ? 'selected' : '' }} value="{{ $currency->id }}">{{ $currency->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- file -->
                                    <div class="col-md-4 form-group mb-3">
                                        <label for="file">File </label>
                                        <input type="file" name="file" id="file" class="form-control">
                                    </div>

                                    <!-- is_selleable -->
                                    <div class="col-md-4 form-group mb-3">
                                        <label for="is_selleable">Is sellable <span class="text-danger">*</span></label>
                                        <select name="is_sellable" id="is_sellable" required class="form-contom select">
                                            <option selected value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Stocks -->
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0">Product Stocks </h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 form-group mb-3">
                                <div class="alert alert-warning">
                                    <b>Warning</b>: Here product stock must be the same quanity as on the <b>Product costing</b> quanity.
                                </div>
                            </div>

                            <div class="col-md-12 mb-3 form-group">
                                <label for="stock_type">Stock Types <span class="text-danger">*</span></label>
                                <select name="stock_types" id="stock_types" class="form-control select">
                                    <option selected value="globally">Globally</option>
                                    <option disabled value="zone_wise">Zone wise</option>
                                    <option disabled value="country_wise">Country wise</option>
                                    <option value="city_wise">City Wise</option>
                                </select>
                            </div>

                            <!-- globally_area -->
                            <div id="globally_area">
                                <div class="col-md-12 form-group mb-3">
                                    <label for="globally_stock_amount">Total in Stock <spna class="text-danger">*</spna></label>
                                    <input type="text" class="form-control number" name="globally_stock_amount" id="globally_stock_amount" required value="0">
                                </div>
                            </div>

                            <!-- zone_wise_area -->
                            <div id="zone_wise_area" style="display: none;">
                                <div class="col-md-12 form-group mb-3">
                                    <label for="zone_wise_id">Zones <span class="text-danger">*</span></label>
                                    <select id="zone_wise_id" class="form-control select" data-placeholder="Select zones">
                                        <option value="">Select zones</option>
                                        @foreach ($zones as $zone)
                                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="zone_wise_data"></div>
                            </div>

                            <!-- country_wise_area -->
                            <div id="country_wise_area" style="display: none;">
                                <div class="col-md-12 form-group mb-3">
                                    <label for="country_wise_id">Countries <span class="text-danger">*</span></label>
                                    <select name="country_wise_id[]" id="country_wise_id" class="form-control select" data-placeholder="Select countries">
                                        <option value="">Select countries</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="country_wise_data"></div>
                            </div>

                            <!-- city_wise_area -->
                            <div id="city_wise_area" style="display: none;">
                                <div class="col-md-12 form-group mb-3">
                                    <label for="city_wise_id">Cities <span class="text-danger">*</span></label>
                                    <select name="city_wise_id[]" id="city_wise_id" class="form-control select" data-placeholder="Select cities">
                                        <option value="">Select cities</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="city_wise_data"></div>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="col-md-12 form-group mb-3 text-end">
                <button class="btn btn-soft-success" type="submit" id="submit">
                    <i class="bi bi-send"></i>
                    Create
                </button>
                <button class="btn btn-soft-warning" style="display: none;" id="submitting" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
            </div>
        </div>
    </form>
@endsection

@push('script')
    <script src="{{ asset('backend/assets/js/dropify.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            _componentSelect();
            _formValidation();

            let added_product_id = $('#added_product_id').val();
            if(added_product_id != '') {
                product_id_array = [added_product_id];
                $.ajax({
                    url: '/search/product-data',
                    method: 'POST',
                    data: { data: product_id_array },
                    dataType: 'JSON',
                    success: function (data) {
                        $('#defaultProductImage').val(data[0].thumb_image);
                        let option = new Option(data[0].name, data[0].id, true, true);
                        $('#product_id').append(option).trigger('change');
                    }
                });
            }

            $(document).on('change', '#product_id', function() {
                let product_id = $(this).val();

                $.ajax({
                    url: '/search/product-stock',
                    method: 'POST',
                    data: {
                        product_id: product_id
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        if(response) {
                            let productName = response.name;
                            if(productName != '') {
                                $('#sku').val(_slugify(productName));
                            }
                        }
                        let stock_type = '';
                        switch(response.stock_types) {
                            case 'globally':
                                stock_type = 'Globally';
                            break;
                            case 'zone_wise': 
                                stock_type = 'Zone wise';
                            break; 
                            case 'country_wise': 
                                stock_type = 'Country wise';
                            break;
                            case 'city_wise':
                                stock_type = 'City wise';
                            break;
                        }


                        $('#already_available_stock').val(response.curr_stock);
                        $('#already_stock_unit_price').val(response.unit_price);
                        $('#already_stock_type').val(stock_type);
                        $('#already-data-area').show();
                        $('#data-area').show();
                    }
                });
            })

            $(document).on('change', '#stock_types', function() {
                let stock_type = $(this).val();
                switch(stock_type) {
                    case 'globally':
                        $('#globally_area').show();
                        $('#globally_stock_amount').attr('required', true);

                        $('#zone_wise_area').hide();
                        $('#zone_wise_data').html("");

                        $('#country_wise_area').hide();
                        $('#country_wise_data').html("");

                        $('#city_wise_area').hide();
                        $('#city_wise_data').html("");
                    break;
                    case 'zone_wise':
                        $('#globally_area').hide();
                        $('#globally_stock_amount').removeAttr('required');

                        $('#zone_wise_area').show();

                        $('#country_wise_area').hide();
                        $('#country_wise_data').html("");

                        $('#city_wise_area').hide();
                        $('#city_wise_data').html("");
                    break;
                    case 'country_wise': 
                        $('#globally_area').hide();
                        $('#globally_stock_amount').removeAttr('required');

                        $('#zone_wise_area').hide();
                        $('#zone_wise_data').html("");

                        $('#country_wise_area').show();

                        $('#city_wise_area').hide();
                        $('#city_wise_data').html("");
                    break;
                    case 'city_wise': 
                        $('#globally_area').hide();
                        $('#globally_stock_amount').removeAttr('required');

                        $('#zone_wise_area').hide();
                        $('#zone_wise_data').html("");
                        
                        $('#country_wise_area').hide();
                        $('#country_wise_data').html("");

                        $('#city_wise_area').show();
                    break;
                }
            });

            $(document).on('change', '#zone_wise_id', function() {
                let zone_id = $(this).val();
                $('#zone_wise_id option').filter(function() {
                    return $(this).val() == zone_id;
                }).remove();

                $.ajax({
                    url: '/admin/get-zone-information-by-id',
                    method: 'POST',
                    data: {
                        zone_id: zone_id
                    },
                    dataType: 'JSON',
                    cache: true,
                    success: function(data) {
                        var randomNumber = Math.floor(Math.random() * (1000000 - 100000)) + 100000;
                        let content = `
                            <div class="row" id="zone_wise_item_`+ randomNumber +`">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="zone_id">Zone <span class="text-danger">*</span></label>
                                    <input type="hidden" name="zone_id[]"  value="`+ data.id +`">
                                    <input type="text" id="zone_id" disabled class="form-control" value="`+ data.name +`">
                                </div>

                                <div class="col-md-4 form-group mb-3">
                                    <label for="zone_wise_stock_quantity">Quantity</label>
                                    <input type="text" name="zone_wise_stock_quantity[]" id="zone_wise_stock_quanity" class="form-control" required value="0">
                                </div>

                                <div class="col-md-2 form-group mb-3">
                                    <button type="button" style="margin-top: 25px;" class="btn btn-sm btn-soft-danger remove_zone_item" data-id="`+ randomNumber +`">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `;

                        $('#zone_wise_data').append(content);
                    }
                })
            });

            $(document).on('click', '.remove_zone_item', function() {
                let id = $(this).data('id');
                $('#zone_wise_item_'+id).remove();
            });

            $(document).on('change', '#country_wise_id', function() {
                let country_id = $(this).val();
                $('#country_wise_id option').filter(function() {
                    return $(this).val() == country_id;
                }).remove();

                $.ajax({
                    url: '/admin/get-country-information-by-id',
                    method: 'POST',
                    data: {
                        country_id: country_id
                    },
                    dataType: 'JSON',
                    cache: true,
                    success: function(data) {
                        var randomNumber = Math.floor(Math.random() * (1000000 - 100000)) + 100000;
                        let content = `
                            <div class="row" id="country_wise_item_`+ randomNumber +`">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="country_id">Country <span class="text-danger">*</span></label>
                                    <input type="hidden" name="country_id[]"  value="`+ data.id +`">
                                    <input type="text" id="country_id" disabled class="form-control" value="`+ data.name +`">
                                </div>

                                <div class="col-md-4 form-group mb-3">
                                    <label for="country_wise_quantity">Quantity</label>
                                    <input type="text" name="country_wise_quantity[]" value="0" id="country_wise_quantity" class="form-control" required>
                                </div>

                                <div class="col-md-2 form-group mb-3">
                                    <button type="button" style="margin-top: 25px;" class="btn btn-sm btn-soft-danger remove_country_item" data-id="`+ randomNumber +`">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `;

                        $('#country_wise_data').append(content);
                    }
                })
            });

            $(document).on('click', '.remove_country_item', function() {
                let id = $(this).data('id');
                $('#country_wise_item_'+id).remove();
            });

            $(document).on('change', '#city_wise_id', function() {
                let city_id = $(this).val();
                $('#city_wise_id option').filter(function() {
                    return $(this).val() == city_id;
                }).remove();

                $.ajax({
                    url: '/admin/get-city-information-by-id',
                    method: 'POST',
                    data: {
                        city_id: city_id
                    },
                    dataType: 'JSON',
                    cache: true,
                    success: function(data) {
                        var randomNumber = Math.floor(Math.random() * (1000000 - 100000)) + 100000;
                        let content = `
                            <div class="row" id="city_wise_item_`+ randomNumber +`">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="city_id">City <span class="text-danger">*</span></label>
                                    <input type="hidden" name="city_id[]"  value="`+ data.id +`">
                                    <input type="text" id="city_id" disabled class="form-control" value="`+ data.name +`">
                                </div>

                                <div class="col-md-4 form-group mb-3">
                                    <label for="city_wise_quantity">Quantity</label>
                                    <input type="text" name="city_wise_quantity[]" id="city_wise_quantity" class="form-control" value="0">
                                </div>

                                <div class="col-md-2 form-group mb-3">
                                    <button type="button" style="margin-top: 25px;" class="btn btn-sm btn-soft-danger remove_city_item" data-id="`+ randomNumber +`">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `;

                        $('#city_wise_data').append(content);
                    }
                })
            });

            $(document).on('click', '.remove_city_item', function() {
                let id = $(this).data('id');
                $('#city_wise_item_'+id).remove();
            });

            $(document).on('keyup', '#unit_price', function() {
                let unit_price = parseInt($(this).val());
                let quantity = parseInt($('#quantity').val());
                let total_price = unit_price * quantity;

                $('#total_price').val(total_price);
            });

            $(document).on('keyup', '#quantity', function() {
                let quantity = parseInt($(this).val());
                let unit_price = parseInt($('#unit_price').val());
                let total_price = unit_price * quantity;
                let purchase_unit_price = parseInt($('#purchase_unit_price').val());
                let purchase_total_price = quantity * purchase_unit_price;

                $('#total_price').val(total_price);
                $('#purchase_total_price').val(purchase_total_price);
            });

            $(document).on('keyup', '#purchase_unit_price', function() {
                let quantity = parseInt($('#quantity').val());
                let purchase_unit_price = parseInt($(this).val());

                let purchase_total_price = quantity * purchase_unit_price;
                $('#purchase_total_price').val(purchase_total_price);
            })

            // For Products
            $('#product_id').select2({
                width: '100%',
                placeholder: 'Select products',
                templateResult: formatProductOption, 
                templateSelection: formatProductSelection,
                ajax: {
                    url: '/search/product',
                    method: 'POST',
                    dataType: 'JSON',
                    delay: 250,
                    cache: true,
                    data: function (data) {
                        return {
                            searchTerm: data.term
                        };
                    },

                    processResults: function (response) {
                        return {
                            results:response
                        };
                    }
                }
            });

            function formatProductOption(product) {
                if (!product.id) {
                    return product.text;
                }

                var productImage = '<img src="' + product.image_url + '" class="img-flag" style="height: 20px; width: 20px; margin-right: 10px;" />';
                var productOption = $('<span>' + productImage + product.text + '</span>');
                return productOption;
            }

            function formatProductSelection(product) {
                if (!product.id) {
                    return product.text;
                }

                var defaultImageUrl = $('#defaultProductImage').val();
                var image_url = product.image_url ? product.image_url : defaultImageUrl;

                var productImage = '<img src="' + image_url + '" class="img-flag" style="height: 20px; width: 20px; margin-right: 10px;" />';
                return $('<span>' + productImage + product.text + '</span>');
            }
        });
    </script>
@endpush
