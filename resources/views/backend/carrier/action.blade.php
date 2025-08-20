{{-- @if (Auth::guard('admin')->user()->hasPermissionTo('city.update')) --}}
    {{-- <a href="{{ route('admin.carrier.edit', $model->id) }}" class="btn btn-soft-warning" data-bs-toggle="tooltip" data-bs-placement="Top" title="Edit">
        <i class="bi bi-pen"></i>
    </a> --}}
{{-- @endif --}}

{{-- @if (Auth::guard('admin')->user()->hasPermissionTo('city.delete')) --}}
    <a href="javascript:;" id="delete_item" data-id ="{{ $model->id }}" data-url="{{ route('admin.carrier.destroy',$model->id) }}" class="corposhop-icon corposhop-icon-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
        <i class="bi bi-trash"></i>
    </a>
{{-- @endif --}}
