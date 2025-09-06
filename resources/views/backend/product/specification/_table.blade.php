<table class="table table-bordered table-striped">
    @foreach ($models as $data)
        <tr data-key-id="{{ $data[0]['key_id'] }}">
            <td colspan="5" class="bg-secondary text-white ">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="h6 mb-0"><b>{{ @$data[0]['specificationKey'] }}</b></h4>
                    </div>
                    <div class="col-md-4 text-end">
                        {{-- Move Up --}}
                        @if (!$loop->first)
                            <button class="btn btn-sm btn-light move-key-up"
                                    data-url="{{ route('admin.product.specification.keyMoveUp', [$product_id, $data[0]['key_id']]) }}">
                                <i class="bi bi-arrow-up"></i>
                            </button>
                        @endif

                        {{-- Move Down --}}
                        @if (!$loop->last)
                            <button class="btn btn-sm btn-light move-key-down"
                                    data-url="{{ route('admin.product.specification.keyMoveDown', [$product_id, $data[0]['key_id']]) }}">
                                <i class="bi bi-arrow-down"></i>
                            </button>
                        @endif

                    </div>
                </div>
            </td>
        </tr>

        @foreach ($data as $index => $item)
            <tr data-row-id="{{ $item['id'] }}">
                <td width="30%">{{ $item['specificationKeyType'] }}</td>
                <td width="30%">{{ $item['specificationKeyTypeAttribute'] }}</td>
                <td>
                    <div class="form-check form-switch col-md-7" style=" padding-left: 2.9em!important;">
                        <span>Key Feature? </span>
                        <input
                            data-url="{{ route('admin.product.specification.keyfeature', $item['id']) }}"
                            class="form-check-input" type="checkbox" role="switch" name="is_featured"
                            id="status{{ $item['id'] }}"
                            {{ $item['key_feature'] == 1 ? 'checked' : '' }}
                            data-id="{{ $item['id'] }}">
                    </div>
                </td>
                <td width="15%" class="text-end">
                    {{-- Move Up --}}
                    @if ($index > 0)
                        <button class="btn btn-sm btn-outline-primary move-up"
                            data-url="{{ route('admin.product.specification.moveUp', [$product_id, $item['id']]) }}">
                            <i class="bi bi-arrow-up"></i>
                        </button>
                    @endif

                    {{-- Move Down --}}
                    @if ($index < count($data) - 1)
                        <button class="btn btn-sm btn-outline-primary move-down"
                            data-url="{{ route('admin.product.specification.moveDown', [$product_id, $item['id']]) }}">
                            <i class="bi bi-arrow-down"></i>
                        </button>
                    @endif

                    {{-- Delete --}}
                    <a class="btn btn-sm btn-outline-danger" href="javascript:;" id="delete_specification"
                        data-id="{{ $item['id'] }}"
                        data-url="{{ route('admin.product.specification.delete', $item['id']) }}">
                        <i class="bi bi-trash"></i>
                    </a>
                </td>
            </tr>
        @endforeach
    @endforeach
</table>
