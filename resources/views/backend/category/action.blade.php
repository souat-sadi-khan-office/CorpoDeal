<div class="dropdown">
    <a class="btn btn-outline-secondary btn-sm dropdown-toggle" href="javascript:;" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-three-dots-vertical"></i>
    </a>
  
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        <li>
            <a href="{{ URL::to($model->slug) }}" target="_blank" class="dropdown-item">
                <i class="bi bi-eye"></i> Visit {{ $model->name }}
            </a>
        </li>

        @if (Auth::guard('admin')->user()->hasPermissionTo('category.update'))
            <li>
                <a href="{{ route('admin.category.edit', ['id' => $model->id, 'sub' => isset($model->parent_id) ? true : null]) }}" class="dropdown-item">
                    <i class="bi bi-pen"></i> Update
                </a>
            </li>
        @endif

        @if (Auth::guard('admin')->user()->hasPermissionTo('category.delete'))
            <li>
                <a href="javascript:;" id="delete_item" data-id ="{{ $model->id }}" data-url="{{ route('admin.category.delete', $model->id) }}" class="dropdown-item">
                    <i class="bi bi-trash"></i> Delete
                </a>
            </li>
        @endif

        @if (Auth::guard('admin')->user()->hasPermissionTo('specification-key.view'))
            <li>
                <a href="{{ route('admin.category.keys', $model->id) }}" class="dropdown-item">
                    <i class="bi bi-list"></i> Specification Keys
                </a>
            </li>
        @endif
        
    </ul>
</div>