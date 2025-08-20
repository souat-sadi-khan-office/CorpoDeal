<div class="d-flex gap-2">

@if (Auth::guard('admin')->user()->hasPermissionTo('flash-deal.update'))
    <a href="{{ route('admin.flash-deal.edit', $model->id) }}" class="corposhop-icon corposhop-icon-sm" data-bs-toggle="tooltip" data-bs-placement="Top" title="Edit">
        <i class="bi bi-pen"></i>
    </a>
@endif

@if (Auth::guard('admin')->user()->hasPermissionTo('flash-deal.delete'))
    <a href="javascript:;" id="delete_item" data-id ="{{ $model->id }}" data-url="{{ route('admin.flash-deal.destroy',$model->id) }}" class="corposhop-icon corposhop-icon-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
        <i class="bi bi-trash"></i>
    </a>
@endif
</div>
