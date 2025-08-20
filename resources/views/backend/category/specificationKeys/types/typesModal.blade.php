<h3 class="m-4">Specification Key Types</h3>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-bordered table-striped table-hover" id="data-table">
                    <thead>
                        <tr>
                            <th style="width: 13%;">Created By</th>
                            <th style="width: 5%;">Status</th>
                            <th style="width: 5%;">Filter</th>
                            <th style="width: 77%; text-align:center">Name, &nbsp;  Filter Name (If Show in Filter ON ) &nbsp;&nbsp; & &nbsp;&nbsp;
                                Position <span class="text-danger">*</span></th>
                            <th style="width: 5%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($models as $data)
                            <tr data-row-id="{{ $data->id }}">
                                <td>{{ $data->name }}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input
                                            data-url="{{ route('admin.category.specification.type.status', $data->id) }}"
                                            class="form-check-input" type="checkbox" role="switch" name="status"
                                            id="status{{ $data->id }}" {{ $data->status == 1 ? 'checked' : '' }}
                                            data-id="{{ $data->id }}">
                                    </div>
                                </td>
                                <td>
                                    @if (Auth::guard('admin')->user()->hasPermissionTo('specification-key.update'))
                                        <div class="form-check form-switch">
                                            <input
                                                data-url="{{ route('admin.category.specification.type.filter', $data->id) }}"
                                                class="form-check-input" type="checkbox" role="switch" name="is_featured"
                                                id="filter{{ $data->id }}"
                                                {{ $data->show_on_filter == 1 ? 'checked' : '' }}
                                                data-id="{{ $data->id }}">
                                        </div>
                                    @else
                                        <span class="badge bg-{{ $data->status == 1 ? 'success' : 'warning' }}">{{ $data->status == 1 ? 'Filter' : 'Not in Filter' }}</span>
                                    @endif
                                </td>

                                <td>
                                    <form
                                        action="{{ route('admin.category.specification.type.position&filter', $data->id) }}"
                                        method="POST" class="nested-form" data-id="{{ $data->id }}">
                                        @csrf
                                        @method('POST')
                                        <div class="row">
                                            <div class="col-md-5 form-group">
                                                <input type="text" name="name"
                                                    id="name{{ $data->id }}"
                                                    class="form-control name-input"
                                                    value="{{ $data->name }}">
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <input type="text" name="filter_name"
                                                    id="filter_name{{ $data->id }}"
                                                    class="form-control filter_name-input"
                                                    value="{{ $data->filter_name }}" {{ $data->show_on_filter == 0 ? 'disabled' : '' }}>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="row">
                                                    <div class="col-md-7 form-group">
                                                        <input type="number" name="position"
                                                            id="position{{ $data->id }}"
                                                            class="form-control position-input" required
                                                            value="{{ $data->position }}">
                                                    </div>
                                                    <div class="col-md-5 mt-1 form-group">
                                                        <button class="btn btn-sm btn-soft-success submit-btn"
                                                            type="submit">
                                                            Update
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </td>

                                <td>
                                    <a href="javascript:;" id="delete_specification" data-id="{{ $data->id }}"
                                        data-url="{{ route('admin.category.specification.type.delete', $data->id) }}"
                                        class="corposhop-icon corposhop-icon-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        _initializeMultipleFormsValidation();

        $('.form-check-input[name="is_featured"]').change(function() {
        const checkbox = $(this);
        const id = checkbox.data('id');
        const filterInput = $('#filter_name' + id);

        filterInput.prop('disabled', !checkbox.is(':checked'));
    });
    });
</script>
