<div class="d-flex gap-2">

<a id="content_management" href="" data-url="{{ route('admin.message.view', $model->id) }}" class="corposhop-icon corposhop-icon-sm" data-bs-toggle="tooltip" data-bs-placement="Top" title="Edit">
    <i class="bi bi-eye"></i>
</a>

@if(Auth::guard('admin')->user()->hasPermissionTo('contact-message.delete'))
    <a href="javascript:;" id="delete_item" data-id ="{{ $model->id }}" data-url="{{ route('admin.message.destroy',$model->id) }}" class="corposhop-icon corposhop-icon-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
        <i class="bi bi-trash"></i>
    </a>
@endcan
</div>
