@extends('backend.layouts.app', ['modal' => 'xl'])
@section('title', 'Public Specification Keys')
@push('style')
    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush
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
                        <li class="breadcrumb-item active" aria-current="page">Public Specification Keys</li>
                    </ol>
                </div>

                @if (Auth::guard('admin')->user()->hasPermissionTo('specification-key.create'))
                <div class="col-sm-6 text-end">
                    <a href="javascript:;" data-url="{{ route('admin.category.specification.key.create') }}" id="content_management" class="btn btn-soft-success">
                        <i class="bi bi-plus"></i>
                        Create New
                    </a>
                </div>
                @endif
                <p class="mx-auto">These Keys Can be Accessed In Every Selected Category when Product Specification Create/Update.</p>
            </div>
        </div>
    </div>
@endsection
@section('content')
@include('backend.category.specificationKeys.keysModal')
 {{-- Pagination Links with Bootstrap 5 --}}
 <div class="d-flex justify-content-between align-items-center">

    @include('frontend.components.paginate',['products'=>$models])

</div>
@endsection
@push('script')
<script>
    $(document).ready(function() {
      _initializeMultipleFormsValidation();
      _componentRemoteModalLoadAfterAjax();
      _statusUpdate();
      _ispublicUpdate();
  });
</script>
@endpush


