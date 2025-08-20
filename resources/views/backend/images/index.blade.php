@extends('backend.layouts.app')
@section('title', 'Image Management')
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
                        <li class="breadcrumb-item active" aria-current="page">Image Management</li>
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
            <div class="col-md-12">
                <h2>Image Upload</h2>

                    <!-- Multiple File Upload Form -->
                    @if (Auth::guard('admin')->user()->hasPermissionTo('image-upload.upload'))
                        <form action="{{ route('admin.image.upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <input type="file" name="images[]" multiple>
                                <button type="submit" class="btn btn-primary mt-2">Upload Images</button>
                            </div>
                        </form>
                    @endif


                    <!-- Display Uploaded Images -->
                    <div class="mt-4">
                        <h4>Uploaded Images</h4>
                        <div class="row">
                            @foreach ($images as $image)
                                @php
                                    $filePath = storage_path('app/public/' . $image);
                                    $fileName = basename($image);
                                    $uploadDate = \Carbon\Carbon::createFromTimestamp(filemtime($filePath))->format('Y-m-d');
                                    $uploadDate = get_system_date($uploadDate);
                                @endphp
                                <div class="col-md-2 mb-3">
                                    <div class="card">
                                        <img src="{{ asset('storage/' . $image) }}" alt="Image" class="card-img-top">
                                        <div class="card-body text-center">
                                            <p class="card-text">{{ basename($image) }}</p>
                                            <p class="text-muted">
                                                Uploaded on: {{ $uploadDate }}
                                            </p>
                                            @if (Auth::guard('admin')->user()->hasPermissionTo('image-upload.delete'))
                                                <button class="btn btn-danger btn-sm delete-image" data-filename="{{ basename($image) }}">
                                                    Delete
                                                </button>
                                            @endif
                                            <button class="btn btn-secondary btn-sm copy-image" data-url="{{ asset('storage/' . $image) }}">Copy</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.delete-image').forEach(button => {
        button.addEventListener('click', function() {
            const filename = this.getAttribute('data-filename');
            fetch(`{{ url('admin/image-delete/') }}/${filename}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        });
    });

    // Copy image URL to clipboard
    document.querySelectorAll('.copy-image').forEach(button => {
        button.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            navigator.clipboard.writeText(url).then(() => {
                alert('Image URL copied to clipboard!');
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        });
    });
</script>
@endsection
