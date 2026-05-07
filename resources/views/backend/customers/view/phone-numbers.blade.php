<div class="row">
    <div class="col-md-12 table-responsive">
        <div class="row">
            <div class="col-md-9">
                <h4 class="h6 mb-0">
                    <b>User Phone Information</b>
                </h4>
            </div>
            <div class="col-md-3 text-end">
                <a id="content_management" href="javascript:;" data-url="{{ route('admin.customer.phone.create', $model->id) }}" class="btn btn-sm btn-outline-dark">
                    <i class="bi bi-plus"></i>
                    Add New Phone Number
                </a>
            </div>
        </div>
        
        <table class="table mt-3 table-bordered" id="user-address-table">
            <thead>
                <th width="5%">#</th>
                <th>Date</th>
                <th>Phone</th>
                <th>Verified</th>
                <th width="5%">Default</th>
                <th>Action</th>
            </thead>
            <tbody>
                @if ($model->phones && count($model->phones) > 0)
                    @php($phoneCounter = 1)
                    @foreach ($model->phones()->orderBy('id', 'DESC')->get()  as $phone)
                        <tr>
                            <td>{{ $phoneCounter }}</td>
                            <td>{{ get_system_date($phone->created_at) }} {{ get_system_time($phone->created_at) }}</td>
                            <td>{{ $phone->phone_number }}</td>
                            <td>
                                <span class="badge bg-{{ $phone->is_verified == 1 ? 'success' : 'warning' }}">{{ $phone->is_verified == 1 ? 'Verified' : 'Not Verified' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $phone->is_default == 1 ? 'success' : 'info' }}">{{ $phone->is_default == 1 ? 'Default' : 'Normal' }}</span>
                            </td>
                            <td>
                                <a id="content_management" href="javascript:;" data-url="{{ route('admin.customer.phone.edit', $phone->id) }}" class="btn btn-sm btn-outline-dark" data-bs-toggle="tooltip" data-bs-placement="Top" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="javascript:;" id="delete_item" data-id ="{{ $model->id }}" data-url="{{ route('admin.customer.phone.destroy',$phone->id) }}" class="btn btn-sm btn-outline-dark" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @php($phoneCounter++)
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">
                            {!! App\CPU\Images::show('pictures/none.gif') !!} <br>
                            <b>Nothing to show</b>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>