<div class="row">
    <div class="col-md-12 table-responsive">
        <div class="row">
            <div class="col-md-12">
                <h4 class="h6 mb-3">
                    <b>User Contact Messages</b>
                </h4>
            </div>
        </div>
        
        <table class="table table-bordered" id="user-address-table">
            <thead>
                <th width="5%">#</th>
                <th>Date</th>
                <th>Name</th>
                <th>Status</th>
                <th>Action</th>
            </thead>
            <tbody>
                @if ($model->messages && count($model->messages) > 0)
                    @php($messageCounter = 1)
                    @foreach ($model->messages()->orderBy('id', 'DESC')->get()  as $message)
                        <tr>
                            <td>{{ $messageCounter }}</td>
                            <td>{{ get_system_date($message->created_at) }} {{ get_system_time($message->created_at) }}</td>
                            <td>{{ $message->name }}</td>
                            <td>
                                <span class="badge bg-{{ $message->status == '1' ? 'success' : 'info' }}">{{ $message->status == '1' ? 'Replied' : 'Not Replied' }}</span>
                            </td>
                            <td>
                                <a id="content_management" href="javascript:;" data-url="{{ route('admin.message.view', $message->id) }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                <a href="javascript:;" id="delete_item" data-id="{{ $message->id }}" data-url="{{ route('admin.message.destroy', $message->id) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @php($messageCounter++)
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