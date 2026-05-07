@if (Auth::guard('admin')->user()->hasPermissionTo('supplier.update'))
    <a href="javascript:;" data-url="{{ route('admin.supplier.edit', ['id' => $model->id]) }}" id="content_management" class="btn btn-sm btn-outline-dark">
        <i class="bi bi-pen"></i> Update
    </a>
@endif