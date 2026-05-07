<div class="d-flex gap-2">
    @if(Auth::guard('admin')->user()->hasPermissionTo('wishlist.delete'))
        <a href="javascript:;" id="delete_item" data-id ="{{ $model->id }}" data-url="{{ route('admin.report.wishlist.delete', $model->id) }}" class="btn btn-sm btn-outline-dark" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
            <i class="bi bi-trash"></i>
        </a>
    @endif
</div>
