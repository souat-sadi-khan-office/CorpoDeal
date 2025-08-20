<div class="dropdown">
    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton_{{ $model->id }}" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-three-dots-vertical"></i>
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{ $model->id }}">
        @if (Auth::guard('admin')->user()->hasPermissionTo('customer.view'))
            <a href="{{ route('admin.customer.view', $model->id) }}" class="dropdown-item" data-bs-toggle="tooltip" data-bs-placement="Top" title="View Detailed Information">
                View Detailed Information    
            </a>
        @endif

        @if (Auth::guard('admin')->user()->hasPermissionTo('customer.send-gift-points-to-customer'))
            <a id="content_management" href="javascript:;" data-url="{{ route('admin.customer.show', $model->id) }}" class="dropdown-item" data-bs-toggle="tooltip" data-bs-placement="Top" title="Points">
                Send Gift Points
            </a>
        @endif
        
        @if (Auth::guard('admin')->user()->hasPermissionTo('customer.update'))
            <a id="content_management" href="javascript:;" data-url="{{ route('admin.customer.edit', $model->id) }}" class="dropdown-item" data-bs-toggle="tooltip" data-bs-placement="Top" title="Edit">
                Update User Information
            </a>
        @endif    

        @if (Auth::guard('admin')->user()->hasPermissionTo('customer.delete'))
            <a href="javascript:;" id="delete_item" data-id ="{{ $model->id }}" data-url="{{ route('admin.customer.destroy',$model->id) }}" class="dropdown-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                Remove User
            </a>
        @endif    
        </div>
</div>