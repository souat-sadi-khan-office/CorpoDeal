<div class="d-flex gap-2">

@if(Auth::guard('admin')->user()->hasPermissionTo('product.update'))
    <a href="{{ route('admin.product.edit', $model->product->id) }}" class="corposhop-icon corposhop-icon-sm" data-bs-toggle="tooltip" data-bs-placement="Top" title="Edit">
        <i class="bi bi-pen"></i>
    </a>
@endif

@if(Auth::guard('admin')->user()->hasPermissionTo('wishlist.delete'))
    <a href="javascript:;" id="delete_item" data-id ="{{ $model->id }}" data-url="{{ route('admin.report.wishlist.delete', $model->id) }}" class="corposhop-icon corposhop-icon-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
        <i class="bi bi-trash"></i>
    </a>
@endif
</div>
