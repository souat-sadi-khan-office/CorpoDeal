<h3 class="m-4">Specification Keys</h3>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-bordered table-striped table-hover" id="data-table">
                    <thead>
                        <tr>
                            <th style="text-align:center;">Name & Position</th>
                            <th style="width: 21%;">Created By</th>
                            <th style="width: 6%;">Status</th>
                            <th style="width: 6%;">Is Public</th>
                            <th style="width: 7%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($models as $data)
                            <tr data-row-id="{{ $data->id }}">
                                <td>
                                    <form action="{{ route('admin.category.specification.key.position', $data->id) }}"
                                        method="POST" class="nested-form" data-id="{{ $data->id }}">
                                        @csrf
                                        @method('POST')
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <input type="text" name="name" id="name{{ $data->id }}"
                                                    class="form-control name-input" value="{{ $data->name }}">
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-6 form-group">
                                                        <input type="number" name="position"
                                                            id="position{{ $data->id }}"
                                                            class="form-control position-input" required
                                                            value="{{ $data->position }}">
                                                    </div>
                                                    <div class="col-md-6 mt-1 form-group">
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
                                <td>{{ $data->admin->name }}</td>
                                <td>
                                    @if (Auth::guard('admin')->user()->hasPermissionTo('specification-key.update'))
                                        <div class="form-check form-switch">
                                            <input
                                                data-url="{{ route('admin.category.specification.key.status', $data->id) }}"
                                                class="form-check-input" type="checkbox" role="switch" name="status"
                                                id="status{{ $data->id }}" {{ $data->status == 1 ? 'checked' : '' }}
                                                data-id="{{ $data->id }}">
                                        </div>
                                    @else
                                        <span class="badge bg-{{ $data->status == 1 ? 'success' : 'warning' }}">{{ $data->status == 1 ? 'Active' : 'Inactive' }}</span>
                                    @endif
                                </td>
                                <td>

                                    @if (Auth::guard('admin')->user()->hasPermissionTo('specification-key.update'))
                                        <div class="form-check form-switch">
                                            <input
                                                data-url="{{ route('admin.category.specification.key.is_public', $data->id) }}"
                                                class="form-check-input" type="checkbox" role="switch" name="is_public"
                                                id="is_public{{ $data->id }}" {{ $data->is_public == 1 ? 'checked' : '' }}
                                                data-id="{{ $data->id }}">
                                        </div>
                                    @else
                                        <span class="badge bg-{{ $data->status == 1 ? 'success' : 'warning' }}">{{ $data->status == 1 ? 'Public' : 'Private' }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if (Auth::guard('admin')->user()->hasPermissionTo('specification-key.delete'))
                                        <a href="javascript:;" id="delete_specification" data-id="{{ $data->id }}"
                                            data-url="{{ route('admin.category.specification.key.delete', $data->id) }}"
                                            class="corposhop-icon corposhop-icon-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    @endif
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
    // Call the function to initialize the validation and submission
    $(document).ready(function() {
        _initializeMultipleFormsValidation();
        _ispublicUpdate();
    });
</script>
