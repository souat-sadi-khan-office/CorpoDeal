<div class="d-flex gap-2">

<a href="{{ URL::to($model->slug) }}" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Visit {{ $model->name }}" class="corposhop-icon corposhop-icon-sm">
    <i class="bi bi-eye"></i>
</a>

@if (Auth::guard('admin')->user()->hasPermissionTo('category.update'))
    <a href="{{ route('admin.category.edit', ['id' => $model->id, 'sub' => isset($model->parent_id) ? true : null]) }}"
        title="Edit" class="corposhop-icon corposhop-icon-sm">
        <i class="bi bi-pen"></i>
    </a>
@endif

@if (Auth::guard('admin')->user()->hasPermissionTo('category.delete'))
    <a href="javascript:;" id="delete_item" data-id ="{{ $model->id }}"
        data-url="{{ route('admin.category.delete', $model->id) }}" class="corposhop-icon corposhop-icon-sm" data-bs-toggle="tooltip"
        data-bs-placement="top" title="Delete">
        <i class="bi bi-trash"></i>
    </a>
@endif

@if (Auth::guard('admin')->user()->hasPermissionTo('specification-key.view'))
    <a href="{{ route('admin.category.keys', $model->id) }}" title="Specification Keys" class="corposhop-icon corposhop-icon-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Visit {{ $model->name }}">
        <i class="bi bi-list"></i>
    </a>
@endif
</div>
