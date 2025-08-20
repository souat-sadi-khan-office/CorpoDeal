@extends('backend.layouts.app')
@section('title', 'Product Stock Bulk Import')
@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Product Stock Bulk Import</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert" style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                                <strong>Step One:</strong>
                                <p>1. Download the skeleton file and fill it with proper data.</p>
                                <p>2. You can download the example file to understand how the data must be filled.</p>
                                <p>3. Once you have downloaded and filled the skeleton file, upload it in the form below and submit.</p>
                                <p>4. After uploading stock you can edit them and set images and others.</p>
                            </div>
                            <br>
                            <div class="">
                                <a class="btn btn-sm btn-info" href="{{ asset('download/product_stock_bulk_demo.xlsx') }}" download>
                                    Download CSV
                                </a>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <form action="{{ route('admin.upload.product.stock') }}" class="content_form" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="card mt-5 ">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 form-group">
                                                <h4>Upload Stock File</h4>
                                            </div>
                                            <div class="col-md-12 mt-3 form-group">
                                                <input type="file" name="file" id="file" required accept=".csv, .xls, .xlsx" class="form-control">
                                                <span class="text-danger">Maximum 200 column at a time</span>
                                            </div>
                                            <div class="col-md-12 mt-3 form-group">
                                                <button type="submit" class="btn btn-sm btn-outline-success"  id="submit">
                                                    <i class="bi bi-send"></i>
                                                    Upload
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning" style="display: none;" id="submitting" type="button" disabled>
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                    Loading...
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="alert" style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                        <strong>File Column Indexing:</strong>
                        <p>When you are filling it with proper data:</p>
                        <ul>
                            <li>
                                <b>Column - 1: (Product)</b>: Name of the Product. If the product is not found in the system, the entire row will be ignored.
                            </li>
                            <li>
                                <b>Column - 2: (City)</b>: Required. If empty, stock will be added globally. If the city name is not found in the system, the row will be ignored.
                            </li>
                            <li>
                                <b>Column - 3: (SKU)</b>: Product's unique SKU.
                            </li>
                            <li>
                                <b>Column - 4: (Quantity)</b>: The quantity of the product. If left empty, it will default to 1.
                            </li>
                            <li>
                                <b>Column - 5: (Unit Price)</b>: The price per unit of the product. If left empty, it will default to 0.00.
                            </li>
                            <li>
                                <b>Column - 6: (Total Unit Price)</b>: Calculated as <code>quantity * unit price</code>.
                            </li>
                            <li>
                                <b>Column - 7: (Purchase Price)</b>: The purchase price per unit of the product. If left empty, it will default to 1.
                            </li>
                            <li>
                                <b>Column - 8: (Total Purchase Price)</b>: Calculated as <code>quantity * purchase price</code>.
                            </li>
                            <li>
                                <b>Column - 9: (Sellable)</b>: Indicates if the product is available for sale. If left empty, it will default to "Yes."
                            </li>
                            <li>
                                <b>Column - 10: (Low Stock Quantity)</b>: When the stock quantity reaches this value, a low stock alert will be triggered.
                            </li>
                            <li>
                                <b>Column - 11: (Number of Sales Already)</b>: Indicates the total number of units already sold. Defaults to 0.
                            </li>
                            <li>
                                <b>Column - 12: (Product Has Discount)</b>: Indicates if there is any discount on the product. Defaults to "No" if left empty.
                            </li>
                            <li>
                                <b>Column - 13: (Discount Type)</b>: The type of discount, either "Flat" or "Percentage." Defaults to "Flat."
                            </li>
                            <li>
                                <b>Column - 14: (Discount Amount)</b>: The discount amount applied to the product.
                            </li>
                            <li>
                                <b>Column - 15: (Discount Start Date)</b>: The date the discount begins. If left empty, the discount will always be active.
                            </li>
                            <li>
                                <b>Column - 16: (Discount End Date)</b>: The date the discount ends. If left empty, the discount will always be active.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script>
        _formValidation();

        // for category searching
        $('#category_id').select2({
            width: '100%',
            placeholder: 'Select category',
            templateResult: formatCategoryOption,
            templateSelection: formatCategorySelection,
            ajax: {
                url: '/search/category',
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

        function formatCategoryOption(category) {
            if (!category.id) {
                return category.text;
            }

            var categoryImage = '<img src="' + category.image_url + '" class="img-flag" style="height: 20px; width: 20px; margin-right: 10px;" />';
            var categoryOption = $('<span>' + categoryImage + category.text + '</span>');
            return categoryOption;
        }

        function formatCategorySelection(category) {

            if (!category.id) {
                return category.text;
            }

            var defaultImageUrl = $('#default_category_image').val();
            var image_url = category.image_url ? category.image_url : defaultImageUrl;

            var categoryImage = '<img src="' + image_url + '" class="img-flag" style="height: 20px; width: 20px; margin-right: 10px;" />';
            return $('<span>' + categoryImage + category.text + '</span>');
        }

        // for brands
        $('#brand_id').select2({
            width: '100%',
            placeholder: 'Select Brand',
            templateResult: formatBrandOption,
            templateSelection: formatBrandSelection,
            ajax: {
                url: '/search/brands',
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

        function formatBrandOption(brand) {
            if (!brand.id) {
                return brand.text;
            }

            var brandImage = '<img src="' + brand.image_url + '" class="img-flag" style="height: 20px; width: 20px; margin-right: 10px;" />';
            var brandOption = $('<span>' + brandImage + brand.text + '</span>');
            return brandOption;
        }

        function formatBrandSelection(brand) {
            if (!brand.id) {
                return brand.text;
            }

            var defaultImageUrl = $('#default_brand_image').val();
            var image_url = brand.image_url ? brand.image_url : defaultImageUrl;

            var brandImage = '<img src="' + image_url + '" class="img-flag" style="height: 25px; width: 25px; margin-right: 10px;" />';
            return $('<span>' + brandImage + brand.text + '</span>');
        }
    </script>
@endpush
