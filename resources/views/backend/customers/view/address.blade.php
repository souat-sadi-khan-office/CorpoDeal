<div class="row">
    <div class="col-md-12 table-responsive">
        <div class="row">
            <div class="col-md-9">
                <h4 class="h6 mb-3">
                    <b>User Address Information</b>
                </h4>
            </div>
            <div class="col-md-3 text-end">
                <a id="content_management" href="javascript:;" data-url="{{ route('admin.customer.address.create', $model->id) }}" class="btn btn-sm btn-outline-secondary" style="padding: 3px 8px; font-size: 12px;">
                    <i class="bi bi-plus"></i>
                    Add
                </a>
            </div>
        </div>
        
        <table class="table table-bordered" id="user-address-table">
            <thead>
                <th width="5%">#</th>
                <th>Date</th>
                <th>Name</th>
                <th>Company</th>
                <th width="5%">Default</th>
                <th>Action</th>
            </thead>
            <tbody>
                @if ($model->address && count($model->address) > 0)
                    @php($addressCounter = 1)
                    @foreach ($model->address()->orderBy('id', 'DESC')->get()  as $address)
                        <tr>
                            <td>{{ $addressCounter }}</td>
                            <td>{{ get_system_date($address->created_at) }} {{ get_system_time($address->created_at) }}</td>
                            <td>{{ $address->first_name . ' '. $address->last_name }}</td>
                            <td>{{ $address->company_name }}</td>
                            <td>
                                <span class="badge bg-{{ $address->is_default == 1 ? 'success' : 'info' }}">{{ $address->is_default == 1 ? 'Default' : 'Normal' }}</span>
                            </td>
                            <td>
                                <a id="content_management" href="javascript:;" data-url="{{ route('admin.customer.address.edit', $address->id) }}" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="Top" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="javascript:;" id="delete_item" data-id ="{{ $model->id }}" data-url="{{ route('admin.customer.address.destroy',$address->id) }}" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                Address: <b>{{ $address->address }}</b> <br>
                                Address Line 2: <b>{{ $address->address_line_2 }}</b> <br>
                                Area: <b>{{ $address->area }}</b> <br>
                                Postcode: <b>{{ $address->postcode }}</b> <br>
                                City: 
                                @if ($address->city)
                                    <b>{{ $address->city->name }}</b>
                                @endif 
                                <br>
                                Country: 
                                @if ($address->country)
                                    <b>{{ $address->country->name }}</b>
                                @endif 
                                <br>
                                Zone: 
                                @if ($address->zone)
                                    <b>{{ $address->zone->name }}</b>
                                @endif 
                            </td>
                        </tr>

                        @php($addressCounter++)
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