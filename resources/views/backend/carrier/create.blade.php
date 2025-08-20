@extends('backend.layouts.app')
@section('title', 'Create New Carrier | Carrier Management')
@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/dropify.min.css') }}">
@endpush
@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="h3 mb-0">Create New Carriers</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.carrier.index') }}">
                                Carriers Management
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Create New Carrier</li>
                    </ol>
                </div>

                <div class="col-sm-6 text-end">
                    <a href="{{ route('admin.carrier.index') }}" class="btn btn-soft-success">
                        <i class="bi bi-plus"></i>
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')

    <div class="card">
        <div class="card-body">
            <div class="row">
                <form action="{{ route("admin.carrier.store") }}" enctype="multipart/form-data" method="POST" class="content_form">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mt-3 form-group">
                            <label for="name">Carrier Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>

                        <div class="col-md-6 mt-3 form-group">
                            <label for="transit_time">Transit Time <span class="text-danger">*</span></label>
                            <input type="text" name="transit_time" id="transit_time" class="form-control" required>
                        </div>

                        <div class="col-md-6 mt-3 form-group">
                            <label for="tracking_url">Tracking URL</label>
                            <input type="url" name="tracking_url" id="tracking_url" class="form-control">
                        </div>

                        <div class="col-md-12 mt-3 form-group">
                            <label for="logo">Logo</label>
                            <input type="file" name="logo" id="logo" class="form-control dropify">
                        </div>
            
                        <div class="col-md-12 mt-3 form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control select" required>
                                <option selected value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-12 mt-3 form-group">
                            <label for="free_shipping">Free Shipping? <span class="text-danger">*</span></label>
                            <select name="free_shipping" id="free_shipping" class="form-control select" required>
                                <option selected value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>

                        <div id="not_free_shipping_area">

                            <div class="col-md-12 form-group mt-3">
                                <label for="rule_type">Billing Rule Type <span class="text-danger">*</span></label>
                                <select name="rule_type" id="rule_type" class="form-control select" required data-parsley-errors-container="#rule_type_error">
                                    <option selected value="fixed">Fixed Type</option>
                                    <option value="weight">According to Weight</option>
                                    <option value="price">According to Price</option>
                                    <option value="quantity">According to Quantity</option>
                                </select>
                            </div>

                            <div id="price_range_form">
                                <div class="col-md-12 my-3 pl-0">
                                    <h3 class="h6 carrier_range_form_header_text text-info">Weight based carrier price range</h3>
                                    <input type="hidden" id="rule_type_value" value="fixed">
                                </div>

                                <div class="col-md-12 table-responsive">
                                    <table id="price-range-table" class="table table-bordered table-striped mb-0">
                                        <tbody>
                                            <tr class="min_quantity" style="background-color: #c9c9d4">
                                                <td class="price_range_text">Will be applied when the weight is</td>
                                                <td> &gt;= </td>
                                                <td>
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text bill_based_on">kg</div>
                                                        </div>
                                                        <input type="number" class="form-control" name="delimiter1[]" value="0.00" step="0.01">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="max_quantity" style="background-color: #c9c9d1">
                                                <td class="price_range_text">Will be applied when the weight is</td>
                                                <td>&lt;</td>
                                                <td>
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text bill_based_on">kg</div>
                                                        </div>
                                                        <input type="number" class="form-control delimiter2" name="delimiter2[]" value="0.00" step="0.01">
                                                    </div>
                                                </td>
                                            </tr>
        
                                            @foreach ($countries as $country)
                                                <tr>
                                                    <td>
                                                        <span class="mt-2">{{ $country->name }}</span>
                                                    </td>
                                                    <td>
                                                        <input class="aiz-square-check zone_enable mt-2" type="checkbox" name="zones[]" value="{{ $country->id }}">
                                                    </td>
                                                    <td>
                                                        <div class="input-group mb-2">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">$</div>
                                                            </div>
                                                            <input type="number" class="form-control shipping_cost" name="carrier_price[{{ $country->id }}][]" placeholder="Cost" disabled="">
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            <tr>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
    
                                
                            </div>

                        </div>
            
                        <div class="col-md-6 mt-3 text-start">
                            <button type="button" class="btn btn-primary btn-sm" id="addNewRange">
                                Add new range
                            </button>
                        </div>
                        <div class="col-md-6 mt-3 text-end">
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
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('backend/assets/js/dropify.min.js') }}"></script>
    <script>
        $(function() {
            _componentSelect();
            _formValidation();

            $(document).on('change', '#free_shipping', function() {
                let value = $(this).val();
                if(value == 'no') {
                    $('#not_free_shipping_area').show();
                } else {
                    $('#not_free_shipping_area').hide();
                }
            })

            $('.dropify').dropify({
                imgFileExtensions: ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp']
            });

            $(document).on("change", ".zone_enable", function() {
                $(this).closest("tr").find('.shipping_cost').prop("disabled", !this.checked);
            });

            $(document).on("click", "#addNewRange", function() {
                var tableBody = $("#price-range-table").find("tbody");
                var tdLength = tableBody.find("tr td").length;

                // last td input 
                var first_lastTd = $("#price-range-table").find("tr:nth-child(1)").find("td:last").find("input").val();
                var second_lastTd = $("#price-range-table").find("tr:nth-child(2)").find("td:last").find("input").val();

                if ((second_lastTd == 0) || (second_lastTd == first_lastTd) ||
                    ((second_lastTd - first_lastTd) < 0)) {
                    alert('Please validate the last range before creating a new one.')
                } else {
                    fnClone(tableBody, second_lastTd);
                }
            });

            // last td remove
            $(document).on("click", ".delete-range", function() {
                var iIndex = $(this).closest("td").prevAll("td").length;
                $(this).parents("#price-range-table").find("tr").each(function() {
                    $(this).find("td:eq(" + iIndex + ")").remove();
                });
            });

            // last td clone function
            function fnClone(tableBody, second_lastTd) {
                tableBody.find("td:nth-last-child(1)").each(function() {
                    $(this).clone()
                        .find("input").val("").end()
                        .insertAfter(this);
                });

                $('#price-range-table tr:last td:last').html('<button type="button" id="disableBtn" class="btn btn-primary btn-sm delete-range">Delete</button>');

                var first_lastTd = $("#price-range-table").find("tr:nth-child(1)").find("td:last").find("input");
                first_lastTd.val(parseFloat(second_lastTd).toFixed(2));
            }

            // update price range form data based on billing type
            function update_price_range_form() {
                var billing_type = $('#rule_type').val();

                switch(billing_type) {
                    case 'fixed':
                        $(".carrier_range_form_header_text").html("Fixed Price based carrier price range");

                        $('.min_quantity').hide();
                        $('.max_quantity').hide();
                        $('#addNewRange').hide();
                    break;
                    case 'weight':
                        $(".carrier_range_form_header_text").html("Weight based carrier price range");

                        $('.min_quantity').show();
                        $('.max_quantity').show();
                        $('#addNewRange').show();

                        $(".bill_based_on").html("kg");
                    break;
                    case 'price':
                        $(".carrier_range_form_header_text").html("Price based carrier price range");

                        $('.min_quantity').show();
                        $('.max_quantity').show();
                        $('#addNewRange').show();

                        $(".bill_based_on").html("$");
                    break;
                    case 'quantity':
                        $(".carrier_range_form_header_text").html("Quantity based carrier price range");

                        $('.min_quantity').show();
                        $('.max_quantity').show();
                        $('#addNewRange').show();

                        $(".bill_based_on").html("Piece");
                    break;
                }
            }

            $(document).on('change', '#rule_type', function() {
                let value = $(this).val();
                update_price_range_form(value);
            })

            update_price_range_form();
        })
    </script>
@endpush