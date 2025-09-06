@php
    $last = App\Models\Banner::orderBy('position', 'DESC')->first()->position;
@endphp
<div class="d-flex gap-2">
    @if ($model->position != 1)
        <button class="btn btn-sm btn-success move-banner" data-id="{{ $model->id }}" data-direction="up" title="Move Up">
            <i class="bi bi-arrow-up-short"></i>
        </button>
    @endif

    @if ($model->position != $last)
        <button class="btn btn-sm btn-warning move-banner" data-id="{{ $model->id }}" data-direction="down" title="Move Down">
            <i class="bi bi-arrow-down-short"></i>
        </button>
    @endif
</div>
