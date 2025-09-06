{{-- @if (Auth::guard('admin')->user()->hasPermissionTo('stock.delete'))
    <a href="javascript:;" id="delete_item" data-id="{{ $model->id }}" data-url="{{ route('admin.stock.destroy',$model->id) }}" class="corposhop-icon corposhop-icon-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
        <i class="bi bi-trash"></i>
    </a>
@endif --}}

<div class="dropdown">
    <a class="btn btn-outline-secondary btn-sm dropdown-toggle" href="javascript:;" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-three-dots-vertical"></i>
    </a>
  
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        @if (Auth::guard('admin')->user()->hasPermissionTo('stock.create'))
            <a href="javascript:;" data-url="{{ route('admin.stock.show', $model->id) }}"
            class="dropdown-item" id="content_management">
                <i class="bi bi-eye" style="margin-right: 5px;"></i> View
            </a>
        @endif

        @if (Auth::guard('admin')->user()->hasPermissionTo('product.serial-add'))
            <a href="javascript:;" data-url="{{ route('admin.supplier.product.serial-form', $model->id) }}" class="dropdown-item"  id="content_management">
                <i class="bi bi-sort-numeric-up" style="margin-right: 5px;"></i> Update Serial
            </a>
        @endif
    
        @if (Auth::guard('admin')->user()->hasPermissionTo('product.serial-add'))
            <a href="javascript:;" data-url="{{ route('admin.supplier.product.serial-form-csv', $model->id) }}" class="dropdown-item" id="content_management">
                <i class="bi bi-upload" style="margin-right: 5px;"></i> Bulk Upload
            </a>
        @endif
    </ul>
</div>