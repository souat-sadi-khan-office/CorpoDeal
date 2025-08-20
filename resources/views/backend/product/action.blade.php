<div class="dropdown">
    <a class="btn btn-outline-secondary btn-sm dropdown-toggle" href="javascript:;" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-three-dots-vertical"></i>
    </a>
  
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        <li>
            <a class="dropdown-item" href="{{ URL::to($model->slug) }}" target="_blank">
                <i class="bi bi-eye"></i>
                View
            </a>
        </li>
    
        @if (Auth::guard('admin')->user()->hasPermissionTo('product.update'))
            <li>
                <a class="dropdown-item" href="{{ route('admin.product.edit', $model->id) }}">
                    <i class="bi bi-pen"></i>
                    Edit
                </a>
            </li>
        @endif

        @if (Auth::guard('admin')->user()->hasPermissionTo('product.specification'))
            <li>
                <a class="dropdown-item" href="{{ route('admin.product.manage.specification',$model->id) }}">
                    <i class="bi bi-archive"></i>
                    Specifications
                </a>
            </li>
        @endif

        @if (Auth::guard('admin')->user()->hasPermissionTo('stock.create'))
            <li>
                <a class="dropdown-item" href="{{ route('admin.stock.create', ['product_id' => $model->id]) }}">
                    <i class="bi bi-archive"></i>
                    Add Stock
                </a>
            </li>
        @endif

        @if (Auth::guard('admin')->user()->hasPermissionTo('stock.view'))
            <li>
                <a class="dropdown-item" href="{{ route('admin.product.stock', $model->id) }}">
                    <i class="bi bi-archive"></i>
                    Stock Report
                </a>
            </li>
        @endif

        @if (Auth::guard('admin')->user()->hasPermissionTo('product.duplicate'))
            <li>
                <a class="dropdown-item" href="javascript:;" id="duplicate_item" data-id="{{ $model->id }}" data-url="{{ route('admin.product.duplicate', $model->id) }}">
                    <i class="bi bi-copy"></i>
                    Duplicate
                </a>
            </li>
        @endif

        {{-- @if (Auth::guard('admin')->user()->hasPermissionTo('product.delete'))
            <li>
                <a class="dropdown-item" href="javascript:;" id="delete_item" data-id="{{ $model->id }}" data-url="{{ route('admin.product.destroy',$model->id) }}">
                    <i class="bi bi-trash"></i>
                    Remove
                </a>
            </li>
        @endif --}}
    </ul>
</div>