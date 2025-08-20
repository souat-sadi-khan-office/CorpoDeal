
@if (Auth::guard('admin')->user()->hasPermissionTo('shipping-configuration.city'))
    <div class="d-flex gap-2">

    <a href="javascript:;" id="content_management" data-url="{{ route('admin.city.edit', $model->id) }}" class="corposhop-icon corposhop-icon-sm" data-bs-toggle="tooltip" data-bs-placement="Top" title="Edit">
        <i class="bi bi-pen"></i>
    </a>

    <a href="javascript:;" id="delete_item" data-id ="{{ $model->id }}" data-url="{{ route('admin.city.destroy',$model->id) }}" class="corposhop-icon corposhop-icon-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
        <i class="bi bi-trash"></i>
    </a>
    </div>

@endif
