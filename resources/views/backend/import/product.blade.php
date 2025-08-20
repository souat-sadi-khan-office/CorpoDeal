@extends('backend.layouts.app')
@section('title', 'Product Bulk Import')
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
                        <li class="breadcrumb-item active" aria-current="page">Product Bulk Import</li>
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
                                <p>4. After uploading products you can edit them and set images and others.</p>
                            </div>
                            <br>
                            <div class="">
                                <a class="btn btn-sm btn-info" href="{{ asset('download/product_bulk_demo.xlsx') }}" download>
                                    Download CSV
                                </a>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <form action="{{ route('admin.upload.product') }}" class="content_form" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="card mt-5 ">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 form-group">
                                                <h4>Upload Product File</h4>
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
                                <b>Column - 1: (Category)</b>: Name of the category. If category not found then ignored the full row.
                            </li>
                            <li>
                                <b>Column - 2: (Brand)</b>: Name of the brand. If brand not found then it will be blanked means no brand product.
                            </li>
                            <li>
                                <b>Column - 3: (Brand Type)</b>: Using Column 2, Brand Type will be checked. If brand type not found then it will be blanked/no brand type.
                            </li>
                            <li>
                                <b>Column - 4: (Name)</b>: Product Name. If the name if matched with system existing product name, then it will be ignored. If this column if empty, then also it will be ignored.
                            </li>
                            <li>
                                <b>Column - 5: (Slug)</b>: Product Slug. Try to use unique slug. If system found any duplicate slug, it will automatically add some random number after slug.
                            </li>
                            <li>
                                <b>Column - 6: (SKU)</b>: Product SKU.
                            </li>
                            <li class="my-3" style="list-style: none;">
                                <div class="alert alert-warning">
                                    Here if you want to upload any stock related record, then this product stock type will be Globally. If you don't want to upload stock record now, just make the column 7 and 8 empty.
                                </div>
                            </li>
                            <li>
                                <b>Column - 7: (Quantity)</b>: Stock quantity. This will be set as globally.
                            </li>
                            <li>
                                <b>Column - 8: (Unit Price)</b>: Product unit price, One product selling price. This will be set as globally.
                            </li>
                            <li>
                                <b>Column - 9: (Purchase Price)</b>: Product purchase unit price, One product purchase price.
                            </li>
                            <li>
                                <b>Column - 10: (Product Type)</b>: Type of the product. Either it is <b>Physical</b> or it will be <b>Digital</b>.
                            </li>
                            <li>
                                <b>Column - 11: (Points)</b>: Number of points will get a customer when they purchase this product. If its empty, then it will be automatically 0.
                            </li>
                            <li>
                                <b>Column - 12: (Minimum Purchase Quantity)</b>: Minimum Purchase Quantity is the number of quantity that a customer must need to buy. If it's empty, then it will be automatically select 1.
                            </li>
                            <li>
                                <b>Column - 13: (Video Provider Name)</b>: If there is any video on this product, give the name of the video provider like YouTube, Vimeo, MXPlayer.
                            </li>
                            <li>
                                <b>Column - 14: (Embed Video Link)</b>: Insert the embed video link here. If there is no video, then keep it empty.
                            </li>
                            <li>
                                <b>Column - 15: (Site Title)</b>: Product Site Title. If this is empty, then <b>Product Name</b> will be Product Site Title.
                            </li>
                            <li>
                                <b>Column - 16: (Meta Title)</b>: Product Meta Title. If this is empty, then <b>Product Name</b> will be Product Meta Title.
                            </li>
                            <li>
                                <b>Column - 17: (Meta Keywords)</b>: Product Meta Keywords..
                            </li>
                            <li>
                                <b>Column - 18: (Meta Description)</b>: Product Meta Description. If this is empty, then <b>Product Name</b> will be Product Meta Description.
                            </li>
                            <li>
                                <b>Column - 19: (Meta Shipping Cost)</b>: Product Shipping Cost. If will be used only when System Shipping Method is set to <b>Product Wise Shipping Cost</b>. For more information, please visit this <a class="text-danger" style="font-weight: bold;" target="_blank" href="{{ route('admin.shipping.configuration') }}">link</a>.
                            </li>
                            <li>
                                <b>Column - 20: (Thumbnail Image)</b>: This is optional. Use Photo Link. if you want you can upload photo <a class="text-danger" style="font-weight: bold;" href="{{ route('admin.image.index') }}">Image Upload</a> Section and use the link here.
                            </li>
                            <li>
                                <b>Column - 21: (More Images)</b>: This is optional. Use comma (,) between URLs. Use Photo Link. if you want you can upload photo <a class="text-danger" style="font-weight: bold;" href="{{ route('admin.image.index') }}">Image Upload</a> Section and use the link here.
                            </li>
                            <li>
                                <b>Column - 22: (Product Has Discount)</b>: If Product has any discount then, it will be <b>Yes</b>. Default is <b>No</b>. If this column is not set to <b>Yes</b> and Column 23, 24, 25, 26 is set, those will be ignored. This column must be set to <b>Yes</b>, if this product has any discount.
                            </li>
                            <li>
                                <b>Column - 23: (Discount Type)</b>: If it is <b>Percentage</b>, Otherwise it will be always <b>Flat Amount</b>.
                            </li>
                            <li>
                                <b>Column - 24: (Discount Amount)</b>: Product Discount Amount.
                            </li>
                            <li>
                                <b>Column - 25: (Discount Start Date)</b>: If there is any date records are in relation with discount, then fill this column. If the product has always discount, then make this field empty.
                            </li>
                            <li>
                                <b>Column - 26: (Discount End Date)</b>: Product discount end date.
                            </li>
                            <li>
                                <b>Column - 27: (Product Status)</b>: If it is <b>Active</b>, otherwise always <b>Inactive</b>.
                            </li>
                            <li>
                                <b>Column - 28: (Product Stage)</b>: Product has 3 Stage. <b>Normal Product</b> - here <b>Product Status will work</b>. <b>Upcoming</b> Product and <b>Pre Order</b> Products
                            </li>
                            <li>
                                <b>Column - 29: (Is Feature Product)</b>: It is always <b>No</b>, if you didn't set to <b>Yes</b>.
                            </li>
                            <li>
                                <b>Column - 30: (Is Returnable)</b>: It is always <b>No</b>, if you didn't set to <b>Yes</b>.
                            </li>
                            <li>
                                <b>Column - 31: (Return Deadline Days)</b>: This column is related with Column 30. If Is Returnable is set to <b>Yes</b>, Then enter the number of Days to return this product.
                            </li>
                            <li>
                                <b>Column - 32: (Low Stock Quantity Warning)</b>: Number of Quantity when Low Stock Alert will show.
                            </li>
                            <li>
                                <b>Column - 30: (Cash On Delivery Available)</b>: It is always <b>No</b>, if you didn't set to <b>Yes</b>.
                            </li>
                            <li>
                                <b>Column - 30: (Estimated Shipping Time)</b>: Enter the Estimated Shipping Time in days. like <b>7, 15, 30</b>.
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
