    <a href="javascript:;" id="content_management" data-url="{{ route('admin.product.specification.edit.modal', $model['id']) }}" class="btn btn-sm btn-outline-dark" title="Update Specification">
        <i class="bi bi-sliders"></i>
    </a>

    <a href="javascript:;" id="delete_item" data-id ="{{ $model['id'] }}" data-url="{{ route('admin.product.specification.edit.empty',$model['id']) }}" class="btn btn-sm btn-outline-dark" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove Specification">
        <i class="bi bi-trash"></i>
    </a>
    <!-- <a href="{{ route('admin.product.specification.edit.page', $model['id']) }}" class="btn btn-sm btn-outline-dark" title="Update Specification in New Page">
        <i class="bi bi-window-stack"></i>
    </a> -->