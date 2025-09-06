<div class="d-flex gap-2">
    <a href="{{ route('admin.home-page-category.edit', $model->id) }}" class="btn btn-sm btn-outline-dark" data-bs-toggle="tooltip" data-bs-placement="Top" title="Edit">
        <i class="bi bi-pen"></i>
    </a>

    <a href="javascript:;" id="delete_item" data-id ="{{ $model->id }}" data-url="{{ route('admin.home-page-category.destroy',$model->id) }}" class="btn btn-sm btn-outline-dark" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
        <i class="bi bi-trash"></i>
    </a>
</div>
