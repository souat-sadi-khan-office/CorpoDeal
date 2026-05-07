@extends('backend.layouts.app')
@section('title', 'Image Management')

@section('page_name')
<div class="app-content-header py-3 bg-light border-bottom">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-add-fill"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Image Management</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
@endsection

@section('content')
<style>
    .folder-row {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    .file-icon {
        color: #0d6efd;
    }
    .folder-icon {
        color: #ffc107;
    }

    .custom-tabs {
    border-bottom: 2px solid #e9ecef;
    gap: 10px;
    flex-wrap: nowrap; /* এক লাইনে রাখবে */
}

.custom-tabs .nav-link {
    white-space: nowrap; /* লাইন ব্রেক ঠেকাবে */
    border: none;
    background: #f8f9fa;
    border-radius: 8px 8px 0 0;
    color: #495057;
    padding: 10px 18px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.custom-tabs .nav-link i {
    margin-right: 6px;
    font-size: 16px;
}

.custom-tabs .nav-link:hover {
    background: #e9ecef;
    color: #0d6efd;
}

.custom-tabs .nav-link.active {
    background: #0d6efd;
    color: #fff;
    box-shadow: 0px 4px 6px rgba(13, 110, 253, 0.2);
}

.custom-tabs .nav-link.active i {
    color: #fff;
}

</style>
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <div class="row mb-3">
                <div class="col-md-8">
                    <h3 class="mb-0">Image Upload</h3>
                </div>
                <div class="col-md-4 text-end">
                    <ul class="nav nav-tabs custom-tabs justify-content-end" id="imageTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="folder-tab" data-bs-toggle="tab" data-bs-target="#folder" type="button" role="tab" aria-controls="folder" aria-selected="false">
                                <i class="bi bi-archive"></i> Create Folder
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload" type="button" role="tab" aria-controls="upload" aria-selected="false">
                                <i class="bi bi-cloud-arrow-up"></i> Upload Files
                            </button>
                        </li>
                    </ul>

                </div>
            </div>
            
            <!-- Tab Content -->
            <div class="tab-content" id="imageTabContent">
                <div class="tab-pane fade" id="folder" role="tabpanel" aria-labelledby="folder-tab">
                    <form action="{{ route('admin.image.create-folder') }}" method="POST" class="border p-3 rounded bg-light mb-3">
                        @csrf
                        <div class="mb-3">
                            <label for="folderName" class="form-label">Folder Name</label>
                            <input type="text" class="form-control @error('folder_name') is-invalid @enderror" id="folderName" name="folder_name" required placeholder="Enter folder name">
                            @error('folder_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                
                        @if (!empty($currentFolder))
                            <input type="hidden" name="parent_folder" value="{{ $currentFolder }}">
                        @endif
                
                        <button type="submit" class="btn btn-sm btn-dark">
                            <i class="bi bi-folder-plus"></i> Create Folder
                        </button>
                    </form>
                </div>
            
                <div class="tab-pane fade" id="upload" role="tabpanel" aria-labelledby="upload-tab">
                    <form action="{{ route('admin.image.upload') }}" method="POST" enctype="multipart/form-data" class="border p-3 rounded bg-light mb-3">
                        @if (!empty($currentFolder))
                            <input type="hidden" name="parent_folder" value="{{ $currentFolder }}">
                        @endif
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Upload Files or Folder</label>
                            <input class="form-control" type="file" name="images[]" multiple>
                            <small class="form-text text-muted">Select multiple files or an entire folder (folder structure will be preserved).</small>
                        </div>
                        <button type="submit" class="btn btn-sm btn-dark">
                            <i class="bi bi-cloud-arrow-up"></i> Upload
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    @if ($currentFolder)
                        <div class="mb-3">
                            <a href="{{ route('admin.image.index', ['folder' => $parentFolder]) }}"
                            class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left-circle"></i> Back to Parent
                            </a>
                            <span class="ms-2 text-muted">Current Folder: <strong>{{ $currentFolder }}</strong></span>
                        </div>
                    @endif
                </div>
                <div class="col-md-12 table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Size</th>
                                <th>Modified</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($folders as $folder)
                                @php
                                    $folderPath = storage_path('app/public/' . $folder);
                                    $folderName = basename($folder);
                                    $modifiedDate = \Carbon\Carbon::createFromTimestamp(filemtime($folderPath))->format('d F, Y h:i A');
                                @endphp
                                <tr class="table-secondary">
                                    <td>
                                        <i class="bi bi-folder-fill text-warning"></i> {{ $folderName }}
                                    </td>
                                    <td>—</td>
                                    <td>{{ $modifiedDate }}</td>
                                    <td class="text-end">
                                        <a data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Go To Folder"
                                            class="btn btn-outline-dark btn-sm open-folder"
                                            href="{{ route('admin.image.index', ['folder' => $folder]) }}">
                                            <i class="bi bi-folder2-open"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                            @foreach ($images as $image)
                                @php
                                    $filePath = storage_path('app/public/' . $image);
                                    $fileName = basename($image);
                                    $folderPath = str_replace($fileName, '', $image);
                                    $uploadDate = \Carbon\Carbon::createFromTimestamp(filemtime($filePath))->format('d F, Y h:i A');
                                    $fileSizeInBytes = filesize($filePath);
                                @endphp
                                <tr>
                                    <td>
                                        <img style="width:105px;" src="{{ asset('storage/' . $image) }}" class="card-img-top" alt="{{ $fileName }}">
                                    </td>
                                    <td>{{ formatSizeUnits($fileSizeInBytes) }}</td>
                                    <td>{{ $uploadDate }}</td>
                                    <td class="text-end">
                                        <button data-bs-toggle="tooltip" data-bs-placement="top" title="Copy Picture URL" class="btn btn-outline-dark btn-sm copy-image" data-url="{{ asset('storage/' . $image) }}">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript --}}
<script>
    document.querySelectorAll('.copy-image').forEach(button => {
        button.addEventListener('click', function () {
            const url = this.getAttribute('data-url');
            navigator.clipboard.writeText(url).then(() => {
                toastr.success("Image URL copied to clipboard!");
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        });
    });
</script>
@endsection
