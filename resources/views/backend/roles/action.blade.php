@if ($model->id != 1)
    <div class="d-flex gap-2">

    @if (Auth::guard('admin')->user()->hasPermissionTo('roles.update'))
        <a href="{{ route('admin.roles.edit', $model->id) }}" class="corposhop-icon corposhop-icon-sm">
            <i class="bi bi-pen"></i>
        </a>
    @endif

    @if (Auth::guard('admin')->user()->hasPermissionTo('roles.delete'))
        <a href="javascript:;" id="delete_item" data-id ="{{ $model->id }}" data-url="{{ route('admin.roles.destroy',$model->id) }}" class="corposhop-icon corposhop-icon-sm">
            <i class="bi bi-trash"></i>
        </a>
    @endif
    </div>

@endif
