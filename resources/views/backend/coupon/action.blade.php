<div class="d-flex gap-2">

@if (Auth::guard('admin')->user()->hasPermissionTo('coupon.assign-to-customer'))
    <a href="javascript:;" id="content_management" data-url="{{ route('admin.coupon.assign', $model->id) }}" class="corposhop-icon corposhop-icon-sm" data-bs-toggle="tooltip" data-bs-placement="Top" title="Assign Coupon To User">
        <i class="bi bi-gift"></i>
    </a>
@endif

@if (Auth::guard('admin')->user()->hasPermissionTo('coupon.update'))
    <a href="javascript:;" id="content_management" data-url="{{ route('admin.coupon.edit', $model->id) }}" class="corposhop-icon corposhop-icon-sm" data-bs-toggle="tooltip" data-bs-placement="Top" title="Edit">
        <i class="bi bi-pen"></i>
    </a>
@endif

@if (Auth::guard('admin')->user()->hasPermissionTo('coupon.delete'))
    <a href="javascript:;" id="delete_item" data-id ="{{ $model->id }}" data-url="{{ route('admin.coupon.destroy',$model->id) }}" class="corposhop-icon corposhop-icon-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
        <i class="bi bi-trash"></i>
    </a>
@endif
</div>
