<div class="text-center d-flex gap-2">

    <a href="javascript:;" id="content_management"
        data-url="{{ route('admin.product.specification.edit.modal', $model['id']) }}" class="corposhop-icon"
        data-bs-toggle="tooltip" data-bs-placement="top" title="Keys">
        <i class="bi bi-info-circle"></i>
    </a>
    <a href="{{ route('admin.product.specification.edit.page', $model['id']) }}" class="corposhop-icon"
        data-bs-toggle="tooltip" data-bs-placement="top" title="Update Specifications">
        <i class="bi bi-send"></i>
    </a>
</div>
