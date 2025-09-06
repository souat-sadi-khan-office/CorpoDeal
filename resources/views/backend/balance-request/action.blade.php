<div class="dropdown">
    <a class="btn btn-sm btn-outline-dark btn-sm dropdown-toggle" href="javascript:;" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-three-dots-vertical"></i>
    </a>

    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        <li>
            <a class="dropdown-item" href="{{ route('admin.balance.request.view', $model->id) }}" target="_blank">
                <i class="bi bi-eye"></i>
                View
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{route('admin.order.invoice',['id' => $model->id, 'download' => true])}}">
                <i class="bi bi-box-arrow-down"></i>
               Invoice
            </a>
        </li>
    </ul>
</div>
