@extends('backend.layouts.app')
@section('title', 'Update Review')
@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="h3 mb-0">Update Review</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.banner.index') }}">Review Management</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Update Review</li>
                    </ol>
                </div>

                @if (Auth::guard('admin')->user()->hasPermissionTo('customer-review.view'))
                    <div class="col-sm-6 text-end">
                        <a href="{{ route('admin.customer-review.index') }}" class="btn btn-soft-danger">
                            <i class="bi bi-backspace"></i>
                            Back
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/dropify.min.css') }}">
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12 mx-auto">
            <form action="{{ route('admin.customer-review.update', $model->id) }}" enctype="multipart/form-data" class="content_form" method="post">
                @csrf
                @method('PATCH')
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 form-group mb-3">
                                <label for="product_id">Product <span class="text-danger">*</span></label>
                                <select required name="product_id" id="product_id" class="form-control">
                                    @if ($model->product)
                                        <option value="{{ $model->product->id }}">{{ $model->product->name }}</option>
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-12 mb-3 form-group">
                                <label for="files">Images</label>
                                <input type="file" accept=".jpg, .png, .webp" multiple name="files[]" id="files" class="form-control dropify">
                            </div>

                            @if ($model->files)
                                @php
                                    $files = explode(',', $model->files);
                                @endphp
                                @foreach ($files as $file)
                                    <div class="col-md-2 mb-3">
                                        <img src="{{ asset($file) }}" width="100" height="100" alt="">
                                    </div>
                                @endforeach
                            @endif

                            <div class="col-md-12 form-group mb-3">
                                <label for="rating">Rating <span class="text-danger">*</span></label>
                                <select name="rating" id="rating" class="form-control select">
                                    <option {{ $model->rating == 1 ? 'selected' : '' }} value="1">1</option>
                                    <option {{ $model->rating == 2 ? 'selected' : '' }} value="2">2</option>
                                    <option {{ $model->rating == 3 ? 'selected' : '' }} value="3">3</option>
                                    <option {{ $model->rating == 4 ? 'selected' : '' }} value="4">4</option>
                                    <option {{ $model->rating == 5 ? 'selected' : '' }} value="5">5</option>
                                </select>
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" required value="{{ $model->name }}">
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="email">Email </label>
                                <input type="text" name="email" id="email" class="form-control" value="{{ $model->email }}">
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="review">Review <span class="text-danger">*</span></label>
                                <textarea name="review" id="review" cols="30" rows="3" class="form-control">{{ $model->review }}</textarea>
                            </div>

                            <div class="col-md-12 mb-3 form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control select" required>
                                    <option {{ $model->status == 1 ? 'selected' : '' }} value="1">Active</option>
                                    <option {{ $model->status == 0 ? 'selected' : '' }} value="0">Inactive</option>
                                </select>
                            </div>

                            <div class="col-md-12 text-end form-group">
                                @if (Auth::guard('admin')->user()->hasPermissionTo('customer-review.update'))
                                    <button type="submit" class="btn btn-soft-success"  id="submit">
                                        <i class="bi bi-send"></i>
                                        Update
                                    </button>
                                    <button class="btn btn-soft-warning" style="display: none;" id="submitting" type="button" disabled>
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Loading...
                                    </button>
                                @endif
                                @if (Auth::guard('admin')->user()->hasPermissionTo('customer-review.view'))
                                    <a href="{{ route('admin.stuff.index') }}" class="btn btn-soft-danger">
                                        <i class="bi bi-backspace"></i>
                                        Back
                                    </a>
                                @endif
                            </div>
                    
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('backend/assets/js/dropify.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/pages/review.js') }}"></script>
    <script>
        _formValidation();
        _componentSelect();

        $('.dropify').dropify({
            imgFileExtensions: ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp']
        });
        
        $('#product_id').select2({
            width: '100%',
            placeholder: 'Select Product',
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

        $(document).on('click', '#button-addon2', function() {
            let number_of_reviews = $('#number_of_reviews').val();
            if(number_of_reviews <= 0) {
                toastr.error("Number of review is needed more then 0");
                return false;
            } 

            if(number_of_reviews > 50) {
                toastr.error("You can add maximum 50 review at a time");
                return false;
            }

            // $('#app').empty();

            function getRandomDate() {
                const start = new Date();
                const end = new Date();
                start.setFullYear(start.getFullYear() - 3);
                const randomDate = new Date(start.getTime() + Math.random() * (end.getTime() - start.getTime()));
                return randomDate.toISOString().split('T')[0];
            }

            function getRandomTime() {
                const hours = Math.floor(Math.random() * 24);
                const minutes = Math.floor(Math.random() * 60); 
                return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
            }

            for (let i = 0; i < number_of_reviews; i++) {
                const randomName = names[Math.floor(Math.random() * names.length)];
                const randomReview = reviews[Math.floor(Math.random() * reviews.length)];
                const randomDate = getRandomDate();
                const randomTime = getRandomTime();

                function getRandomRating() {
                    const random = Math.random() * 100;
                    if (random <= 5) {
                        return 3;
                    } else if (random <= 25) {
                        return 4;
                    } else {
                        return 5;
                    }
                }

                const randomRating = getRandomRating(); 

                $('#app').append(`
                    <div class="row mb-3 review-item">
                        <div class="col-md-3 form-group">
                            <label for="review_name_${i}">Name</label>
                            <input type="text" name="review_name[]" id="review_name_${i}" class="form-control" value="${randomName}" required>
                        </div>
                        
                        <div class="col-md-3 form-group">
                            <label for="review_rating_${i}">Number of Rating</label>
                            <input type="text" name="review_rating[]" id="review_rating_${i}" class="form-control number" value="${randomRating}">
                        </div>
                        
                        <div class="col-md-3 form-group">
                            <label for="review_date_${i}">Date</label>
                            <input type="date" name="review_date[]" id="review_date_${i}" class="form-control" value="${randomDate}" required>
                        </div>
                        
                        <div class="col-md-3 form-group">
                            <label for="review_time_${i}">Time</label>
                            <input type="time" name="review_time[]" id="review_time_${i}" class="form-control" value="${randomTime}" required>
                        </div>

                        <div class="col-md-12 mt-3 form-group">
                            <label for="review_${i}">Review</label>
                            <textarea rows="3" required name="review[]" id="review_${i}" class="form-control">${randomReview}</textarea>
                        </div>
                    </div>
                `);
            }

        })

        function formatProductSelection(product) {

            if (!product.id) {
                return product.text;
            }

            var defaultImageUrl = $('#default_product_image').val();
            var image_url = product.image_url ? product.image_url : defaultImageUrl;

            var productImage = '<img src="' + image_url + '" class="img-flag" style="height: 20px; width: 20px; margin-right: 10px;" />';
            return $('<span>' + productImage + product.text + '</span>');
        }
    </script>
@endpush