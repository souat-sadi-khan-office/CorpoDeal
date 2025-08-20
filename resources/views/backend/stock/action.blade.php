<div class="d-flex gap-2">

@if (Auth::guard('admin')->user()->hasPermissionTo('stock.create'))
    <a href="javascript:;" data-url="{{ route('admin.stock.show', $model->id) }}" class="corposhop-icon corposhop-icon-sm" data-bs-toggle="tooltip" data-bs-placement="Top" title="View" id="content_management">
        <i class="bi bi-eye"></i>
    </a>
@endif

@if (Auth::guard('admin')->user()->hasPermissionTo('stock.delete'))
    <a href="javascript:;" id="delete_item" data-id ="{{ $model->id }}" data-url="{{ route('admin.stock.destroy',$model->id) }}" class="corposhop-icon corposhop-icon-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
        <i class="bi bi-trash"></i>
    </a>
@endif
</div>
